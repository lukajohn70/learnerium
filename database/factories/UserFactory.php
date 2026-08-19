<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash; // Import Hash facade

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // default password 'password'
            'remember_token' => Str::random(10),
            'role' => 'student', // Default role is student
            'profile_picture' => null, // Placeholder for now
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): self // Changed 'static' to 'self'
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an instructor.
     */
    public function instructor(): self // Changed 'static' to 'self'
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'instructor',
            // Generate a simple placeholder based on initials
            'profile_picture' => 'https://placehold.co/40x40/secondary-jlm/ffffff?text=' . strtoupper(substr($attributes['name'], 0, 1) . substr(explode(' ', $attributes['name'])[1] ?? '', 0, 1)),
        ]);
    }
}
