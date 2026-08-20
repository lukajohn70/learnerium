<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }

    /**
     * Check if a student has completed all lessons in this module.
     */
    public function isCompletedBy($user): bool
    {
        if (!$user) {
            return false;
        }

        $lessonIds = $this->lessons()->pluck('id')->toArray();
        if (empty($lessonIds)) {
            return true;
        }

        $completedCount = DB::table('lesson_progress')
            ->where('user_id', $user->id)
            ->whereIn('lesson_id', $lessonIds)
            ->where('completed', 1)
            ->count();

        return $completedCount >= count($lessonIds);
    }

    /**
     * Check if this module is unlocked for the user.
     * The first module is always unlocked.
     * Subsequent modules require the previous module to be 100% completed.
     */
    public function isUnlockedFor($user): bool
    {
        if (!$user) {
            return false;
        }

        // Instructors and Admins bypass completion locks
        if (method_exists($user, 'isInstructor') && $user->isInstructor()) {
            return true;
        }

        // Get the previous module in order
        $previousModule = Module::where('course_id', $this->course_id)
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();

        if (!$previousModule) {
            // This is the first module in order
            return true;
        }

        return $previousModule->isCompletedBy($user);
    }
}
