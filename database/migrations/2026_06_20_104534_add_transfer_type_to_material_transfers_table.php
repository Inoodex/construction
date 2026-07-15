<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_transfers', function (Blueprint $table) {
            $table->string('transfer_type', 50)->default('warehouse_to_site')->after('transfer_number');
            $table->foreignId('from_site_id')->nullable()->constrained('sites')->nullOnDelete()->after('from_warehouse_id');
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses')->nullOnDelete()->after('to_site_id');
        });

        if (config('database.default') === 'mysql') {
            DB::statement('ALTER TABLE material_transfers MODIFY COLUMN status VARCHAR(50) DEFAULT "pending" NOT NULL');
        }
    }

    public function down(): void
    {
        Schema::table('material_transfers', function (Blueprint $table) {
            $table->dropColumn(['transfer_type', 'from_site_id', 'to_warehouse_id']);
        });
    }
};
