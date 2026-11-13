<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama', 100);
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('no_hp', 20);
            $table->string('password');
            $table->enum('role', ['admin','pelanggan'])->default('pelanggan');
            $table->string('foto')->nullable();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};
