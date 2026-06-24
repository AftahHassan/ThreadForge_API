<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\RepurposeContentRequest;
use App\Http\Resources\RawContentResource;
use App\Jobs\GeneratePostJob;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class ContentRepurposeController extends Controller
{
    public function store(RepurposeContentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $campaign = Campaign::where('id', $validated['campaign_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $campaign) {
            return response()->json([
                'message' => 'Blueprint introuvable ou non autorisé.',
            ], 403);
        }

        $rawContent = $request->user()->rawContents()->create([
            'campaign_id' => $campaign->id,
            'content' => $validated['content'],
            'source_type' => $validated['source_type'] ?? 'note',
            'processing_status' => 'pending',
        ]);

        GeneratePostJob::dispatch($rawContent->id);

        return response()->json([
            'message' => 'Contenu reçu. La génération est lancée en arrière-plan.',
            'raw_content' => new RawContentResource($rawContent),
        ], 202);
    }
}