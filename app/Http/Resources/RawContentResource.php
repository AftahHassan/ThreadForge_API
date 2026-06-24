<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RawContentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'campaign_id' => $this->campaign_id,
            'content' => $this->content,
            'source_type' => $this->source_type,
            'processing_status' => $this->processing_status,
            'error_message' => $this->error_message,
        ];
    }
}