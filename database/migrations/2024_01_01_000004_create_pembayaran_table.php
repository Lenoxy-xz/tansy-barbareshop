<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->unsignedBigInteger('id_booking')->unique();
            $table->decimal('total_bayar', 10, 2);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['belum_dibayar','menunggu_verifikasi','lunas'])->default('belum_dibayar');
            $table->date('tanggal_pembayaran')->nullable();
            $table->foreign('id_booking')->references('id_booking')->on('booking')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('pembayaran'); }
};
