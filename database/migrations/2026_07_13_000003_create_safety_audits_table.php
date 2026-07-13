<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safety_audits', function (Blueprint $table) {
            $table->id();
            $table->string('audit_number')->unique();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('auditor_id')->constrained('users')->restrictOnDelete();
            $table->date('audit_date');
            $table->string('audit_type');
            $table->string('scope');
            $table->text('findings')->nullable();
            $table->text('non_conformances')->nullable();
            $table->text('recommendations')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'follow_up'])->default('scheduled');
            $table->integer('score')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safety_audits');
    }
};
