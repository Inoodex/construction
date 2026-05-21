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
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_template_id')
                  ->constrained('report_templates')
                  ->onDelete('cascade');
            $table->json('recipients'); // JSON array of email addresses or user IDs
            $table->enum('frequency', ['daily', 'weekly', 'monthly'])->default('weekly');
            $table->timestamp('next_run_at');
            $table->timestamp('last_run_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_reports');
    }
};
