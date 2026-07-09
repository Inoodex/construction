<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ncr_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('punch_list_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('car_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->text('preventive_action')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('target_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'verified', 'closed'])->default('open');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('verified_date')->nullable();
            $table->text('effectiveness_check')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrective_actions');
    }
};
