<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'subject_id',
        'parent_id',
        'name',
    ];
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            Category::class,
            'parent_id'
        );
    }
}