<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. database/migrations/xxxx_create_users_table.php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('whatsapp')->nullable();
    $table->string('password');
    $table->enum('role', ['contributor', 'upcycler', 'admin'])->default('contributor');
    $table->integer('koin')->default(0); // Menyimpan saldo koin real-time
    $table->boolean('is_verified')->default(false); // Khusus upcycler
    $table->timestamps();
});

// 2. database/migrations/xxxx_create_limbah_kains_table.php
Schema::create('limbah_kains', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Kontributor penyumbang kain
    $table->string('judul');
    $table->enum('jenis_bahan', ['katun', 'denim', 'sutra', 'polyester']);
    $table->decimal('berat_kg', 5, 2);
    $table->text('deskripsi')->nullable();
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 11, 7)->nullable(); // <== Diperbaiki jadi 11 agar tidak terpotong di Indonesia
    $table->enum('status', ['available', 'claimed', 'processing', 'completed'])->default('available');
    $table->foreignId('penjahit_id')->nullable()->constrained('users')->nullOnDelete(); // <== Ditambahkan agar aman jika user dihapus
    $table->timestamps();
});

// 3. database/migrations/xxxx_create_koin_transactions_table.php
Schema::create('koin_transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->integer('amount'); // Positif = masuk (dari upload), negatif = keluar (dari pencairan)
    $table->enum('type', ['upload', 'pencairan']);
    $table->string('keterangan')->nullable();
    $table->string('metode')->nullable();   // Bank / E-Wallet
    $table->string('no_rekening')->nullable();
    $table->timestamps();
});

// 4. database/migrations/xxxx_create_produk_jadis_table.php
Schema::create('produk_jadis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('penjahit_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('limbah_id')->constrained('limbah_kains')->cascadeOnDelete();
    $table->string('nama_produk');
    $table->text('deskripsi')->nullable();
    $table->integer('harga');
    $table->integer('stok')->default(0);
    $table->string('foto')->nullable();
    $table->timestamps();
});

// 5. database/migrations/xxxx_create_orders_table.php
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('buyer_id')->constrained('users', 'id')->cascadeOnDelete(); // <== Ditambahkan parameter 'id' target
    $table->foreignId('produk_id')->constrained('produk_jadis')->cascadeOnDelete();
    $table->integer('qty')->default(1);
    $table->integer('total_harga');
    $table->enum('status', ['pending', 'paid', 'shipped', 'done'])->default('paid');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};