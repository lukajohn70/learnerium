<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'instructor@learnerium.test'],
            [
                'name' => 'Instructor User',
                'password' => Hash::make('Password123!'),
                'role' => 'instructor',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@learnerium.test'],
            [
                'name' => 'Student User',
                'password' => Hash::make('Password123!'),
                'role' => 'student',
            ]
        );

        // Optional: seed additional users
        User::factory(5)->create();
        User::factory(2)->instructor()->create();
    }
}
