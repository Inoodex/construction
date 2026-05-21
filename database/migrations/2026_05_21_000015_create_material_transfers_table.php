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
        Schema::create('material_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->onDelete('restrict');
            $table->foreignId('to_site_id')->nullable()->constrained('sites')->onDelete('restrict');
            $table->string('transfer_number')->unique();
            $table->enum('status', ['pending', 'transit', 'completed', 'cancelled'])->default('pending');
            $table->date('transfer_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_transfers');
    }
};
