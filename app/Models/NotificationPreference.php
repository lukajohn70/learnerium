<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    protected $table = 'notification_preferences';

    protected $fillable = [
        'user_id',
        'email_enrollment', 'email_payment', 'email_course_updates',
        'email_new_student', 'email_payout', 'email_announcements', 'email_marketing',
        'inapp_enrollment', 'inapp_payment', 'inapp_course_updates', 'inapp_announcements',
    ];

    protected $casts = [
        'email_enrollment'    => 'boolean',
        'email_payment'       => 'boolean',
        'email_course_updates'=> 'boolean',
        'email_new_student'   => 'boolean',
        'email_payout'        => 'boolean',
        'email_announcements' => 'boolean',
        'email_marketing'     => 'boolean',
        'inapp_enrollment'    => 'boolean',
        'inapp_payment'       => 'boolean',
        'inapp_course_updates'=> 'boolean',
        'inapp_announcements' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create preferences for a user with sensible defaults.
     */
    public static function forUser(int $userId): static
    {
        return static::firstOrCreate(['user_id' => $userId]);
    }
}
