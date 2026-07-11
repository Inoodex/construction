<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('drawing_revisions')) {
            Schema::create('drawing_revisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('drawing_id')->constrained()->cascadeOnDelete();
                $table->string('revision');
                $table->date('revision_date');
                $table->text('description')->nullable();
                $table->string('file_path')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->boolean('is_current')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('drawing_transmittals')) {
            Schema::create('drawing_transmittals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->string('transmittal_number')->unique();
                $table->string('to_party');
                $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->date('sent_date');
                $table->enum('purpose', ['for_approval', 'for_information', 'for_construction', 'as_built'])->default('for_information');
                $table->enum('status', ['draft', 'sent', 'acknowledged'])->default('draft');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('drawing_transmittal_items')) {
            Schema::create('drawing_transmittal_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('drawing_transmittal_id')->constrained()->cascadeOnDelete();
                $table->foreignId('drawing_id')->constrained()->cascadeOnDelete();
                $table->foreignId('drawing_revision_id')->nullable()->constrained('drawing_revisions')->nullOnDelete();
                $table->unsignedInteger('copies')->default(1);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('rfis')) {
            Schema::create('rfis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->string('rfi_number')->unique();
                $table->string('subject');
                $table->text('question');
                $table->foreignId('drawing_id')->nullable()->constrained()->nullOnDelete();
                $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
                $table->enum('status', ['open', 'answered', 'closed'])->default('open');
                $table->foreignId('raised_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->date('due_date')->nullable();
                $table->text('answer')->nullable();
                $table->foreignId('answered_by')->nullable()->constrained('users')->nullOnDelete();
                $table->date('answered_date')->nullable();
                $table->string('attachment_path')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('change_orders')) {
            Schema::create('change_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained()->cascadeOnDelete();
                $table->string('change_order_number')->unique();
                $table->string('title');
                $table->text('description');
                $table->enum('type', ['variation', 'change_order', 'extension'])->default('variation');
                $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'implemented'])->default('draft');
                $table->decimal('cost_impact', 15, 2)->nullable();
                $table->integer('time_impact_days')->nullable();
                $table->foreignId('rfi_id')->nullable()->constrained('rfis')->nullOnDelete();
                $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->date('approved_date')->nullable();
                $table->string('attachment_path')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('change_orders');
        Schema::dropIfExists('rfis');
        Schema::dropIfExists('drawing_transmittal_items');
        Schema::dropIfExists('drawing_transmittals');
        Schema::dropIfExists('drawing_revisions');
    }
};
