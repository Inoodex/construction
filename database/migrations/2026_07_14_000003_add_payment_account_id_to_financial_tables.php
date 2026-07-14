<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('payment_account_id')->nullable()->after('reference')->constrained('payment_accounts')->nullOnDelete();
        });

        Schema::table('bill_payments', function (Blueprint $table) {
            $table->foreignId('payment_account_id')->nullable()->after('reference')->constrained('payment_accounts')->nullOnDelete();
        });

        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->foreignId('payment_account_id')->nullable()->after('reference')->constrained('payment_accounts')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('payment_account_id')->nullable()->after('reference_number')->constrained('payment_accounts')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['payment_account_id']);
            $table->dropColumn('payment_account_id');
        });

        Schema::table('bill_payments', function (Blueprint $table) {
            $table->dropForeign(['payment_account_id']);
            $table->dropColumn('payment_account_id');
        });

        Schema::table('receivable_payments', function (Blueprint $table) {
            $table->dropForeign(['payment_account_id']);
            $table->dropColumn('payment_account_id');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['payment_account_id']);
            $table->dropColumn('payment_account_id');
        });
    }
};
