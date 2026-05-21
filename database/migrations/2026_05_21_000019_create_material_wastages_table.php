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
        Schema::create('material_wastages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->foreignId('site_id')->constrained('sites')->onDelete('restrict');
            $table->foreignId('material_id')->constrained('materials')->onDelete('restrict');
            $table->decimal('quantity', 12, 4);
            $table->string('reason');
            $table->date('reported_date');
            $table->foreignId('reported_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_wastages');
    }
};
