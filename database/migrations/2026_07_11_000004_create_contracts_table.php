<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number', 50)->unique();
            $table->string('title');
            $table->string('client_name');
            $table->enum('contract_type', ['main', 'subcontract', 'supply', 'consultancy'])->default('main');
            $table->decimal('contract_value', 15, 2);
            $table->string('currency', 10)->default('BDT');
            $table->date('signing_date');
            $table->date('commencement_date');
            $table->date('completion_date')->nullable();
            $table->date('extended_completion_date')->nullable();
            $table->decimal('retention_percentage', 5, 2)->default(5.00);
            $table->decimal('liquidated_damages_rate', 10, 2)->nullable();
            $table->decimal('advance_payment_percentage', 5, 2)->nullable();
            $table->enum('status', ['draft', 'active', 'suspended', 'completed', 'terminated'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
