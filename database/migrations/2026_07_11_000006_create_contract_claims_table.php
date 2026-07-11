<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('claim_number', 50)->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['time_extension', 'cost_compensation', 'both']);
            $table->decimal('claimed_amount', 15, 2)->nullable();
            $table->integer('claimed_days')->nullable();
            $table->decimal('granted_amount', 15, 2)->nullable();
            $table->integer('granted_days')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'granted', 'partially_granted', 'rejected'])->default('draft');
            $table->date('submitted_date')->nullable();
            $table->date('response_date')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_claims');
    }
};
