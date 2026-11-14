<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Support\Berkas;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::orderByDesc('id_layanan')->get();
        return view('admin.layanan', compact('layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan'   => 'required|string|max:100',
            'harga'          => 'required|numeric|min:1000',
            'estimasi_waktu' => 'required|integer|min:5',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = Berkas::simpan($request->file('foto'), 'layanan');
        }

        Layanan::create([
            'nama_layanan'   => $request->nama_layanan,
            'deskripsi'      => $request->deskripsi,
            'harga'          => $request->harga,
            'estimasi_waktu' => $request->estimasi_waktu,
            'foto'           => $foto,
        ]);

        return back()->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $layanan = Layanan::findOrFail($id);

        $request->validate([
            'nama_layanan'   => 'required|string|max:100',
            'harga'          => 'required|numeric|min:1000',
            'estimasi_waktu' => 'required|integer|min:5',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $foto = $layanan->foto;
        if ($request->hasFile('foto')) {
            Berkas::hapus($foto);   // hapus foto lama
            $foto = Berkas::simpan($request->file('foto'), 'layanan');
        }

        $layanan->update([
            'nama_layanan'   => $request->nama_layanan,
            'deskripsi'      => $request->deskripsi,
            'harga'          => $request->harga,
            'estimasi_waktu' => $request->estimasi_waktu,
            'foto'           => $foto,
        ]);

        return back()->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $layanan = Layanan::findOrFail($id);

        if ($layanan->bookings()->count() > 0) {
            return back()->with('error', 'Layanan tidak bisa dihapus karena masih ada booking!');
        }

        Berkas::hapus($layanan->foto);

        $layanan->delete();
        return back()->with('success', 'Layanan berhasil dihapus!');
    }
}
