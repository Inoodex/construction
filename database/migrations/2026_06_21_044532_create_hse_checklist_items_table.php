<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hse_checklist_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->boolean('is_compliant')->default(false);
            $table->text('remarks')->nullable();
            $table->integer('order_index')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_checklist_items');
    }
};
