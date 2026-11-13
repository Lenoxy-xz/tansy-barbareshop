<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->string('nama_layanan', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->integer('estimasi_waktu')->comment('dalam menit');
            $table->string('foto')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('layanan'); }
};
