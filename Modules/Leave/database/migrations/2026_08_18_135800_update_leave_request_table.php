<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('leave_requests')
            ->whereIn('status', ['pending_supervisor', 'pending_hr'])
            ->update(['status' => 'pending']);

        DB::statement("ALTER TABLE leave_requests MODIFY status ENUM('pending', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE leave_requests MODIFY status ENUM('pending_supervisor', 'pending_hr', 'approved', 'rejected', 'cancelled') NOT NULL DEFAULT 'pending_supervisor'");

        DB::table('leave_requests')
            ->where('status', 'pending')
            ->update(['status' => 'pending_supervisor']);
    }
};
