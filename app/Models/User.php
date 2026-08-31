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
        'bank_name',
        'bank_code',
        'account_number',
        'account_name',
        'payout_requested_at',
    ];

    public function avatarUrl(): string
    {
        $defaultSvg = function() {
            $initials = strtoupper(substr($this->name ?? 'U', 0, 2));
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="#1b2299"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" fill="#f7de7a" font-family="Arial, Helvetica, sans-serif" font-size="46" font-weight="bold">'.$initials.'</text></svg>';
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };

        $raw = $this->avatar ?: $this->profile_picture;
        if (!empty($raw)) {
            $raw = trim($raw);
            if (str_starts_with($raw, 'data:image/')) {
                return $raw;
            }
            // If external placeholder service, use instant local SVG instead of waiting for slow external network
            if (str_contains($raw, 'ui-avatars.com') || str_contains($raw, 'placehold.co') || str_contains($raw, 'gravatar.com')) {
                return $defaultSvg();
            }
            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://')) {
                return $raw;
            }
            $filename = basename($raw);
            return asset('uploads/avatars/' . $filename);
        }

        return $defaultSvg();
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
        // Only return PAID enrollments — pending (pre-payment) should not show as enrolled
        return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id')
                    ->wherePivot('payment_status', 'paid')
                    ->withPivot('progress_percentage', 'completion_date', 'created_at', 'updated_at');
    }

    /**
     * Enrollments for courses this user teaches (as instructor).
     * Used for earnings/payout calculations.
     */
    public function instructorEnrollments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Enrollment::class,
            Course::class,
            'instructor_id', // FK on courses table
            'course_id',     // FK on enrollments table
            'id',            // local key on users
            'id'             // local key on courses
        );
    }

    /**
     * Helper method to check if the user is an instructor.
     */
    public function isInstructor(): bool
    {
        if (session()->has('active_role')) {
            return session('active_role') === 'instructor';
        }
        return $this->role === 'instructor' || $this->role === 'admin';
    }

    /**
     * Helper method to check if the user is a student.
     */
    public function isStudent(): bool
    {
        if (session()->has('active_role')) {
            return session('active_role') === 'student';
        }
        return $this->role === 'student' || $this->role === 'instructor' || $this->role === 'admin';
    }

    /**
     * Helper method to check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is eligible to switch modes.
     */
    public function canSwitchRole(): bool
    {
        return $this->role === 'instructor' || $this->role === 'admin';
    }

    /**
     * Check if user is enrolled in a specific course AND has paid (for paid courses).
     */
    public function enrolledIn($courseId): bool
    {
        $enrollment = $this->enrollments()
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return false;
        }

        // For any enrollment, check if paid (or 'paid' status means free or paid)
        return $enrollment->payment_status === 'paid';
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

    /**
     * Wishlist relationship.
     */
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'wishlists', 'user_id', 'course_id')->withTimestamps();
    }

    /**
     * Cart relationship.
     */
    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'cart_items', 'user_id', 'course_id')->withTimestamps();
    }

    public function inWishlist($courseId): bool
    {
        return $this->wishlist()->where('course_id', $courseId)->exists();
    }

    public function inCart($courseId): bool
    {
        return $this->cart()->where('course_id', $courseId)->exists();
    }
}