<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcontract_progress_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcontract_agreement_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('work_completed_value', 15, 2)->default(0);
            $table->decimal('previous_certified_value', 15, 2)->default(0);
            $table->decimal('total_certified_to_date', 15, 2)->default(0);
            $table->decimal('retention_amount', 15, 2)->default(0);
            $table->decimal('retention_released', 15, 2)->default(0);
            $table->decimal('net_payable', 15, 2)->default(0);
            $table->string('status', 50)->default('draft');
            $table->foreignId('certified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('certified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcontract_progress_payments');
    }
};
