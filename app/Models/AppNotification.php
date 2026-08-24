<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppNotification extends Model
{
    protected $table = 'app_notifications';

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'action_url',
        'icon', 'color', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a notification for a user and send an email if opted in.
     */
    public static function notify(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $icon = 'fa-bell',
        string $color = 'blue'
    ): static {
        // 1. Create in-app notification
        $notification = static::create([
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'action_url' => $actionUrl,
            'icon'       => $icon,
            'color'      => $color,
            'is_read'    => false,
        ]);

        // 2. Dispatch Email
        try {
            $user = User::find($userId);
            if ($user && !empty($user->email)) {
                $prefs = NotificationPreference::forUser($userId);
                
                $shouldSendEmail = match($type) {
                    'enrollment' => $user->isInstructor() ? $prefs->email_new_student : $prefs->email_enrollment,
                    'payment'    => $prefs->email_payment,
                    'payout'     => $prefs->email_payout,
                    'submission' => $prefs->email_course_updates,
                    'grading'    => $prefs->email_course_updates,
                    default      => true,
                };

                if ($shouldSendEmail) {
                    \Illuminate\Support\Facades\Mail::send(
                        'emails.notification',
                        [
                            'title'       => $title,
                            'bodyMessage' => $message,
                            'actionUrl'   => $actionUrl,
                        ],
                        function ($mail) use ($user, $title) {
                            $mail->to($user->email, $user->name)
                                 ->subject("Learnerium: {$title}");
                        }
                    );
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Notification email delivery skipped ({$e->getMessage()})");
        }

        return $notification;
    }
}

