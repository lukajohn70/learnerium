<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnrollmentsTableForPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('payment_status', 50)->nullable()->default('pending');
            $table->decimal('amount_paid', 8, 2)->nullable();
            $table->string('coupon_code', 100)->nullable();
            $table->string('payment_reference', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'amount_paid', 'coupon_code', 'payment_reference']);
        });
    }
}
