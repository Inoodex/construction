<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->string('tender_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('issue_date');
            $table->date('close_date');
            $table->enum('status', ['draft', 'open', 'closed', 'awarded', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });

        Schema::create('tender_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained('tenders')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('restrict');
            $table->decimal('bid_amount', 15, 2);
            $table->text('notes')->nullable();
            $table->integer('technical_score')->nullable();
            $table->integer('financial_score')->nullable();
            $table->integer('total_score')->nullable();
            $table->enum('status', ['submitted', 'evaluated', 'shortlisted', 'awarded', 'rejected'])->default('submitted');
            $table->date('submitted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tender_bids');
        Schema::dropIfExists('tenders');
    }
};
