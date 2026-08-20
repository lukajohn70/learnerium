<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'type',
        'description',
        'is_required',
        'peer_review_enabled',
        'required_reviews_count',
        'config',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'peer_review_enabled' => 'boolean',
        'required_reviews_count' => 'integer',
        'config' => 'array', // Array cast for JSON surveys/questions
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
