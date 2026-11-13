<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with(['booking.user','booking.layanan'])->orderByDesc('id_pembayaran');
        if ($request->status) $query->where('status_pembayaran', $request->status);
        $transaksi = $query->get();
        return view('admin.transaksi', compact('transaksi'));
    }

    public function verifikasi(Request $request)
    {
        $request->validate([
            'id_pembayaran'     => 'required|exists:pembayaran,id_pembayaran',
            'status_pembayaran' => 'required|in:lunas,belum_dibayar',
        ]);
        $p = Pembayaran::findOrFail($request->id_pembayaran);
        $p->update(['status_pembayaran' => $request->status_pembayaran, 'tanggal_pembayaran' => today()]);
        if ($request->status_pembayaran === 'lunas') {
            $p->booking()->update(['status_booking' => 'selesai']);
        }
        return response()->json(['success' => true]);
    }
}
