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
            $table->dropColumn('formula_version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rod_calculations', function (Blueprint $table) {
            $table->string('formula_version', 10)->default('1.0');
        });
    }
};
