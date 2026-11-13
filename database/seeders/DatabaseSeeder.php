<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        DB::table('users')->insert([
            ['nama' => 'Administrator', 'username' => 'admin',    'email' => 'admin@tansybarbershop.com', 'no_hp' => '081234567890', 'password' => Hash::make('password'), 'role' => 'admin',     'foto' => null],
            ['nama' => 'Budi Santoso',  'username' => 'budi123',  'email' => 'budi@gmail.com',            'no_hp' => '081111111111', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'foto' => null],
            ['nama' => 'Andi Prasetyo', 'username' => 'andi99',   'email' => 'andi@gmail.com',            'no_hp' => '082222222222', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'foto' => null],
            ['nama' => 'Rizki Hidayat', 'username' => 'rizki007', 'email' => 'rizki@gmail.com',           'no_hp' => '083333333333', 'password' => Hash::make('password'), 'role' => 'pelanggan', 'foto' => null],
        ]);

        // Layanan
        DB::table('layanan')->insert([
            ['nama_layanan' => 'Potong Rambut Reguler',       'deskripsi' => 'Potong rambut standar dengan hasil rapi dan profesional',             'harga' => 35000, 'estimasi_waktu' => 30, 'foto' => null],
            ['nama_layanan' => 'Potong + Cuci Rambut',        'deskripsi' => 'Potong rambut dan keramas menggunakan shampo premium',                'harga' => 50000, 'estimasi_waktu' => 45, 'foto' => null],
            ['nama_layanan' => 'Cukur Jenggot',               'deskripsi' => 'Perawatan jenggot dengan teknik barbershop klasik',                   'harga' => 25000, 'estimasi_waktu' => 20, 'foto' => null],
            ['nama_layanan' => 'Hair Styling',                'deskripsi' => 'Penataan rambut sesuai keinginan menggunakan pomade premium',         'harga' => 40000, 'estimasi_waktu' => 35, 'foto' => null],
            ['nama_layanan' => 'Potong Rambut + Cukur Jenggot','deskripsi'=> 'Paket lengkap potong rambut dan cukur jenggot',                      'harga' => 55000, 'estimasi_waktu' => 50, 'foto' => null],
            ['nama_layanan' => 'Keramas + Hair Mask',         'deskripsi' => 'Perawatan rambut dengan masker rambut berkualitas',                   'harga' => 60000, 'estimasi_waktu' => 40, 'foto' => null],
        ]);

        // Booking
        DB::table('booking')->insert([
            ['kode_booking' => 'TBS-20240115-001', 'id_user' => 2, 'id_layanan' => 1, 'tanggal_booking' => '2024-01-15', 'jam_booking' => '09:00:00', 'metode_pembayaran' => 'cash',     'catatan' => '', 'status_booking' => 'selesai',  'nomor_antrian' => 1],
            ['kode_booking' => 'TBS-20240115-002', 'id_user' => 3, 'id_layanan' => 5, 'tanggal_booking' => '2024-01-15', 'jam_booking' => '10:00:00', 'metode_pembayaran' => 'transfer', 'catatan' => 'Jangan terlalu pendek', 'status_booking' => 'selesai', 'nomor_antrian' => 2],
            ['kode_booking' => 'TBS-20240116-001', 'id_user' => 4, 'id_layanan' => 2, 'tanggal_booking' => '2024-01-16', 'jam_booking' => '09:30:00', 'metode_pembayaran' => 'qris',     'catatan' => '', 'status_booking' => 'selesai',  'nomor_antrian' => 1],
            ['kode_booking' => 'TBS-'.date('Ymd').'-001', 'id_user' => 2, 'id_layanan' => 3, 'tanggal_booking' => date('Y-m-d'), 'jam_booking' => '14:00:00', 'metode_pembayaran' => 'cash',     'catatan' => '', 'status_booking' => 'pending',  'nomor_antrian' => 1],
            ['kode_booking' => 'TBS-'.date('Ymd').'-002', 'id_user' => 3, 'id_layanan' => 1, 'tanggal_booking' => date('Y-m-d'), 'jam_booking' => '15:00:00', 'metode_pembayaran' => 'transfer', 'catatan' => '', 'status_booking' => 'diproses', 'nomor_antrian' => 2],
        ]);

        // Pembayaran
        DB::table('pembayaran')->insert([
            ['id_booking' => 1, 'total_bayar' => 35000, 'bukti_pembayaran' => null, 'status_pembayaran' => 'lunas',              'tanggal_pembayaran' => '2024-01-15'],
            ['id_booking' => 2, 'total_bayar' => 55000, 'bukti_pembayaran' => null, 'status_pembayaran' => 'lunas',              'tanggal_pembayaran' => '2024-01-15'],
            ['id_booking' => 3, 'total_bayar' => 50000, 'bukti_pembayaran' => null, 'status_pembayaran' => 'lunas',              'tanggal_pembayaran' => '2024-01-16'],
            ['id_booking' => 4, 'total_bayar' => 25000, 'bukti_pembayaran' => null, 'status_pembayaran' => 'belum_dibayar',      'tanggal_pembayaran' => null],
            ['id_booking' => 5, 'total_bayar' => 35000, 'bukti_pembayaran' => null, 'status_pembayaran' => 'menunggu_verifikasi','tanggal_pembayaran' => date('Y-m-d')],
        ]);

        // Testimoni
        DB::table('testimoni')->insert([
            ['id_user' => 2, 'rating' => 5, 'komentar' => 'Pelayanannya sangat memuaskan! Hasilnya rapi dan barbernya ramah. Pasti balik lagi.',  'status' => 'disetujui'],
            ['id_user' => 3, 'rating' => 5, 'komentar' => 'Barbershop terbaik di kota! Tempatnya bersih dan hasilnya sesuai ekspektasi.',         'status' => 'disetujui'],
            ['id_user' => 4, 'rating' => 4, 'komentar' => 'Hasilnya bagus, tinggal tunggu antrian agak lama tapi worth it.',                      'status' => 'disetujui'],
        ]);
    }
}
