<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->date('maintenance_date');
            $table->string('type', 50)->default('preventive'); // preventive / corrective / inspection
            $table->text('description');
            $table->integer('meter_hours')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->string('vendor')->nullable();
            $table->date('next_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenance');
    }
};
