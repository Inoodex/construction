<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('punch_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('punch_list_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->string('location')->nullable();
            $table->enum('trade', ['civil', 'electrical', 'mechanical', 'plumbing', 'painting', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'completed', 'verified'])->default('open');
            $table->string('assigned_to')->nullable();
            $table->date('completed_date')->nullable();
            $table->date('verified_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('punch_list_items');
    }
};
