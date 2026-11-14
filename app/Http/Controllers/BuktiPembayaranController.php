<?php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Support\Berkas;
use Illuminate\Support\Facades\Auth;

/**
 * Menyajikan bukti pembayaran. File disimpan di storage/app/pembayaran (di luar folder public),
 * sehingga tidak bisa dibuka lewat URL langsung. Hanya admin dan pemilik booking yang boleh melihat.
 */
class BuktiPembayaranController extends Controller
{
    public function show(Pembayaran $pembayaran)
    {
        $user = Auth::user();
        $milikSendiri = $pembayaran->booking && (int) $pembayaran->booking->id_user === (int) $user->id_user;
        abort_unless($user->role === 'admin' || $milikSendiri, 403);
        abort_unless($pembayaran->bukti_pembayaran, 404);

        $nama = basename($pembayaran->bukti_pembayaran);          // cegah path traversal
        $berkas = Berkas::ambil($nama, ['pembayaran']);
        if ($berkas) {
            return Berkas::respons($berkas, 'private, max-age=0, must-revalidate');
        }

        // Berkas lama (sebelum disimpan di database)
        $path = storage_path('app/pembayaran/'.$nama);
        if (!is_file($path)) {
            $path = public_path('uploads/pembayaran/'.$nama);
            abort_unless(is_file($path), 404);
        }

        return response()->file($path, [
            'Cache-Control'       => 'private, max-age=0, must-revalidate',
            'Content-Disposition' => 'inline',
        ]);
    }
}
