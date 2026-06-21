<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hse_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('checklist_type', 100)->default('general'); // general / fire / electrical / scaffolding / ppe / excavation / other
            $table->string('location')->nullable();
            $table->date('inspection_date');
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 50)->default('open'); // open / closed
            $table->text('findings')->nullable();
            $table->text('corrective_actions')->nullable();
            $table->date('closure_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hse_checklists');
    }
};
