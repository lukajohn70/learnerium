<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class Enrollment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'enrollments'; // Explicitly define table name if it's not plural of model name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'progress_percentage',
        'completion_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completion_date' => 'datetime',
    ];

    // --- Define Relationships Below ---

    /**
     * Get the user (student) that owns the enrollment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the course that the enrollment belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    /**
     * Calculate and update progress percentage for this enrollment.
     */
    public function updateProgress()
    {
        $course = $this->course;
        $userId = $this->user_id;
        $totalLessons = $course->lessons()->count();
        if ($totalLessons === 0) {
            $this->progress_percentage = 0;
            $this->save();
            return;
        }
        $completedLessons = \App\Models\LessonProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $course->lessons()->pluck('id'))
            ->where('completed', true)
            ->count();
        $progress = (int) round(($completedLessons / $totalLessons) * 100);
        $this->progress_percentage = $progress;
        $this->save();
    }
}
