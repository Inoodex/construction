<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('phase_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('milestone_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['phase_id']);
            $table->dropForeign(['milestone_id']);
            $table->dropColumn(['phase_id', 'milestone_id']);
        });
    }
};
