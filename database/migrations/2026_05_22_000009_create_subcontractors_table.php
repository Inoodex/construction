<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('trade_category')->nullable();
            $table->string('specialization')->nullable();
            $table->string('license_number')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->unsignedTinyInteger('performance_rating')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontractors');
    }
};
