<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BroadcastEmail extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_type',
        'recipient_user_id',
        'subject',
        'message',
        'total_sent',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(InboundMessage::class, 'broadcast_email_id');
    }
}
