<?php
namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Penyimpanan berkas unggahan di database (tabel `berkas`).
 * Nama berkas dibuat server (acak, ekstensi dari isi berkas), bukan dari klien.
 */
class Berkas
{
    private const MIME_DIIZINKAN = ['image/jpeg', 'image/png', 'image/webp'];

    public static function simpan(UploadedFile $file, string $jenis): string
    {
        $mime = $file->getMimeType();
        if (!in_array($mime, self::MIME_DIIZINKAN, true)) {
            throw new \InvalidArgumentException('Tipe berkas tidak diizinkan.');
        }
        $nama = $file->hashName();
        DB::table('berkas')->insert([
            'nama'       => $nama,
            'jenis'      => $jenis,
            'mime'       => $mime,
            'ukuran'     => $file->getSize(),
            'isi'        => base64_encode(file_get_contents($file->getRealPath())),
            'created_at' => now(),
        ]);
        return $nama;
    }

    public static function ambil(string $nama, array $jenis): ?object
    {
        return DB::table('berkas')->where('nama', basename($nama))->whereIn('jenis', $jenis)->first();
    }

    public static function hapus(?string $nama): void
    {
        if (!$nama) return;
        $nama = basename($nama);
        DB::table('berkas')->where('nama', $nama)->delete();
        // Berkas lama dari sebelum penyimpanan di database (bila ada)
        foreach ([public_path("uploads/layanan/$nama"), public_path("uploads/profil/$nama"),
                  public_path("uploads/pembayaran/$nama"), storage_path("app/pembayaran/$nama")] as $path) {
            if (is_file($path)) @unlink($path);
        }
    }

    public static function respons(object $berkas, string $cache): Response
    {
        return response(base64_decode($berkas->isi), 200, [
            'Content-Type'        => $berkas->mime,
            'Content-Disposition' => 'inline',
            'Cache-Control'       => $cache,
        ]);
    }
}
