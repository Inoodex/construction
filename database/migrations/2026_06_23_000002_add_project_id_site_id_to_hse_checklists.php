<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hse_checklists', function (Blueprint $table) {
            $table->dropColumn('location');
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hse_checklists', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['site_id']);
            $table->dropColumn(['project_id', 'site_id']);
            $table->string('location')->nullable();
        });
    }
};
