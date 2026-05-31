<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upcycler_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('textile_id')->constrained('textiles')->cascadeOnDelete();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->integer('price')->default(0);
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
