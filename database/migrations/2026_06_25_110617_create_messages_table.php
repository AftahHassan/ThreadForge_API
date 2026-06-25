<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {

            $table->id();

            // Conversation
            $table->foreignId('conversation_id')
                ->constrained()
                ->cascadeOnDelete();

            // Qui a envoyé le message ?
            $table->enum('role', [
                'user',
                'assistant',
                'system'
            ]);

            // Contenu
            $table->longText('content');

            // Informations supplémentaires (facultatif)
            $table->json('metadata')->nullable();

            // Nombre de tokens utilisés
            $table->unsignedInteger('tokens_used')
                ->default(0);

            // Temps de réponse IA
            $table->unsignedInteger('response_time_ms')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};