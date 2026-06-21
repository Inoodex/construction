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
        Schema::table('equipment', function (Blueprint $table) {
            $table->decimal('hire_rate', 10, 2)->nullable()->after('acquisition_type');
            $table->string('hire_rate_period', 20)->nullable()->after('hire_rate'); // daily / weekly / monthly
            $table->date('hire_start_date')->nullable()->after('hire_rate_period');
            $table->date('hire_end_date')->nullable()->after('hire_start_date');
            $table->string('hire_vendor', 255)->nullable()->after('hire_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn(['hire_rate', 'hire_rate_period', 'hire_start_date', 'hire_end_date', 'hire_vendor']);
        });
    }
};
