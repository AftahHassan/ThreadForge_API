<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'role' => $this->role,
            'content' => $this->content,
            'metadata' => $this->metadata,
            'tokens_used' => $this->tokens_used ?? 0,
            'response_time_ms' => $this->response_time_ms,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}