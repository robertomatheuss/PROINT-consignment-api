<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('perfil', ['ADMIN', 'VENDEDOR'])
                  ->default('VENDEDOR')
                  ->after('password');

            $table->boolean('active')
                  ->default(true)
                  ->after('perfil');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['perfil', 'active']);
        });
    }
};
