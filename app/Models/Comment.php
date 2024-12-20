<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_id', 'comment'];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}

