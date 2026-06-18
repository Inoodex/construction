<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_guarantees', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->string('type'); // bid, performance, advance, retention
            $table->string('issuing_bank');
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('beneficiary');
            $table->decimal('amount', 15, 2);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->date('return_date')->nullable();
            $table->string('status')->default('active'); // active, expired, encashed, returned
            $table->text('narration')->nullable();
            $table->string('document_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_guarantees');
    }
};
