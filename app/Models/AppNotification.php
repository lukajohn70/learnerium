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
     * Create a notification for a user.
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
        return static::create([
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'message'    => $message,
            'action_url' => $actionUrl,
            'icon'       => $icon,
            'color'      => $color,
            'is_read'    => false,
        ]);
    }
}
