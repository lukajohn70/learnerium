<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->decimal('instructor_share', 12, 2)->default(0)->after('amount_paid');
            $table->decimal('platform_share', 12, 2)->default(0)->after('instructor_share');
            $table->string('payout_status')->default('pending')->after('platform_share'); // pending|paid
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['instructor_share', 'platform_share', 'payout_status']);
        });
    }
};
