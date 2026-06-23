<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::query()
            ->where('user_id', $request->user()->id)
            ->withCount('generatedPosts')
            ->latest()
            ->get();

        return CampaignResource::collection($campaigns);
    }

    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $campaign = $request->user()->campaigns()->create($request->validated());

        return response()->json([
            'message' => 'Campaign Blueprint créé avec succès.',
            'campaign' => new CampaignResource($campaign),
        ], 201);
    }

    public function show(Request $request, Campaign $campaign): JsonResponse
    {
        $this->ensureCampaignBelongsToUser($request, $campaign);

        $campaign->loadCount('generatedPosts');

        return response()->json([
            'campaign' => new CampaignResource($campaign),
        ]);
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign): JsonResponse
    {
        $this->ensureCampaignBelongsToUser($request, $campaign);

        $campaign->update($request->validated());

        return response()->json([
            'message' => 'Campaign Blueprint modifié avec succès.',
            'campaign' => new CampaignResource($campaign),
        ]);
    }

    public function destroy(Request $request, Campaign $campaign): JsonResponse
    {
        $this->ensureCampaignBelongsToUser($request, $campaign);

        $campaign->delete();

        return response()->json([
            'message' => 'Campaign Blueprint supprimé avec succès.',
        ]);
    }

    private function ensureCampaignBelongsToUser(Request $request, Campaign $campaign): void
    {
        if ($campaign->user_id !== $request->user()->id) {
            abort(403, 'Accès refusé.');
        }
    }
}