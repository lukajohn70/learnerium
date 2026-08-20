<?php

namespace Database\Factories;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Enrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $student = User::where('role', 'student')->inRandomOrder()->first(); // Get a random student
        $course = Course::inRandomOrder()->first(); // Get a random course

        // Fallback if no student or course exists
        if (!$student) {
            $student = User::factory()->create(['role' => 'student']);
        }
        if (!$course) {
            $course = Course::factory()->create();
        }

        $progress = $this->faker->numberBetween(0, 100);
        $completionDate = ($progress === 100) ? $this->faker->dateTimeBetween('-6 months', 'now') : null;

        return [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'progress_percentage' => $progress,
            'completion_date' => $completionDate,
        ];
    }

    /**
     * Indicate that the enrollment is complete.
     */
    public function completed(): self // Changed 'static' to 'self' for PHP 7.4 / Laravel 8 compatibility
    {
        return $this->state(fn (array $attributes) => [
            'progress_percentage' => 100,
            'completion_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }
}
