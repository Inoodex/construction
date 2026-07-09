<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('itp_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('phase', ['foundation', 'superstructure', 'finishing', 'mep', 'other'])->default('other');
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itps');
    }
};
