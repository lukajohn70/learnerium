<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User; // Ensure this line is present and correct
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(rand(3, 6)); // Generate a unique sentence for title
        $instructor = User::where('role', 'instructor')->inRandomOrder()->first(); // Get a random instructor

        // Fallback if no instructor exists (shouldn't happen if UserSeeder runs first)
        if (!$instructor) {
            $instructor = User::factory()->instructor()->create();
        }

        return [
            'instructor_id' => $instructor->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(rand(5, 10)),
            'thumbnail' => 'https://placehold.co/600x400/primary-jlm/ffffff?text=' . urlencode(substr($title, 0, 15)), // Dynamic placeholder image
            'price' => $this->faker->randomFloat(2, 10, 500),
            'level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'duration_minutes' => $this->faker->numberBetween(60, 1200), // 1 hour to 20 hours
            'published_at' => $this->faker->boolean(80) ? $this->faker->dateTimeBetween('-1 year', 'now') : null, // 80% chance of being published
        ];
    }
}
