<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sp_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->enum('status', [
                'candidate',
                'pending_decision',
                'cancelled_manual',
                'cancelled_late_checkin_decision',
                'resolved_issued',
            ])->default('candidate');
            $table->dateTime('detected_at');
            $table->dateTime('late_checkin_at')->nullable();
            $table->foreignId('decision_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->dateTime('decision_at')->nullable();
            $table->text('decision_note')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date', 'shift_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_candidates');
    }
};
