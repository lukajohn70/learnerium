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
     * Check if this lesson is unlocked for the given user based on module gating rules.
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

