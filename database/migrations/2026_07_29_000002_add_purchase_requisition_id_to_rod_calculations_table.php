<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->foreignId('purchase_requisition_id')->nullable()->after('project_id')->constrained('purchase_requisitions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->dropForeign(['purchase_requisition_id']);
            $table->dropColumn('purchase_requisition_id');
        });
    }
};
