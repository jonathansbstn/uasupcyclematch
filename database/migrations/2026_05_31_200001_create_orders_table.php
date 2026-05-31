<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Cek dan tambah kolom yang belum ada
            if (!Schema::hasColumn('orders', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete()->after('buyer_id');
            }
            if (!Schema::hasColumn('orders', 'quantity')) {
                $table->integer('quantity')->default(1)->after('product_id');
            }
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->integer('total_price')->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('orders', 'recipient_name')) {
                $table->string('recipient_name')->nullable()->after('total_price');
            }
            if (!Schema::hasColumn('orders', 'recipient_phone')) {
                $table->string('recipient_phone')->nullable()->after('recipient_name');
            }
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable()->after('recipient_phone');
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->default('transfer')->after('city');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('orders', 'product_id')     ? 'product_id'     : null,
                Schema::hasColumn('orders', 'quantity')       ? 'quantity'       : null,
                Schema::hasColumn('orders', 'total_price')    ? 'total_price'    : null,
                Schema::hasColumn('orders', 'recipient_name') ? 'recipient_name' : null,
                Schema::hasColumn('orders', 'recipient_phone')? 'recipient_phone': null,
                Schema::hasColumn('orders', 'address')        ? 'address'        : null,
                Schema::hasColumn('orders', 'city')           ? 'city'           : null,
                Schema::hasColumn('orders', 'payment_method') ? 'payment_method' : null,
                Schema::hasColumn('orders', 'notes')          ? 'notes'          : null,
                Schema::hasColumn('orders', 'paid_at')        ? 'paid_at'        : null,
            ]));
        });
    }
};
