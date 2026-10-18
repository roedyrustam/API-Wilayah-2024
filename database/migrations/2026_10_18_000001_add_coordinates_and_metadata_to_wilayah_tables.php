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
        Schema::table('provinsis', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('name');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('timezone', 20)->nullable()->after('longitude');
        });

        Schema::table('kabupatens', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('full_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        Schema::table('kecamatans', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('full_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        Schema::table('kelurahans', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('pos_code');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinsis', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'timezone']);
        });

        Schema::table('kabupatens', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('kecamatans', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('kelurahans', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
