<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->string('concrete_grade', 20)->nullable()->after('steel_grade');
            $table->decimal('concrete_waste_percent', 5, 2)->nullable()->after('waste_percent');
            $table->decimal('cement_price_per_bag', 10, 2)->nullable()->after('steel_price_per_kg');
            $table->decimal('sand_price_per_cft', 10, 2)->nullable()->after('cement_price_per_bag');
            $table->decimal('aggregate_price_per_cft', 10, 2)->nullable()->after('sand_price_per_cft');
        });
    }

    public function down(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->dropColumn([
                'concrete_grade',
                'concrete_waste_percent',
                'cement_price_per_bag',
                'sand_price_per_cft',
                'aggregate_price_per_cft',
            ]);
        });
    }
};
