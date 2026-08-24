<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('submissions', 'attempts_count')) {
                $table->unsignedInteger('attempts_count')->default(1)->after('grade');
            }
            if (!Schema::hasColumn('submissions', 'max_attempts')) {
                $table->unsignedInteger('max_attempts')->default(3)->after('attempts_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['attempts_count', 'max_attempts']);
        });
    }
};
