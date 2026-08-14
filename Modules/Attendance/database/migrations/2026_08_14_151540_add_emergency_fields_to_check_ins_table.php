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
        Schema::table('check_ins', function (Blueprint $table) {
            $table->string('type', 20)->default('normal')->after('employee_id');
            $table->foreignId('location_id')->nullable()->change();
            $table->unsignedInteger('distance_meters')->nullable()->change();
            $table->string('emergency_photo')->nullable()->after('photo');
            $table->text('emergency_reason')->nullable()->after('emergency_photo');
            $table->enum('emergency_status', ['pending', 'approved', 'rejected'])->nullable()->after('emergency_reason');
            $table->foreignId('emergency_decided_by')->nullable()->after('emergency_status')->constrained('employees')->nullOnDelete();
            $table->dateTime('emergency_decided_at')->nullable()->after('emergency_decided_by');
            $table->text('emergency_decision_note')->nullable()->after('emergency_decided_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropForeign(['emergency_decided_by']);
            $table->dropColumn([
                'type',
                'emergency_photo',
                'emergency_reason',
                'emergency_status',
                'emergency_decided_by',
                'emergency_decided_at',
                'emergency_decision_note',
            ]);

            $table->foreignId('location_id')->nullable(false)->change();
            $table->unsignedInteger('distance_meters')->nullable(false)->change();
        });
    }
};
