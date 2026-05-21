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
        Schema::create('goods_received_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_received_note_id')
                  ->constrained('goods_received_notes')
                  ->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('restrict');
            $table->decimal('quantity_received', 12, 4);
            $table->decimal('quantity_accepted', 12, 4);
            $table->decimal('quantity_rejected', 12, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_received_note_items');
    }
};
