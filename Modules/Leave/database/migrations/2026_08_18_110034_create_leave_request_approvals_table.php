<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_request_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approver_employee_id')->constrained('employees')->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence');
            $table->enum('type', ['supervisor', 'hrd', 'director'])->default('supervisor');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('decided_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['leave_request_id', 'sequence']);
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supervisor_id');
            $table->dropConstrainedForeignId('hr_id');

            $table->dropColumn(['supervisor_decided_at', 'supervisor_note', 'hr_decided_at', 'hr_note']);
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('supervisor_decided_at')->nullable();
            $table->text('supervisor_note')->nullable();
            $table->foreignId('hr_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamp('hr_decided_at')->nullable();
            $table->text('hr_note')->nullable();
        });

        Schema::dropIfExists('leave_request_approvals');
    }
};
