<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $conversations = Conversation::query()
            ->where('user_id', $request->user()->id)
            ->with('generatedPost')
            ->withCount('messages')
            ->latest()
            ->paginate(10);

        return ConversationResource::collection($conversations);
    }

    public function show(Request $request, Conversation $conversation): JsonResponse
    {
        $this->ensureConversationBelongsToUser($request, $conversation);

        $conversation->load([
            'generatedPost',
            'messages' => function ($query) {
                $query->oldest();
            },
        ]);

        return response()->json([
            'conversation' => new ConversationResource($conversation),
        ]);
    }

    public function update(Request $request, Conversation $conversation): JsonResponse
    {
        $this->ensureConversationBelongsToUser($request, $conversation);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'closed'])],
        ]);

        $conversation->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Statut de la conversation modifié avec succès.',
            'conversation' => new ConversationResource($conversation),
        ]);
    }

    public function destroy(Request $request, Conversation $conversation): JsonResponse
    {
        $this->ensureConversationBelongsToUser($request, $conversation);

        $conversation->delete();

        return response()->json([
            'message' => 'Conversation supprimée avec succès.',
        ]);
    }

    private function ensureConversationBelongsToUser(Request $request, Conversation $conversation): void
    {
        if ($conversation->user_id !== $request->user()->id) {
            abort(403, 'Accès refusé.');
        }
    }
}