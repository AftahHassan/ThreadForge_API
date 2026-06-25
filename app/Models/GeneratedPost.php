<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'raw_content_id',
        'hook_propose',
        'body_points',
        'technical_readability_score',
        'suggested_hashtags',
        'tone_compliance_justification',
        'status',
    ];

    protected $casts = [
        'body_points' => 'array',
        'suggested_hashtags' => 'array',
        'technical_readability_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function rawContent(): BelongsTo
    {
        return $this->belongsTo(RawContent::class);
    }
    
    public function conversations()
    {
        return $this->hasMany(\App\Models\Conversation::class);
    }
}