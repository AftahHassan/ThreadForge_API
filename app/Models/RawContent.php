<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RawContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'content',
        'source_type',
        'processing_status',
        'error_message',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function generatedPosts(): HasMany
    {
        return $this->hasMany(GeneratedPost::class);
    }
}