<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'course_id',
        'active',
        'max_uses',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the course associated with this coupon.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if coupon is valid for a given course.
     */
    public function isValidFor(Course $course): bool
    {
        if (!$this->active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        // Must be a global coupon (course_id is null) or specific to this course
        if ($this->course_id !== null && (int)$this->course_id !== (int)$course->id) {
            return false;
        }

        return true;
    }

    /**
     * Increment coupon usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Calculate discount amount.
     */
    public function discountAmount($originalPrice): float
    {
        $price = (float) $originalPrice;
        $value = (float) $this->discount_value;

        if ($this->discount_type === 'percentage') {
            return round(($price * $value) / 100, 2);
        }

        // Fixed discount
        return round(min($value, $price), 2);
    }
}
