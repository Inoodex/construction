<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goods_received_notes', function (Blueprint $table) {
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete()->after('purchase_order_id');
            $table->string('delivery_note')->nullable()->after('received_date');
            $table->string('vehicle_number')->nullable()->after('delivery_note');
        });
    }

    public function down(): void
    {
        Schema::table('goods_received_notes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('site_id');
            $table->dropColumn(['delivery_note', 'vehicle_number']);
        });
    }
};
