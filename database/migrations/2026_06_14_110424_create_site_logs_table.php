<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('report_type', ['daily_log', 'field_report'])->default('daily_log');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('log_date');
            $table->string('weather_conditions')->nullable();
            $table->decimal('temperature', 5, 1)->nullable();
            $table->integer('worker_count')->nullable();
            $table->text('work_completed')->nullable();
            $table->text('equipment_used')->nullable();
            $table->text('materials_received')->nullable();
            $table->text('issues_notes')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_logs');
    }
};
