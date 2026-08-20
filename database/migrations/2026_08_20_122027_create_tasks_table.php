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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->string('title');
            $table->string('type'); // link, file, survey, quiz
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('peer_review_enabled')->default(false);
            $table->integer('required_reviews_count')->default(1);
            $table->text('config')->nullable(); // JSON configuration (survey questions, file formats, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
