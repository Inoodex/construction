<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('concrete_ratios', function (Blueprint $table) {
            $table->decimal('cement_price_per_bag', 10, 2)->nullable()->after('waste_percent');
            $table->decimal('sand_price_per_cft', 10, 2)->nullable()->after('cement_price_per_bag');
            $table->decimal('aggregate_price_per_cft', 10, 2)->nullable()->after('sand_price_per_cft');
        });
    }

    public function down(): void
    {
        Schema::table('concrete_ratios', function (Blueprint $table) {
            $table->dropColumn(['cement_price_per_bag', 'sand_price_per_cft', 'aggregate_price_per_cft']);
        });
    }
};
