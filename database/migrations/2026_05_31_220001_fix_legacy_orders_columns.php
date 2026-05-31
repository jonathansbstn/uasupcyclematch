<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('produk_id')->nullable()->change();
            $table->integer('qty')->nullable()->change();
            $table->integer('total_harga')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('produk_id')->nullable(false)->change();
            $table->integer('qty')->nullable(false)->change();
            $table->integer('total_harga')->nullable(false)->change();
        });
    }
};
