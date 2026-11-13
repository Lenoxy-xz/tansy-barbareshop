<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $layanan         = Layanan::orderBy('harga')->get();
        $selectedLayanan = (int)$request->get('layanan', 0);
        $tanggalCek      = $request->get('cek_tanggal', today()->format('Y-m-d'));

        // Ambil semua booking aktif pada tanggal tersebut beserta estimasi layanannya
        $bookingAktif = Booking::with('layanan')
            ->whereDate('tanggal_booking', $tanggalCek)
            ->where('status_booking', '!=', 'dibatalkan')
            ->orderBy('jam_booking')
            ->get();

        // Buat array waktu terblokir
        $waktuTerblokir = [];
        foreach ($bookingAktif as $bk) {
            [$h, $m]      = explode(':', $bk->jam_booking);
            $mulaiMenit   = (int)$h * 60 + (int)$m;
            $selesaiMenit = $mulaiMenit + (int)$bk->layanan->estimasi_waktu;
            $waktuTerblokir[] = [
                'mulai_str'    => str_pad((int)$h, 2, '0', STR_PAD_LEFT).':'.str_pad((int)$m, 2, '0', STR_PAD_LEFT),
                'selesai_str'  => str_pad(floor($selesaiMenit/60), 2, '0', STR_PAD_LEFT).':'.str_pad($selesaiMenit%60, 2, '0', STR_PAD_LEFT),
                'nama_layanan' => $bk->layanan->nama_layanan,
                'estimasi'     => $bk->layanan->estimasi_waktu,
            ];
        }

        return view('pelanggan.booking', compact(
            'layanan', 'selectedLayanan', 'tanggalCek', 'waktuTerblokir'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_layanan'        => 'required|exists:layanan,id_layanan',
            'tanggal_booking'   => 'required|date|after_or_equal:today',
            'jam_booking'       => 'required',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
        ], [
            'id_layanan.required'            => 'Pilih layanan terlebih dahulu!',
            'tanggal_booking.after_or_equal' => 'Tanggal tidak boleh di masa lalu!',
            'jam_booking.required'           => 'Masukkan jam booking!',
            'metode_pembayaran.required'     => 'Pilih metode pembayaran!',
        ]);

        // Validasi jam antara 09:00 - 20:00
        [$jH, $jM]  = explode(':', $request->jam_booking);
        $mulaiMenit = (int)$jH * 60 + (int)$jM;
        if ($mulaiMenit < 9*60 || $mulaiMenit > 20*60) {
            return back()->withErrors(['jam_booking' => 'Jam booking harus antara 09:00 - 20:00!'])->withInput();
        }

        // Ambil estimasi layanan
        $layanan      = Layanan::findOrFail($request->id_layanan);
        $estimasi     = (int)$layanan->estimasi_waktu;
        $selesaiMenit = $mulaiMenit + $estimasi;

        // Cek bentrok dengan booking yang sudah ada
        $bookingAda = Booking::with('layanan')
            ->where('tanggal_booking', $request->tanggal_booking)
            ->where('status_booking', '!=', 'dibatalkan')
            ->get();

        foreach ($bookingAda as $ada) {
            [$aH, $aM]  = explode(':', $ada->jam_booking);
            $adaMulai   = (int)$aH * 60 + (int)$aM;
            $adaSelesai = $adaMulai + (int)$ada->layanan->estimasi_waktu;

            if ($mulaiMenit < $adaSelesai && $selesaiMenit > $adaMulai) {
                $mulaiStr   = str_pad((int)$aH, 2, '0', STR_PAD_LEFT).':'.str_pad((int)$aM, 2, '0', STR_PAD_LEFT);
                $selesaiStr = str_pad(floor($adaSelesai/60), 2, '0', STR_PAD_LEFT).':'.str_pad($adaSelesai%60, 2, '0', STR_PAD_LEFT);
                return back()->withErrors([
                    'jam_booking' => "Waktu bentrok dengan layanan \"{$ada->layanan->nama_layanan}\" jam $mulaiStr - $selesaiStr. Pilih jam lain!"
                ])->withInput();
            }
        }

        // Hitung nomor antrian
        $nomorAntrian = (Booking::where('tanggal_booking', $request->tanggal_booking)
            ->where('status_booking', '!=', 'dibatalkan')
            ->max('nomor_antrian') ?? 0) + 1;

        // Format jam jadi HH:MM:SS
        $jamDB = $request->jam_booking . ':00';

        $booking = Booking::create([
            'kode_booking'      => Booking::generateKode(),
            'id_user'           => Auth::user()->id_user,
            'id_layanan'        => $request->id_layanan,
            'tanggal_booking'   => $request->tanggal_booking,
            'jam_booking'       => $jamDB,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan'           => $request->catatan ?? '',
            'status_booking'    => 'pending',
            'nomor_antrian'     => $nomorAntrian,
        ]);

        Pembayaran::create([
            'id_booking'        => $booking->id_booking,
            'total_bayar'       => $layanan->harga,
            'status_pembayaran' => 'belum_dibayar',
        ]);

        return redirect()->route('pelanggan.riwayat.index')
            ->with('success', "Booking berhasil! Kode: {$booking->kode_booking}. Nomor antrian: #{$nomorAntrian}");
    }
}
