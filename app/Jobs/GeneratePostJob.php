<?php

namespace App\Jobs;

use App\Models\GeneratedPost;
use App\Models\RawContent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class GeneratePostJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $rawContentId
    ) {
    }

    public function handle(): void
    {
        $rawContent = RawContent::with('campaign')->findOrFail($this->rawContentId);

        try {
            $rawContent->update([
                'processing_status' => 'processing',
                'error_message' => null,
            ]);

            $campaign = $rawContent->campaign;

            $response = Http::withToken(config('services.groq.api_key'))
                ->acceptJson()
                ->post(config('services.groq.api_url'), [
                    'model' => config('services.groq.model'),
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un assistant IA spécialisé dans le repurposing de contenu pour créateurs tech. Tu dois répondre uniquement avec un JSON valide, sans texte avant ou après.',
                        ],
                        [
                            'role' => 'user',
                            'content' => "
Transforme le contenu brut suivant en post pour X/Twitter.

Respecte strictement le Campaign Blueprint.

Campaign Blueprint :
- Audience cible : {$campaign->target_audience}
- Ton : {$campaign->tone}
- Maximum caractères : {$campaign->max_characters}
- Maximum hashtags : {$campaign->max_hashtags}
- Règles de style : {$campaign->style_rules}

Contenu brut :
{$rawContent->content}

Retourne uniquement ce JSON exact :
{
  \"hook_propose\": \"string\",
  \"body_points\": [\"string\", \"string\", \"string\"],
  \"technical_readability_score\": 85,
  \"suggested_hashtags\": [\"#Laravel\"],
  \"tone_compliance_justification\": \"string\"
}
",
                        ],
                    ],
                    'temperature' => 0.7,
                ]);

            if ($response->failed()) {
                throw new Exception('Erreur API Groq : ' . $response->body());
            }

            $content = $response->json('choices.0.message.content');

            $structuredOutput = json_decode($content, true);

            if (! is_array($structuredOutput)) {
                throw new Exception('Réponse Groq invalide : ' . $content);
            }

            $requiredKeys = [
                'hook_propose',
                'body_points',
                'technical_readability_score',
                'suggested_hashtags',
                'tone_compliance_justification',
            ];

            foreach ($requiredKeys as $key) {
                if (! array_key_exists($key, $structuredOutput)) {
                    throw new Exception("Clé manquante dans la réponse Groq : {$key}");
                }
            }

            GeneratedPost::create([
                'user_id' => $rawContent->user_id,
                'campaign_id' => $rawContent->campaign_id,
                'raw_content_id' => $rawContent->id,
                'hook_propose' => $structuredOutput['hook_propose'],
                'body_points' => $structuredOutput['body_points'],
                'technical_readability_score' => $structuredOutput['technical_readability_score'],
                'suggested_hashtags' => $structuredOutput['suggested_hashtags'],
                'tone_compliance_justification' => $structuredOutput['tone_compliance_justification'],
                'status' => 'draft',
            ]);

            $rawContent->update([
                'processing_status' => 'completed',
            ]);
        } catch (Exception $exception) {
            $rawContent->update([
                'processing_status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}