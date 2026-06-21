<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('fuel_type', 50)->default('diesel'); // diesel / petrol / gas / other
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 20)->default('liters'); // liters / gallons
            $table->decimal('unit_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->integer('meter_hours')->nullable();
            $table->string('vendor')->nullable();
            $table->string('receipt_no')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
    }
};
