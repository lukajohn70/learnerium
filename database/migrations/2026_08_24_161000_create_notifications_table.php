<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');           // enrollment, payment, course_update, system, payout
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->string('icon')->default('fa-bell');
            $table->string('color')->default('blue'); // blue, green, red, amber, purple
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_read']);
        });

        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            // Email preferences
            $table->boolean('email_enrollment')->default(true);
            $table->boolean('email_payment')->default(true);
            $table->boolean('email_course_updates')->default(true);
            $table->boolean('email_new_student')->default(true);      // instructor: new student enrolled
            $table->boolean('email_payout')->default(true);           // instructor: payout processed
            $table->boolean('email_announcements')->default(true);
            $table->boolean('email_marketing')->default(false);
            // In-app preferences
            $table->boolean('inapp_enrollment')->default(true);
            $table->boolean('inapp_payment')->default(true);
            $table->boolean('inapp_course_updates')->default(true);
            $table->boolean('inapp_announcements')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('app_notifications');
    }
};
