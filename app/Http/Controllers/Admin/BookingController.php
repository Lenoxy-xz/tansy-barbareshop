<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user','layanan','pembayaran'])->orderByDesc('id_booking');
        if ($request->status)  $query->where('status_booking', $request->status);
        if ($request->tanggal) $query->whereDate('tanggal_booking', $request->tanggal);
        $bookings = $query->get();
        return view('admin.booking', compact('bookings'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id_booking'     => 'required|exists:booking,id_booking',
            'status_booking' => 'required|in:pending,diproses,sedang_melayani,hampir_selesai,selesai,dibatalkan',
        ]);

        $booking = Booking::findOrFail($request->id_booking);
        $booking->update(['status_booking' => $request->status_booking]);

        // Jika selesai + cash → otomatis lunas
        if ($request->status_booking === 'selesai' && $booking->metode_pembayaran === 'cash') {
            $booking->pembayaran()->update([
                'status_pembayaran'  => 'lunas',
                'tanggal_pembayaran' => today(),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Status berhasil diupdate']);
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return back()->with('success', 'Booking berhasil dihapus!');
    }
}
