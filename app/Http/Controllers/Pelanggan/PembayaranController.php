<?php
namespace App\Http\Controllers\Pelanggan;
use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Support\Berkas;

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
        Berkas::hapus($p->bukti_pembayaran);
        $filename = Berkas::simpan($request->file('bukti'), 'pembayaran');   // disimpan di database, nama dibuat server
        $p->update(['bukti_pembayaran' => $filename, 'status_pembayaran' => 'menunggu_verifikasi', 'tanggal_pembayaran' => today()]);
        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }
}
