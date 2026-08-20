<?php

namespace Database\Seeders;

  // use Illuminate\Database\Console\Seeds\WithoutModelEvents;
    use Illuminate\Database\Seeder;
    use Database\Seeders\UserSeeder;
    use Database\Seeders\CourseSeeder; // Add this line
    use Database\Seeders\EnrollmentSeeder; // Add this line

    class DatabaseSeeder extends Seeder
    {
        /**
         * Seed the application's database.
         */
        public function run(): void
        {
            $this->call([
                UserSeeder::class,
                CourseSeeder::class,    // Run after UserSeeder
                EnrollmentSeeder::class, // Run after UserSeeder and CourseSeeder
            ]);
        }
}
