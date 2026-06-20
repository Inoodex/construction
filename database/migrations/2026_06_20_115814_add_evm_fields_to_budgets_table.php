<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->decimal('planned_value', 12, 2)->default(0)->after('actual_amount');
            $table->decimal('earned_value', 12, 2)->default(0)->after('planned_value');
            $table->decimal('actual_cost', 12, 2)->default(0)->after('earned_value');
        });
    }

    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn(['planned_value', 'earned_value', 'actual_cost']);
        });
    }
};
