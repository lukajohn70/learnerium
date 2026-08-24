<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string|integer|decimal|boolean
            $table->string('label')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('platform_settings')->insert([
            ['key' => 'instructor_revenue_share', 'value' => '70', 'type' => 'decimal', 'label' => 'Instructor Revenue Share (%)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'platform_revenue_share',   'value' => '30', 'type' => 'decimal', 'label' => 'Platform Revenue Share (%)',   'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
