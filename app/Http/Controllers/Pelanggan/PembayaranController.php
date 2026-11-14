<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $pembayaranList = Pembayaran::with(['booking.layanan'])->whereHas('booking', fn($q) => $q->where('id_user', Auth::id()))->orderByDesc('id_pembayaran')->get();
        $focusBooking   = (int)$request->get('id', 0);
        return view('pelanggan.pembayaran', compact('pembayaranList','focusBooking'));
    }

    public function upload(Request $request)
    {
        $request->validate(['id_pembayaran' => 'required|exists:pembayaran,id_pembayaran', 'bukti' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048']);
        $p = Pembayaran::whereHas('booking', fn($q) => $q->where('id_user', Auth::id()))->findOrFail($request->id_pembayaran);
        if ($p->bukti_pembayaran) {
            foreach ([storage_path('app/pembayaran/'), public_path('uploads/pembayaran/')] as $dir) {
                $lama = $dir.basename($p->bukti_pembayaran);
                if (is_file($lama)) unlink($lama);
            }
        }
        $file = $request->file('bukti');
        $filename = $file->hashName();   // nama acak + ekstensi dari isi file, bukan dari klien
        $file->move(storage_path('app/pembayaran'), $filename);
        $p->update(['bukti_pembayaran' => $filename, 'status_pembayaran' => 'menunggu_verifikasi', 'tanggal_pembayaran' => today()]);
        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}
