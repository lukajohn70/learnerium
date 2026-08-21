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
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'max_uses')) {
                $table->integer('max_uses')->nullable()->after('active');
            }
            if (!Schema::hasColumn('coupons', 'used_count')) {
                $table->integer('used_count')->default(0)->after('max_uses');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['max_uses', 'used_count']);
        });
    }
};
