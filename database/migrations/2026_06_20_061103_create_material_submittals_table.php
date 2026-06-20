<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_submittals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('submittal_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('material_name');
            $table->string('manufacturer')->nullable();
            $table->string('brand')->nullable();
            $table->string('model_reference')->nullable();
            $table->text('specification_details')->nullable();
            $table->string('quantity_unit')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'approved_with_conditions', 'rejected', 'resubmitted'])->default('draft');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('submitted_date')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('review_date')->nullable();
            $table->text('review_comments')->nullable();
            $table->date('resubmission_deadline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_submittals');
    }
};
