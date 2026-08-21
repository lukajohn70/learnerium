<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'instructor_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'price',
        'level',
        'category',
        'duration_minutes',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    // --- Define Relationships Below ---

    /**
     * Get the instructor that owns the Course.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the enrollments for the Course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    /**
     * Get the modules for the Course.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'course_id')->orderBy('order', 'asc');
    }

    /**
     * Get the lessons for the Course.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'course_id')->orderBy('order');
    }

    /**
     * Get the students that are enrolled in the Course.
     * This goes through the 'enrollments' table.
     */
    public function students(): BelongsToMany
    {
        // MODIFIED: Explicitly add 'created_at' and 'updated_at' to withPivot
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->withPivot('progress_percentage', 'completion_date', 'created_at', 'updated_at'); // Changed this line
                    // ->withTimestamps(); // REMOVE or comment out this line if you add them to withPivot
    }

    /**
     * Get the coupons associated with this Course.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the dynamic thumbnail URL for this Course.
     */
    public function thumbnailUrl(): string
    {
        if ($this->thumbnail) {
            if (preg_match('/uploads\/thumbnails\/(.+)$/', $this->thumbnail, $matches)) {
                return asset('uploads/thumbnails/' . $matches[1]);
            }
            if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                return $this->thumbnail;
            }
            return asset('storage/' . $this->thumbnail);
        }
        return 'https://placehold.co/600x400/1b2299/f7de7a?text=' . urlencode($this->title);
    }
}