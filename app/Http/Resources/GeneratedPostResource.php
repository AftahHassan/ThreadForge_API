<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneratedPostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'raw_content_id' => $this->raw_content_id,

            'campaign' => $this->whenLoaded('campaign', function () {
                return [
                    'id' => $this->campaign->id,
                    'name' => $this->campaign->name,
                    'tone' => $this->campaign->tone,
                    'max_characters' => $this->campaign->max_characters,
                    'max_hashtags' => $this->campaign->max_hashtags,
                ];
            }),

            'raw_content' => $this->whenLoaded('rawContent', function () {
                return [
                    'id' => $this->rawContent->id,
                    'content' => $this->rawContent->content,
                    'source_type' => $this->rawContent->source_type,
                    'processing_status' => $this->rawContent->processing_status,
                ];
            }),

            'hook_propose' => $this->hook_propose,
            'body_points' => $this->body_points,
            'technical_readability_score' => $this->technical_readability_score,
            'suggested_hashtags' => $this->suggested_hashtags,
            'tone_compliance_justification' => $this->tone_compliance_justification,
            'status' => $this->status,
        ];
    }
}