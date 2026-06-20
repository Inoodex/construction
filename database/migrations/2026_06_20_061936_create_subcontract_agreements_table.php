<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontract_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('subcontractor_id')->constrained()->cascadeOnDelete();
            $table->string('agreement_number')->unique();
            $table->string('title');
            $table->text('scope_of_work')->nullable();
            $table->date('agreement_date');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('contract_value', 15, 2)->default(0);
            $table->decimal('retention_percentage', 5, 2)->default(5.00);
            $table->text('payment_terms')->nullable();
            $table->text('special_conditions')->nullable();
            $table->text('insurance_requirements')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'terminated', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontract_agreements');
    }
};
