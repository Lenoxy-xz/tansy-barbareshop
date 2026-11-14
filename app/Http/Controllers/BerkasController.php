<?php
namespace App\Http\Controllers;

use App\Support\Berkas;

/** Menyajikan foto layanan dan foto profil (publik). Bukti pembayaran ada di BuktiPembayaranController. */
class BerkasController extends Controller
{
    public function foto(string $nama)
    {
        $nama = basename($nama);
        $berkas = Berkas::ambil($nama, ['layanan', 'profil']);
        if ($berkas) {
            return Berkas::respons($berkas, 'public, max-age=86400');
        }
        // Foto lama yang masih tersimpan di folder (sebelum penyimpanan di database)
        foreach (['layanan', 'profil'] as $dir) {
            $path = public_path("uploads/$dir/$nama");
            if (is_file($path)) {
                return response()->file($path, ['Cache-Control' => 'public, max-age=86400']);
            }
        }
        abort(404);
    }
}
