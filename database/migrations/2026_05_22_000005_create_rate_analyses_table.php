<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->string('ra_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('total_rate', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'revised'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('rate_analysis_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rate_analysis_id')->constrained('rate_analyses')->onDelete('cascade');
            $table->enum('resource_type', ['labour', 'material', 'equipment', 'subcontract', 'overhead']);
            $table->string('resource_description');
            $table->string('unit');
            $table->decimal('quantity', 12, 4);
            $table->decimal('unit_rate', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_analysis_items');
        Schema::dropIfExists('rate_analyses');
    }
};
