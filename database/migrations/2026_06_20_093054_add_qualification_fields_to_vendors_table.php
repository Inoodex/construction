<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('qualification_status', 50)->default('unqualified')->after('status');
            $table->timestamp('qualified_at')->nullable()->after('qualification_status');
            $table->foreignId('qualified_by')->nullable()->constrained('users')->nullOnDelete()->after('qualified_at');
        });
    }

    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('qualified_by');
            $table->dropColumn(['qualification_status', 'qualified_at']);
        });
    }
};
