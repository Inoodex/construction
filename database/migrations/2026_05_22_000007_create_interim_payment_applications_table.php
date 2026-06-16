<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interim_payment_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->string('ipa_number')->unique();
            $table->string('title');
            $table->date('application_date');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('previous_cumulative_amount', 15, 2)->default(0);
            $table->decimal('applied_amount', 15, 2)->default(0);
            $table->decimal('certified_amount', 15, 2)->default(0);
            $table->decimal('retention_rate', 5, 2)->default(5.00);
            $table->decimal('retention_amount', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'certified', 'approved', 'rejected', 'paid'])->default('draft');
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('certified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('certified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('ipa_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipa_id')->constrained('interim_payment_applications')->onDelete('cascade');
            $table->foreignId('boq_item_id')->nullable()->constrained('boq_items')->onDelete('set null');
            $table->string('item_number');
            $table->text('description');
            $table->string('unit');
            $table->decimal('previous_quantity', 12, 4)->default(0);
            $table->decimal('current_quantity', 12, 4)->default(0);
            $table->decimal('cumulative_quantity', 12, 4)->default(0);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('previous_amount', 15, 2)->default(0);
            $table->decimal('current_amount', 15, 2)->default(0);
            $table->decimal('cumulative_amount', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipa_items');
        Schema::dropIfExists('interim_payment_applications');
    }
};
