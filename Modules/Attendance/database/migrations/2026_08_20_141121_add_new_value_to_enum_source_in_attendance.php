<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE attendances MODIFY source ENUM('mobile', 'manual', 'system') NOT NULL DEFAULT 'mobile'");
    }

    public function down(): void
    {
        DB::table('attendances')->where('source', 'system')->update(['source' => 'manual']);
        DB::statement("ALTER TABLE attendances MODIFY source ENUM('mobile', 'manual') NOT NULL DEFAULT 'mobile'");
    }
};
