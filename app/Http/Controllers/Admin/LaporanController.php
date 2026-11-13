<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tipe      = $request->get('tipe', 'transaksi');
        $tglMulai  = $request->get('tgl_mulai', now()->format('Y-m-01'));
        $tglAkhir  = $request->get('tgl_akhir', now()->format('Y-m-d'));
        $data            = collect();
        $totalPendapatan = 0;

        if ($tipe === 'transaksi') {
            $data = Pembayaran::with(['booking.user','booking.layanan'])
                ->where('status_pembayaran','lunas')
                ->whereBetween('tanggal_pembayaran', [$tglMulai, $tglAkhir])
                ->orderByDesc('tanggal_pembayaran')->get();
            $totalPendapatan = $data->sum('total_bayar');

        } elseif ($tipe === 'booking') {
            $data = Booking::with(['user','layanan'])
                ->whereBetween('tanggal_booking', [$tglMulai, $tglAkhir])
                ->orderByDesc('tanggal_booking')->get();

        } elseif ($tipe === 'layanan_terlaris') {
            $data = Layanan::withCount(['bookings as total_booking' => function ($q) use ($tglMulai, $tglAkhir) {
                $q->whereBetween('tanggal_booking', [$tglMulai, $tglAkhir]);
            }])->orderByDesc('total_booking')->get();
        }

        return view('admin.laporan', compact('data','tipe','tglMulai','tglAkhir','totalPendapatan'));
    }
}
