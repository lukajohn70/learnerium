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
        'requirements',
        'what_you_will_learn',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'published_at'        => 'datetime',
        'requirements'        => 'array',
        'what_you_will_learn' => 'array',
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
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'user_id')
                    ->wherePivot('payment_status', 'paid')
                    ->withPivot('payment_status', 'progress_percentage', 'completion_date', 'created_at', 'updated_at');
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
     * Prevents 404 broken image errors by checking disk existence before serving URL.
     */
    public function thumbnailUrl(): string
    {
        if (!empty($this->thumbnail)) {
            $thumb = trim(str_replace('primary-jlm', '1b2299', $this->thumbnail));

            if (str_starts_with($thumb, 'http://') || str_starts_with($thumb, 'https://') || str_starts_with($thumb, 'data:image')) {
                if (!str_contains($thumb, 'primary-jlm')) {
                    return $thumb;
                }
            }

            $filename = basename($thumb);
            if (file_exists(public_path('uploads/thumbnails/' . $filename)) || file_exists(base_path('public/uploads/thumbnails/' . $filename))) {
                return asset('uploads/thumbnails/' . $filename);
            }

            $clean = ltrim(preg_replace('#^public/#', '', $thumb), '/');
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
        }

        // Guaranteed instant Base64 SVG Data URI fallback styled with JLM colors
        $title = htmlspecialchars(substr($this->title ?? 'Learnerium Course', 0, 32), ENT_QUOTES, 'UTF-8');
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400"><rect width="600" height="400" fill="#1b2299"/><circle cx="500" cy="80" r="120" fill="#e4306d" opacity="0.4"/><circle cx="100" cy="320" r="140" fill="#f7de7a" opacity="0.3"/><text x="50%" y="45%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-family="Arial, Helvetica, sans-serif" font-size="26" font-weight="bold">'.$title.'</text><text x="50%" y="62%" dominant-baseline="middle" text-anchor="middle" fill="#f7de7a" font-family="Arial, Helvetica, sans-serif" font-size="16" font-weight="bold">Learnerium Course</text></svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}