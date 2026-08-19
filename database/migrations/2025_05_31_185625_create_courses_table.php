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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade'); // Foreign key to users table
            $table->string('title')->unique(); // Added unique constraint
            $table->string('slug')->unique(); // Added slug column with unique constraint
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('level', 50)->default('Beginner'); // Changed from 'difficulty' to 'level'
            $table->unsignedInteger('duration_minutes'); // Added duration_minutes
            $table->timestamp('published_at')->nullable();
            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
