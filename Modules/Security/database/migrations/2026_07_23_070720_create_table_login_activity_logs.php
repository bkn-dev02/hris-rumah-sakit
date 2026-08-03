<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->string('log_name', 50);
            $table->string('description', 255);
            $table->enum('event', ['created', 'updated', 'deleted']);

            $table->nullableMorphs('subject');
            $table->nullableMorphs('causer');

            $table->json('properties')->nullable();

            $table->timestamp('created_at')->useCurrent();

            $table->index('log_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
