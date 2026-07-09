<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itp_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itp_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('specification_reference')->nullable();
            $table->enum('inspection_type', ['visual', 'dimensional', 'testing', 'documentation'])->default('visual');
            $table->text('acceptance_criteria')->nullable();
            $table->enum('method', ['observation', 'measurement', 'testing', 'review'])->default('observation');
            $table->enum('frequency', ['each_occurrence', 'daily', 'weekly', 'monthly'])->default('each_occurrence');
            $table->enum('status', ['pending', 'in_progress', 'passed', 'failed', 'n_a'])->default('pending');
            $table->text('result')->nullable();
            $table->date('inspected_date')->nullable();
            $table->string('inspector')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itp_items');
    }
};
