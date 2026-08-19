<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Enrollment; // Import the Enrollment model
use App\Models\User; // Ensure this line is present and correct
use App\Models\Course; // Ensure this line is present and correct

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students and courses
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        // Ensure we have students and courses to create enrollments
        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found to create enrollments. Please run UserSeeder and CourseSeeder first.');
            return;
        }

        // For each student, enroll them in a random number of courses
        foreach ($students as $student) {
            // Enroll in 1 to 5 random courses
            $coursesToEnroll = $courses->random(rand(1, min(5, $courses->count())));

            foreach ($coursesToEnroll as $course) {
                // Check if student is already enrolled to avoid unique constraint violation
                if (!$student->coursesEnrolled()->where('course_id', $course->id)->exists()) {
                    Enrollment::factory()->create([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'progress_percentage' => rand(0, 100), // Random progress
                        'completion_date' => (rand(0, 1) === 1 && rand(0, 100) > 70) ? now() : null, // Some might be completed
                    ]);
                }
            }
        }
    }
}
