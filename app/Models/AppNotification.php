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
                    // Thoroughly strip emojis and exclamation marks from subject to bypass SpamAssassin and Gmail filters
                    $cleanTitle = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1FA70}-\x{1FAFF}\x{10000}-\x{10FFFF}]/u', '', $title);
                    $cleanTitle = str_replace(['!', '  '], ['', ' '], $cleanTitle);
                    $cleanTitle = trim($cleanTitle) ?: 'Notification';

                    \Illuminate\Support\Facades\Mail::send(
                        ['html' => 'emails.notification', 'text' => 'emails.notification_plain'],
                        [
                            'title'       => $title,
                            'bodyMessage' => $message,
                            'actionUrl'   => $actionUrl,
                        ],
                        function ($mail) use ($user, $cleanTitle) {
                            $fromAddress = config('mail.from.address') ?: 'learnerium@jlm.com.ng';
                            $fromName    = config('mail.from.name') ?: 'Learnerium';

                            $mail->from($fromAddress, $fromName)
                                 ->replyTo($fromAddress, 'Learnerium Support')
                                 ->to($user->email, $user->name)
                                 ->subject("Learnerium: {$cleanTitle}");

                            // RFC & anti-spam deliverability headers for Gmail, Yahoo & Outlook
                            $headers = $mail->getHeaders();
                            $headers->addTextHeader('Auto-Submitted', 'auto-generated');
                            $headers->addTextHeader('X-Auto-Response-Suppress', 'OOF, AutoReply');
                            $headers->addTextHeader('List-Unsubscribe', '<mailto:learnerium@jlm.com.ng?subject=unsubscribe>');
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

