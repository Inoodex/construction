<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->year('year')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('acquisition_type', 50)->default('owned'); // owned / hired
            $table->decimal('purchase_cost', 12, 2)->default(0);
            $table->date('purchase_date')->nullable();
            $table->integer('useful_life_years')->default(5);
            $table->decimal('salvage_value', 12, 2)->default(0);
            $table->decimal('current_value', 12, 2)->default(0);
            $table->string('status', 50)->default('active'); // active / under-maintenance / retired
            $table->string('location')->nullable();
            $table->string('operator')->nullable();
            $table->integer('meter_hours')->default(0);
            $table->integer('maintenance_interval_hours')->nullable();
            $table->integer('next_maintenance_hours')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
