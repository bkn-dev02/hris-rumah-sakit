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
        Schema::create('sp_letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sp_candidate_id')->constrained('sp_candidates')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('file_path');
            $table->unsignedInteger('sp_number');
            $table->foreignId('issued_by')->constrained('employees')->cascadeOnDelete();
            $table->dateTime('issued_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp_letters');
    }
};
