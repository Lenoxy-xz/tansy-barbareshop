<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = User::where('role', 'pelanggan')->count();
        $totalBooking   = Booking::count();
        $bookingHari    = Booking::whereDate('tanggal_booking', today())->count();
        $bookingPending = Booking::where('status_booking', 'pending')->count();

        $pendapatanHari = Pembayaran::where('status_pembayaran', 'lunas')
            ->whereDate('tanggal_pembayaran', today())->sum('total_bayar');

        $pendapatanBulan = Pembayaran::where('status_pembayaran', 'lunas')
            ->whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year)
            ->sum('total_bayar');

        $bookingTerbaru = Booking::with(['user', 'layanan'])
            ->orderByDesc('id_booking')->limit(8)->get();

        // Grafik 7 hari terakhir
        $grafik = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl   = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('d/m');
            $nilai = Pembayaran::where('status_pembayaran', 'lunas')
                        ->whereDate('tanggal_pembayaran', $tgl)->sum('total_bayar');
            $grafik[] = ['label' => $label, 'nilai' => (float)$nilai];
        }

        $terlaris = Layanan::withCount('bookings')->orderByDesc('bookings_count')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalPelanggan', 'totalBooking', 'bookingHari', 'bookingPending',
            'pendapatanHari', 'pendapatanBulan', 'bookingTerbaru', 'grafik', 'terlaris'
        ));
    }
}
