<?php

namespace App\Jobs;

use App\Models\GeneratedPost;
use App\Models\RawContent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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

            /*
             * Version temporaire pour tester le workflow complet.
             * Au prochain niveau, cette partie sera remplacée par l'appel réel à Grok / laravel-ai.
             */
            $structuredOutput = [
                'hook_propose' => 'Laravel Queues m’a appris une chose : une API rapide ne doit jamais attendre un traitement lourd.',
                'body_points' => [
                    'Les jobs permettent de traiter les tâches en arrière-plan.',
                    'L’utilisateur reçoit une réponse immédiate.',
                    'Le système reste fluide même avec des appels IA longs.',
                ],
                'technical_readability_score' => 85,
                'suggested_hashtags' => ['#Laravel'],
                'tone_compliance_justification' => 'Le post respecte le ton demandé : ' . ($campaign->tone ?? 'professionnel') . '.',
            ];

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