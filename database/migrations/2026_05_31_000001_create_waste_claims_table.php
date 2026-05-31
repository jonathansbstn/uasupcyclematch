<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waste_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('textile_id')->constrained('textiles')->cascadeOnDelete();
            $table->foreignId('upcycler_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waste_claims');
    }
};
