<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_test_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('material_name');
            $table->enum('material_type', ['concrete', 'steel', 'soil', 'aggregate', 'cement', 'other'])->default('other');
            $table->string('supplier')->nullable();
            $table->string('batch_number')->nullable();
            $table->string('certificate_number');
            $table->date('test_date');
            $table->enum('test_result', ['pass', 'fail', 'conditional'])->default('pass');
            $table->text('test_parameters')->nullable();
            $table->enum('compliance_status', ['compliant', 'non_compliant', 'pending'])->default('pending');
            $table->string('certificate_file')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_test_certificates');
    }
};
