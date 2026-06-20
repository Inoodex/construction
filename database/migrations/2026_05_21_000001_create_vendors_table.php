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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('trade_category'); // e.g. Cement, Steel, Bricks, Sand, Electrical, Plumbing, etc.
            $table->enum('status', ['active', 'inactive', 'pending', 'approved', 'rejected', 'blacklisted'])->default('pending');
            $table->decimal('credit_limit', 15, 2)->default(0.00);
            $table->string('payment_terms')->nullable(); // e.g. Net 30, Cash on Delivery
            $table->unsignedTinyInteger('performance_rating')->default(5); // 1 to 5 stars
            $table->boolean('is_blacklisted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
