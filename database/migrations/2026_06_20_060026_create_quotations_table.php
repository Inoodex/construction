<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfq_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('quotation_number')->nullable();
            $table->date('submitted_date');
            $table->text('notes')->nullable();
            $table->boolean('is_winner')->default(false);
            $table->timestamps();

            $table->unique(['rfq_id', 'vendor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
