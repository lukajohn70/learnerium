<?php

namespace App\Models;

use App\Models\ModuleMaterial;
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
        'drip_date',
        'drip_days',
    ];

    protected $casts = [
        'drip_date' => 'datetime',
        'drip_days' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ModuleMaterial::class);
    }

    /**
     * Check if module has a drip schedule locking it for the user.
     */
    public function isDripLockedFor($user): bool
    {
        if (!$user) return false;
        if (method_exists($user, 'isInstructor') && $user->isInstructor()) return false;
        if ($user->role === 'admin') return false;

        // Specific Calendar Date Drip
        if ($this->drip_date && $this->drip_date->isFuture()) {
            return true;
        }

        // Days-after-enrollment Drip
        if ($this->drip_days && $this->drip_days > 0) {
            $enrollment = DB::table('enrollments')
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

        return false;
    }

    /**
     * Get human-readable drip lock message.
     */
    public function dripMessageFor($user): ?string
    {
        if (!$this->isDripLockedFor($user)) return null;

        if ($this->drip_date && $this->drip_date->isFuture()) {
            return 'Unlocks on ' . $this->drip_date->format('d M Y, h:i A');
        }

        if ($this->drip_days && $this->drip_days > 0) {
            $enrollment = DB::table('enrollments')
                ->where('user_id', $user->id)
                ->where('course_id', $this->course_id)
                ->first();
            if ($enrollment && $enrollment->created_at) {
                $unlockAt = \Carbon\Carbon::parse($enrollment->created_at)->addDays($this->drip_days);
                return 'Unlocks ' . $unlockAt->diffForHumans();
            }
            return "Unlocks {$this->drip_days} days after enrollment";
        }

        return null;
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
     * 1. Must satisfy drip schedule (date/time or days after enrollment).
     * 2. Must satisfy sequential prerequisite (previous module 100% completed).
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
        if ($user->role === 'admin') {
            return true;
        }

        // Check Drip Schedule First
        if ($this->isDripLockedFor($user)) {
            return false;
        }

        // Get the previous module in order
        $previousModule = Module::where('course_id', $this->course_id)
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();

        if (!$previousModule) {
            // First module in order
            return true;
        }

        return $previousModule->isCompletedBy($user);
    }
}

