<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->decimal('steel_price_per_kg', 10, 2)->nullable()->after('waste_percent');
        });
    }

    public function down(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->dropColumn('steel_price_per_kg');
        });
    }
};
