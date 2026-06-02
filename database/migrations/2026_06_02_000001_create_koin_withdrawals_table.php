<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koin_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_koin');
            $table->integer('nominal_rupiah');  // jumlah_koin * 2500
            $table->enum('metode', ['bank', 'ewallet']);
            $table->string('nama_bank');        // nama bank / nama e-wallet
            $table->string('nomor_rekening');   // nomor rekening / nomor hp
            $table->string('nama_penerima');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koin_withdrawals');
    }
};
