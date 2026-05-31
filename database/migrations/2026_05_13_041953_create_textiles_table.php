<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('textiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('fabric_type'); // Katun, Denim, Sutra, Polyester
            $table->text('description');
            $table->decimal('weight', 8, 2); // berat dalam kg
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('status')->default('available'); // available, claimed, processing, completed
            $table->unsignedBigInteger('claimed_by')->nullable();
            $table->string('product_image')->nullable();
            $table->timestamps();
 
            $table->foreign('claimed_by')->references('id')->on('users')->onDelete('set null');
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('textiles');
    }
};