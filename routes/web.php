<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController  as AdminDashboard;
use App\Http\Controllers\Admin\LayananController    as AdminLayanan;
use App\Http\Controllers\Admin\BookingController    as AdminBooking;
use App\Http\Controllers\Admin\PelangganController  as AdminPelanggan;
use App\Http\Controllers\Admin\TransaksiController  as AdminTransaksi;
use App\Http\Controllers\Admin\TestimoniController  as AdminTestimoni;
use App\Http\Controllers\Admin\LaporanController    as AdminLaporan;
use App\Http\Controllers\Pelanggan\DashboardController  as PelangganDashboard;
use App\Http\Controllers\Pelanggan\BookingController    as PelangganBooking;
use App\Http\Controllers\Pelanggan\RiwayatController;
use App\Http\Controllers\Pelanggan\PembayaranController;
use App\Http\Controllers\Pelanggan\TestimoniController  as PelangganTestimoni;
use App\Http\Controllers\Pelanggan\ProfilController;

// ── Landing Page ──────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Auth Routes ───────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class,    'showForm'])->name('login');
    Route::post('/login',    [LoginController::class,    'login'])->name('login.post');
    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── Admin Routes ──────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Layanan CRUD
    Route::get('/layanan',         [AdminLayanan::class, 'index'])->name('layanan.index');
    Route::post('/layanan',        [AdminLayanan::class, 'store'])->name('layanan.store');
    Route::put('/layanan/{id}',    [AdminLayanan::class, 'update'])->name('layanan.update');
    Route::delete('/layanan/{id}', [AdminLayanan::class, 'destroy'])->name('layanan.destroy');

    // Booking
    Route::get('/booking',             [AdminBooking::class, 'index'])->name('booking.index');
    Route::post('/booking/status',     [AdminBooking::class, 'updateStatus'])->name('booking.status');
    Route::delete('/booking/{id}',     [AdminBooking::class, 'destroy'])->name('booking.destroy');

    // Pelanggan
    Route::get('/pelanggan',           [AdminPelanggan::class, 'index'])->name('pelanggan.index');
    Route::delete('/pelanggan/{id}',   [AdminPelanggan::class, 'destroy'])->name('pelanggan.destroy');

    // Transaksi
    Route::get('/transaksi',             [AdminTransaksi::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/verifikasi', [AdminTransaksi::class, 'verifikasi'])->name('transaksi.verifikasi');

    // Testimoni
    Route::get('/testimoni',           [AdminTestimoni::class, 'index'])->name('testimoni.index');
    Route::post('/testimoni/status',   [AdminTestimoni::class, 'updateStatus'])->name('testimoni.status');
    Route::delete('/testimoni/{id}',   [AdminTestimoni::class, 'destroy'])->name('testimoni.destroy');

    // Laporan
    Route::get('/laporan', [AdminLaporan::class, 'index'])->name('laporan.index');
});

// ── Pelanggan Routes ──────────────────────────────────────────────────────
Route::prefix('pelanggan')->name('pelanggan.')->middleware(['auth', 'role:pelanggan'])->group(function () {
    Route::get('/dashboard',  [PelangganDashboard::class, 'index'])->name('dashboard');

    Route::get('/booking',    [PelangganBooking::class, 'index'])->name('booking.index');
    Route::post('/booking',   [PelangganBooking::class, 'store'])->name('booking.store');

    Route::get('/riwayat',    [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat/cancel/{id}', [RiwayatController::class, 'cancel'])->name('riwayat.cancel');

    Route::get('/pembayaran',  [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::post('/pembayaran/upload', [PembayaranController::class, 'upload'])->name('pembayaran.upload');

    Route::get('/testimoni',   [PelangganTestimoni::class, 'index'])->name('testimoni.index');
    Route::post('/testimoni',  [PelangganTestimoni::class, 'store'])->name('testimoni.store');
    Route::delete('/testimoni/{id}', [PelangganTestimoni::class, 'destroy'])->name('testimoni.destroy');

    Route::get('/profil',      [ProfilController::class, 'index'])->name('profil.index');
    Route::post('/profil',     [ProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});
