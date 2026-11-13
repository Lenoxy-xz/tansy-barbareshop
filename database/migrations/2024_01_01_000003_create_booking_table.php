<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('booking', function (Blueprint $table) {
            $table->id('id_booking');
            $table->string('kode_booking', 20)->unique();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_layanan');
            $table->date('tanggal_booking');
            $table->time('jam_booking');
            $table->enum('metode_pembayaran', ['cash','transfer','qris']);
            $table->text('catatan')->nullable();
            $table->enum('status_booking', [
                'pending',
                'diproses',
                'sedang_melayani',
                'hampir_selesai',
                'selesai',
                'dibatalkan'
            ])->default('pending');
            $table->integer('nomor_antrian');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanan')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('booking'); }
};
