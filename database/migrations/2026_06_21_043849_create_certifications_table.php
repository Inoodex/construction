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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('certification_name');
            $table->string('issuing_authority')->nullable();
            $table->string('certificate_no')->nullable();
            $table->string('category', 50)->default('certification'); // certification / license / permit
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('status', 50)->default('active'); // active / expired / suspended / revoked
            $table->date('renewal_reminder_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
