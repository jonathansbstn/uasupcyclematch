<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('textiles', function (Blueprint $table) {
            if (!Schema::hasColumn('textiles', 'address')) {
                $table->string('address')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('textiles', 'image')) {
                $table->string('image')->nullable()->after('address');
            }
            if (!Schema::hasColumn('textiles', 'claimed_at')) {
                $table->timestamp('claimed_at')->nullable()->after('claimed_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('textiles', function (Blueprint $table) {
            $table->dropColumn(['address', 'image', 'claimed_at']);
        });
    }
};
