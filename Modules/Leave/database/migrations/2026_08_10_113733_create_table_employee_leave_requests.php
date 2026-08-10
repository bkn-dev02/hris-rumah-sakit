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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')->constrained()->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('total_days');
            $table->text('reason');
            $table->string('attachment')->nullable();

            $table->enum('status', ['pending_supervisor', 'pending_hr', 'approved', 'rejected', 'cancelled'])->default('pending_supervisor');

            $table->foreignId('supervisor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->dateTime('supervisor_decided_at')->nullable();
            $table->text('supervisor_note')->nullable();

            $table->foreignId('hr_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->dateTime('hr_decided_at')->nullable();
            $table->text('hr_note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
