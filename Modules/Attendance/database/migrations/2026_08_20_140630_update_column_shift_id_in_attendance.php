<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_id')->nullable()->change();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('shift_id')->references('id')->on('shifts')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['shift_id']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_id')->nullable(false)->change();
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('shift_id')->references('id')->on('shifts')->restrictOnDelete();
        });
    }
};
