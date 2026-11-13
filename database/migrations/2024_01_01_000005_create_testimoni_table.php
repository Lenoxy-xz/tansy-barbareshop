<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id('id_testimoni');
            $table->unsignedBigInteger('id_user');
            $table->integer('rating');
            $table->text('komentar');
            $table->enum('status', ['menunggu','disetujui','ditolak'])->default('menunggu');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('testimoni'); }
};
