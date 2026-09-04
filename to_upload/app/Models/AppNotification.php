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
                $shouldSendEmail = true;

                // For non-admin users, check granular preferences if available
                if (!$user->isAdmin()) {
                    $prefs = NotificationPreference::forUser($userId);
                    if ($prefs) {
                        $shouldSendEmail = match($type) {
                            'enrollment' => $user->isInstructor() ? ($prefs->email_new_student ?? true) : ($prefs->email_enrollment ?? true),
                            'payment'    => $prefs->email_payment ?? true,
                            'payout'     => $prefs->email_payout ?? true,
                            'submission' => $prefs->email_course_updates ?? true,
                            'grading'    => $prefs->email_course_updates ?? true,
                            'marketing'  => $prefs->email_marketing ?? true,
                            default      => true,
                        };
                    }
                }

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
            \Illuminate\Support\Facades\Log::warning("Notification email delivery skipped for {$userId}: ({$e->getMessage()})");
        }

        return $notification;
    }

    /**
     * Dispatch in-app notification and email to ALL registered administrators.
     */
    public static function notifyAdmins(
        string $type,
        string $title,
        string $message,
        ?string $actionUrl = null,
        string $icon = 'fa-shield-halved',
        string $color = 'purple'
    ): void {
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                static::notify(
                    $admin->id,
                    $type,
                    $title,
                    $message,
                    $actionUrl,
                    $icon,
                    $color
                );
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Admin notification dispatch error: {$e->getMessage()}");
        }
    }
}

