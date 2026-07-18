<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rod_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('restrict');
            $table->string('reference_no', 50)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('steel_grade', 20)->nullable();
            $table->string('revision', 50)->nullable();
            $table->string('status', 20)->default('draft');
            $table->string('formula_version', 10)->default('1.0');
            $table->decimal('waste_percent', 5, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('restrict');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('project_id');
            $table->index('status');
            $table->index('created_by');
        });

        Schema::create('rod_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rod_calculation_id')->constrained('rod_calculations')->onDelete('cascade');
            $table->string('type', 30);
            $table->string('member_code', 100);
            $table->integer('quantity')->default(1);
            $table->decimal('length', 10, 2)->nullable();
            $table->decimal('width', 10, 2)->nullable();
            $table->decimal('height', 10, 2)->nullable();
            $table->decimal('depth', 10, 2)->nullable();
            $table->decimal('thickness', 10, 2)->nullable();
            $table->decimal('cover', 10, 2)->default(25);
            $table->integer('sort_order')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('rod_calculation_id');
            $table->index('type');
            $table->index('member_code');
        });

        Schema::create('rod_member_bars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rod_member_id')->constrained('rod_members')->onDelete('cascade');
            $table->string('bar_name', 100);
            $table->string('direction', 20);
            $table->decimal('diameter', 5, 2);
            $table->decimal('spacing', 10, 2)->nullable();
            $table->decimal('hook_length', 10, 2)->default(0);
            $table->decimal('bend_length', 10, 2)->default(0);
            $table->decimal('lap_length', 10, 2)->default(0);
            $table->decimal('actual_size', 10, 2);
            $table->decimal('cutting_length', 10, 2)->default(0);
            $table->integer('bars_count')->default(0);
            $table->decimal('total_length', 12, 2)->default(0);
            $table->decimal('unit_weight', 10, 4)->default(0);
            $table->decimal('total_weight', 12, 2)->default(0);
            $table->string('shape_code', 50)->nullable();
            $table->boolean('is_manual_count')->default(false);
            $table->integer('sort_order')->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('rod_member_id');
            $table->index('diameter');
            $table->index('direction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rod_member_bars');
        Schema::dropIfExists('rod_members');
        Schema::dropIfExists('rod_calculations');
    }
};
