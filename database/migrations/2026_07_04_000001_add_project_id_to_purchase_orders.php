<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('vendor_id')->constrained()->nullOnDelete();
        });

        DB::statement('UPDATE purchase_orders SET project_id = (SELECT pr.project_id FROM purchase_requisitions pr WHERE pr.id = purchase_orders.purchase_requisition_id) WHERE purchase_requisition_id IS NOT NULL');
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropColumn('project_id');
        });
    }
};
