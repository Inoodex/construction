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
        Schema::create('material_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_transfer_id')
                  ->constrained('material_transfers')
                  ->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('restrict');
            $table->decimal('quantity', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_transfer_items');
    }
};
