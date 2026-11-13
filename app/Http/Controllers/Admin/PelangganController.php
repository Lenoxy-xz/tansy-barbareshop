<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = User::where('role','pelanggan')->withCount('bookings')->orderByDesc('id_user')->get();
        return view('admin.pelanggan', compact('pelanggan'));
    }

    public function destroy($id)
    {
        User::where('id_user',$id)->where('role','pelanggan')->firstOrFail()->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus!');
    }
}
