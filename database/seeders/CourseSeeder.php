<?php

namespace Database\Seeders;

  use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use App\Models\Course; // Import the Course model

    class CourseSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // Create 20 random courses
            Course::factory(20)->create();
        }
    }