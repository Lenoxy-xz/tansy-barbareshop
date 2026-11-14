<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Berkas unggahan (foto layanan, foto profil, bukti pembayaran) disimpan di database
// karena platform serverless (Vercel) tidak memiliki disk permanen.
return new class extends Migration {
    public function up(): void {
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->unique();
            $table->string('jenis', 20);          // layanan | profil | pembayaran
            $table->string('mime', 50);
            $table->unsignedInteger('ukuran');
            $table->longText('isi');              // isi berkas dalam base64
            $table->timestamp('created_at')->nullable();
            $table->index('jenis');
        });
    }
    public function down(): void { Schema::dropIfExists('berkas'); }
};
