<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = [
        'subject_id',
        'category_id',
        'front_text',
        'back_text',
        'memo',
        'mastery_level',
        'last_studied_at',
        'is_bookmarked',
        'front_image_url',
        'back_image_url',
    ];

    protected function casts(): array
    {
        return [
            'mastery_level' => 'integer',
            'last_studied_at' => 'datetime',
            'is_bookmarked' => 'boolean',
        ];
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}