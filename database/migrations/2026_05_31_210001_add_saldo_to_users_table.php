<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'saldo')) {
                $table->integer('saldo')->default(0)->after('koin'); // Saldo pendapatan Upcycler (dalam Rupiah)
            }
            if (!Schema::hasColumn('users', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('saldo');
            }
            if (!Schema::hasColumn('users', 'bank_account')) {
                $table->string('bank_account')->nullable()->after('bank_name');
            }
            if (!Schema::hasColumn('users', 'bank_holder')) {
                $table->string('bank_holder')->nullable()->after('bank_account');
            }
        });

        // Tambah kolom status update ke orders jika belum ada
        if (!Schema::hasColumn('orders', 'notes_for_buyer')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('notes_for_buyer')->nullable()->after('notes');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('users','saldo')       ? 'saldo'       : null,
                Schema::hasColumn('users','bank_name')   ? 'bank_name'   : null,
                Schema::hasColumn('users','bank_account')? 'bank_account': null,
                Schema::hasColumn('users','bank_holder') ? 'bank_holder' : null,
            ]));
        });
    }
};
