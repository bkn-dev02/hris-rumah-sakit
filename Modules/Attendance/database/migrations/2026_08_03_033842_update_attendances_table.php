<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['check_in_location_id']);
            $table->dropForeign(['check_out_location_id']);

            $table->dropColumn([
                'check_in_at',
                'check_in_latitude',
                'check_in_longitude',
                'check_in_photo',
                'check_in_location_id',
                'check_in_distance_meters',
                'check_out_at',
                'check_out_latitude',
                'check_out_longitude',
                'check_out_photo',
                'check_out_location_id',
                'check_out_distance_meters',
            ]);

            $table->foreignId('check_in_id')->nullable()->after('shift_id')->constrained('check_ins')->nullOnDelete();
            $table->foreignId('check_out_id')->nullable()->after('check_in_id')->constrained('check_outs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['check_in_id']);
            $table->dropForeign(['check_out_id']);
            $table->dropColumn([
                'check_in_id',
                'check_out_id',
            ]);

            $table->timestamp('check_in_at')->nullable();
            $table->decimal('check_in_latitude', 10, 7)->nullable();
            $table->decimal('check_in_longitude', 10, 7)->nullable();
            $table->string('check_in_photo')->nullable();
            $table->foreignId('check_in_location_id')->nullable()->constrained('attendance_locations')->restrictOnDelete();
            $table->unsignedInteger('check_in_distance_meters')->nullable();

            $table->timestamp('check_out_at')->nullable();
            $table->decimal('check_out_latitude', 10, 7)->nullable();
            $table->decimal('check_out_longitude', 10, 7)->nullable();
            $table->string('check_out_photo')->nullable();
            $table->foreignId('check_out_location_id')->nullable()->constrained('attendance_locations')->restrictOnDelete();
            $table->unsignedInteger('check_out_distance_meters')->nullable();
        });
    }
};
