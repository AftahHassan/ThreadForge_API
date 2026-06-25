<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\GeneratedPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class GhostwriterController extends Controller
{
    public function chat(SendMessageRequest $request, GeneratedPost $post): JsonResponse
    {
        if ($post->user_id !== $request->user()->id) {
            abort(403, 'Accès refusé.');
        }

        $validated = $request->validated();

        $conversation = null;

        if (! empty($validated['conversation_id'])) {
            $conversation = Conversation::where('id', $validated['conversation_id'])
                ->where('user_id', $request->user()->id)
                ->where('generated_post_id', $post->id)
                ->firstOrFail();
        } else {
            $conversation = Conversation::create([
                'user_id' => $request->user()->id,
                'generated_post_id' => $post->id,
                'title' => 'Discussion autour du post #' . $post->id,
            ]);
        }

        $userMessage = $conversation->messages()->create([
            'role' => 'user',
            'content' => $validated['message'],
        ]);

        $history = $conversation->messages()
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->map(function ($message) {
                return [
                    'role' => $message->role === 'assistant' ? 'assistant' : 'user',
                    'content' => $message->content,
                ];
            })
            ->values()
            ->toArray();

        $post->load(['campaign', 'rawContent']);

        $systemPrompt = "
Tu es un Ghostwriter Assistant pour créateurs tech.
Tu aides l'utilisateur à améliorer un post généré.
Tu dois tenir compte du post initial, du contenu brut, du Blueprint et de l'historique de conversation.

POST GÉNÉRÉ :
Hook : {$post->hook_propose}
Body points : " . json_encode($post->body_points, JSON_UNESCAPED_UNICODE) . "
Hashtags : " . json_encode($post->suggested_hashtags, JSON_UNESCAPED_UNICODE) . "

BLUEPRINT :
Audience : {$post->campaign->target_audience}
Ton : {$post->campaign->tone}
Max caractères : {$post->campaign->max_characters}
Max hashtags : {$post->campaign->max_hashtags}
Règles : {$post->campaign->style_rules}

Contenu brut :
{$post->rawContent->content}
";

        $messages = array_merge(
            [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
            ],
            $history
        );

        $response = Http::withToken(config('services.groq.api_key'))
            ->acceptJson()
            ->post(config('services.groq.api_url'), [
                'model' => config('services.groq.model'),
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Erreur Groq.',
                'error' => $response->body(),
            ], 500);
        }

        $assistantContent = $response->json('choices.0.message.content');

        $assistantMessage = $conversation->messages()->create([
            'role' => 'assistant',
            'content' => $assistantContent,
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'user_message' => new MessageResource($userMessage),
            'assistant_message' => new MessageResource($assistantMessage),
        ], 201);
    }
}