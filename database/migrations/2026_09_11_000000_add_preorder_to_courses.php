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
        Schema::table('courses', function (Blueprint $table) {
            // Is this course currently in preorder mode?
            $table->boolean('is_preorder')->default(false)->after('published_at');

            // The special discounted price charged during the preorder window
            $table->decimal('preorder_price', 8, 2)->nullable()->after('is_preorder');

            // Optional deadline: when preorder ends and the course goes live
            $table->timestamp('preorder_ends_at')->nullable()->after('preorder_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['is_preorder', 'preorder_price', 'preorder_ends_at']);
        });
    }
};
