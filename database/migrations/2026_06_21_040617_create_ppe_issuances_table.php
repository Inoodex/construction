<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppe_issuances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('item_name');
            $table->string('category', 100)->nullable();
            $table->date('issue_date');
            $table->integer('quantity')->default(1);
            $table->string('size', 50)->nullable();
            $table->string('condition_on_issue', 100)->nullable();
            $table->date('return_date')->nullable();
            $table->string('condition_on_return', 100)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppe_issuances');
    }
};
