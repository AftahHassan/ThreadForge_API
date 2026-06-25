<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneratedPostResource;
use App\Models\GeneratedPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GeneratedPostController extends Controller
{
    public function index(Request $request)
    {
        $posts = GeneratedPost::query()
            ->where('user_id', $request->user()->id)
            ->with(['campaign', 'rawContent'])
            ->latest()
            ->get();

        return GeneratedPostResource::collection($posts);
    }

    public function show(Request $request, GeneratedPost $generatedPost): JsonResponse
    {
        $this->ensurePostBelongsToUser($request, $generatedPost);

        $generatedPost->load(['campaign', 'rawContent']);

        return response()->json([
            'post' => new GeneratedPostResource($generatedPost),
        ]);
    }

    public function updateStatus(Request $request, GeneratedPost $generatedPost): JsonResponse
    {
        $this->ensurePostBelongsToUser($request, $generatedPost);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'posted', 'archived'])],
        ]);

        $generatedPost->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Statut du post modifié avec succès.',
            'post' => new GeneratedPostResource($generatedPost),
        ]);
    }

    private function ensurePostBelongsToUser(Request $request, GeneratedPost $generatedPost): void
    {
        if ($generatedPost->user_id !== $request->user()->id) {
            abort(403, 'Accès refusé.');
        }
    }
}