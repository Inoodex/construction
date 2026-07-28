<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to reliably alter the column from ENUM to VARCHAR(50) across MySQL versions
        DB::statement("ALTER TABLE project_resources MODIFY resource_type VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE project_resources MODIFY resource_type ENUM('labor', 'equipment', 'material') NOT NULL");
    }
};
