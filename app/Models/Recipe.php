<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal_id',
        'title',
        'instructions',
        'ingredients',
        'category_id',
        'area_id',
        'tags',
        'thumbnail',
        'youtube',
        'source',
        'image_source',
        'creative_commons_confirmed',
        'date_modified',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'tags' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

