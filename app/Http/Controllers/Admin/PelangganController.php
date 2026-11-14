<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pembayaran;
use App\Support\Berkas;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = User::where('role','pelanggan')->withCount('bookings')->orderByDesc('id_user')->get();
        return view('admin.pelanggan', compact('pelanggan'));
    }

    public function destroy($id)
    {
        $user = User::where('id_user',$id)->where('role','pelanggan')->firstOrFail();
        // Kumpulkan nama berkas milik pelanggan ini (foto + bukti pembayaran) sebelum datanya terhapus
        $berkas = Pembayaran::whereHas('booking', fn ($q) => $q->where('id_user', $user->id_user))
                    ->pluck('bukti_pembayaran')->push($user->foto)->filter()->all();
        $user->delete();
        foreach ($berkas as $nama) Berkas::hapus($nama);
        return back()->with('success', 'Pelanggan berhasil dihapus!');
    }
}
