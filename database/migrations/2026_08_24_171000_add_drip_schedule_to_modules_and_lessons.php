<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            if (!Schema::hasColumn('modules', 'drip_date')) {
                $table->dateTime('drip_date')->nullable();
            }
            if (!Schema::hasColumn('modules', 'drip_days')) {
                $table->integer('drip_days')->nullable();
            }
        });

        Schema::table('lessons', function (Blueprint $table) {
            if (!Schema::hasColumn('lessons', 'drip_date')) {
                $table->dateTime('drip_date')->nullable();
            }
            if (!Schema::hasColumn('lessons', 'drip_days')) {
                $table->integer('drip_days')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn(['drip_date', 'drip_days']);
        });
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['drip_date', 'drip_days']);
        });
    }
};
