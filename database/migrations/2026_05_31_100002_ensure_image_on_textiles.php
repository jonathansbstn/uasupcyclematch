<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('textiles', function (Blueprint $table) {
            if (!Schema::hasColumn('textiles', 'image')) {
                $table->string('image')->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        // image column already existed, don't drop
    }
};
