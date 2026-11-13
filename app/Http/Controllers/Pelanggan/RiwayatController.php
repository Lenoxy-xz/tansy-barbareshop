<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['layanan','pembayaran'])->where('id_user', Auth::id())->orderByDesc('id_booking')->get();
        return view('pelanggan.riwayat', compact('bookings'));
    }

    public function cancel($id)
    {
        $booking = Booking::where('id_booking',$id)->where('id_user', Auth::id())->where('status_booking','pending')->firstOrFail();
        $booking->update(['status_booking' => 'dibatalkan']);
        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
