<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'target_audience' => $this->target_audience,
            'tone' => $this->tone,
            'max_characters' => $this->max_characters,
            'max_hashtags' => $this->max_hashtags,
            'style_rules' => $this->style_rules,
            'generated_posts_count' => $this->whenCounted('generatedPosts'),
        ];
    }
}