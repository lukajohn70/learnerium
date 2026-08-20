<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'profile_picture',
    ];

    public function avatarUrl(): string
    {
        if ($this->avatar) {
            return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('uploads/avatars/' . $this->avatar);
        }
        if ($this->profile_picture) {
            return str_starts_with($this->profile_picture, 'http') ? $this->profile_picture : asset('uploads/avatars/' . $this->profile_picture);
        }
        return 'https://placehold.co/120x120/f7de7a/1b2299?text=' . urlencode(substr($this->name, 0, 2));
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed', // REMOVE THIS LINE for Laravel 8. If you have this line, it's correct for Laravel 10+, but for Laravel 8, it might cause issues if not configured.
    ];

    // --- Define Relationships Below ---

    /**
     * Get the courses that the user (as an instructor) teaches.
     */
    public function coursesTaught(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get the enrollments associated with the user (as a student).
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'user_id');
    }

    /**
     * Get the courses that the user (as a student) is enrolled in.
     * This goes through the 'enrollments' table.
     */
    public function coursesEnrolled(): BelongsToMany
    {
        // MODIFIED: Explicitly add 'created_at' and 'updated_at' to withPivot
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                    ->withPivot('progress_percentage', 'completion_date', 'created_at', 'updated_at'); // Changed this line
                    // ->withTimestamps(); // REMOVE or comment out this line if you add them to withPivot
    }

    /**
     * Helper method to check if the user is an instructor.
     */
    public function isInstructor(): bool
    {
        if (session()->has('active_role')) {
            return session('active_role') === 'instructor';
        }
        return $this->role === 'instructor';
    }

    /**
     * Helper method to check if the user is a student.
     */
    public function isStudent(): bool
    {
        if (session()->has('active_role')) {
            return session('active_role') === 'student';
        }
        return $this->role === 'student' || $this->role === 'instructor';
    }

    /**
     * Check if user is eligible to switch modes.
     */
    public function canSwitchRole(): bool
    {
        return $this->role === 'instructor' || $this->role === 'admin';
    }

    /**
     * Check if user is enrolled in a specific course
     */
    public function enrolledIn($courseId): bool
    {
        return $this->enrollments()
            ->where('course_id', $courseId)
            ->exists();
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function peerReviews()
    {
        return $this->hasMany(PeerReview::class, 'reviewer_id');
    }

    public function instructorApplication()
    {
        return $this->hasOne(InstructorApplication::class);
    }
}