<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('risk_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['technical', 'safety', 'financial', 'environmental', 'schedule', 'other'])->default('other');
            $table->enum('probability', ['very_low', 'low', 'medium', 'high', 'very_high'])->default('medium');
            $table->enum('impact', ['very_low', 'low', 'medium', 'high', 'very_high'])->default('medium');
            $table->unsignedTinyInteger('risk_score')->default(9);
            $table->enum('status', ['open', 'in_progress', 'mitigated', 'closed'])->default('open');
            $table->foreignId('risk_owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('identified_date');
            $table->date('review_date')->nullable();
            $table->date('due_date')->nullable();
            $table->date('closed_date')->nullable();
            $table->text('mitigation_plan')->nullable();
            $table->text('contingency_plan')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
