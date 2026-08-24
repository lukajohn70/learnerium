<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'description',
        'order',
        'video_url',
        'content',
        'drip_date',
        'drip_days',
    ];

    protected $casts = [
        'drip_date' => 'datetime',
        'drip_days' => 'integer',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Check if this lesson has a direct drip lock.
     */
    public function isDripLockedFor($user): bool
    {
        if (!$user) return false;
        if (method_exists($user, 'isInstructor') && $user->isInstructor()) return false;
        if ($user->role === 'admin') return false;

        // Lesson specific calendar date
        if ($this->drip_date && $this->drip_date->isFuture()) {
            return true;
        }

        // Lesson specific days after enrollment
        if ($this->drip_days && $this->drip_days > 0) {
            $enrollment = \Illuminate\Support\Facades\DB::table('enrollments')
                ->where('user_id', $user->id)
                ->where('course_id', $this->course_id)
                ->first();
            if ($enrollment && $enrollment->created_at) {
                $unlockAt = \Carbon\Carbon::parse($enrollment->created_at)->addDays($this->drip_days);
                if ($unlockAt->isFuture()) {
                    return true;
                }
            }
        }

        // Inherit from parent module if parent module has drip schedule
        if ($this->module && $this->module->isDripLockedFor($user)) {
            return true;
        }

        return false;
    }

    /**
     * Get human-readable drip message.
     */
    public function dripMessageFor($user): ?string
    {
        if (!$this->isDripLockedFor($user)) return null;

        if ($this->drip_date && $this->drip_date->isFuture()) {
            return 'Unlocks on ' . $this->drip_date->format('d M Y, h:i A');
        }

        if ($this->drip_days && $this->drip_days > 0) {
            $enrollment = \Illuminate\Support\Facades\DB::table('enrollments')
                ->where('user_id', $user->id)
                ->where('course_id', $this->course_id)
                ->first();
            if ($enrollment && $enrollment->created_at) {
                $unlockAt = \Carbon\Carbon::parse($enrollment->created_at)->addDays($this->drip_days);
                return 'Unlocks ' . $unlockAt->diffForHumans();
            }
            return "Unlocks {$this->drip_days} days after enrollment";
        }

        if ($this->module && $this->module->isDripLockedFor($user)) {
            return $this->module->dripMessageFor($user);
        }

        return null;
    }

    /**
     * Check if this lesson is unlocked for the given user based on module gating and drip rules.
     */
    public function isUnlockedFor($user): bool
    {
        if (!$user) {
            return false;
        }

        // Instructors / Admins bypass completion locks
        if (method_exists($user, 'isInstructor') && $user->isInstructor()) {
            return true;
        }
        if ($user->role === 'admin') {
            return true;
        }

        // Check drip schedule
        if ($this->isDripLockedFor($user)) {
            return false;
        }

        if ($this->module) {
            return $this->module->isUnlockedFor($user);
        }

        return true;
    }


    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function userProgress(User $user)
    {
        return $this->progress()->where('user_id', $user->id)->first();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function discussions()
    {
        return $this->hasMany(LessonDiscussion::class, 'lesson_id');
    }
}

