<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use App\Models\Booking; use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $idUser       = Auth::user()->id_user;
        $totalBooking = Booking::where('id_user', $idUser)->count();
        $bookingAktif = Booking::where('id_user', $idUser)->whereIn('status_booking',['pending','diproses'])->count();
        $totalBayar   = Pembayaran::whereHas('booking', fn($q) => $q->where('id_user', $idUser))->where('status_pembayaran','lunas')->sum('total_bayar');
        $bookingTerbaru = Booking::with(['layanan','pembayaran'])->where('id_user',$idUser)->orderByDesc('id_booking')->limit(5)->get();
        $antreanHariIni = Booking::with('layanan')->where('id_user',$idUser)->whereDate('tanggal_booking',today())->whereIn('status_booking',['pending','diproses'])->orderBy('nomor_antrian')->first();
        return view('pelanggan.dashboard', compact('totalBooking','bookingAktif','totalBayar','bookingTerbaru','antreanHariIni'));
    }
}
