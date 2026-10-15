<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp', 10)->nullable()->unique()->after('email');
            $table->string('empresa')->nullable()->after('whatsapp');
            $table->string('ciudad')->nullable()->after('empresa');
            $table->string('unidad_principal')->nullable()->after('ciudad');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['whatsapp']);
            $table->dropColumn(['whatsapp', 'empresa', 'ciudad', 'unidad_principal']);
        });
    }
};
