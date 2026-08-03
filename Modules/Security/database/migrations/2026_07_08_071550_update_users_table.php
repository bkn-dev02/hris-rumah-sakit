<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'email_verified_at',
            ]);
            $table->string('slug', 50)
                ->unique()
                ->after('id');
            $table->foreignId('employee_id')
                ->nullable()
                ->after('slug')
                ->nullOnDelete();
            $table->string('username', 50)
                ->unique()
                ->after('employee_id');
            $table->string('email', 150)
                ->change();
            $table->boolean('is_active')
                ->default(true)
                ->after('password');
            $table->timestamp('last_login_at')
                ->nullable()
                ->after('is_active');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropColumn([
                'slug',
                'employee_id',
                'username',
                'is_active',
                'last_login_at',
            ]);

            $table->string('name')->after('id');

            $table->timestamp('email_verified_at')
                ->nullable()
                ->after('email');
        });
    }
};
