<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('work_date');
            $table->foreignId('shift_id')->constrained('shifts')->restrictOnDelete();

            $table->dateTime('check_in_at')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->string('check_in_photo')->nullable();
            $table->foreignId('check_in_location_id')->nullable()->constrained('attendance_locations')->restrictOnDelete();
            $table->unsignedInteger('check_in_distance_meters')->nullable();

            $table->dateTime('check_out_at')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->string('check_out_photo')->nullable();
            $table->foreignId('check_out_location_id')->nullable()->constrained('attendance_locations')->restrictOnDelete();
            $table->unsignedInteger('check_out_distance_meters')->nullable();

            $table->foreignId('attendance_status_id')->nullable()->constrained('attendance_statuses')->restrictOnDelete();
            $table->enum('determination_type', ['auto', 'manual'])->nullable();
            $table->enum('source', ['mobile', 'manual'])->default('mobile');
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'work_date']);
            $table->index('work_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
