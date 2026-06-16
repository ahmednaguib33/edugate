<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default(UserRole::Student->value)->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('nationality')->nullable()->after('phone');
            $table->string('country_of_residence')->nullable()->after('nationality');
            $table->boolean('is_active')->default(true)->after('country_of_residence');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'nationality',
                'country_of_residence',
                'is_active',
            ]);
        });
    }
};
