<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('concrete_ratios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->string('reference_no', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('grade', 10)->nullable();
            $table->foreignId('rod_calculation_id')->nullable()->constrained('rod_calculations')->onDelete('set null');
            $table->string('status', 20)->default('draft');
            $table->decimal('waste_percent', 5, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('status');
        });

        Schema::create('concrete_ratio_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('concrete_ratio_id')->constrained('concrete_ratios')->onDelete('cascade');
            $table->foreignId('rod_member_id')->nullable()->constrained('rod_members')->onDelete('set null');
            $table->string('type', 30);
            $table->string('member_code', 100);
            $table->integer('quantity')->default(1);
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('depth', 10, 2)->nullable();
            $table->decimal('thickness', 10, 2)->nullable();
            $table->decimal('volume_m3', 10, 4)->default(0);
            $table->decimal('cement_bags', 10, 2)->default(0);
            $table->decimal('sand_m3', 10, 4)->default(0);
            $table->decimal('aggregate_m3', 10, 4)->default(0);
            $table->decimal('water_liters', 10, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('concrete_ratio_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('concrete_ratio_members');
        Schema::dropIfExists('concrete_ratios');
    }
};
