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
        Schema::create('conversations', function (Blueprint $table) {

            $table->id();

            // Utilisateur propriétaire
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Post généré concerné
            $table->foreignId('generated_post_id')
                ->constrained()
                ->cascadeOnDelete();

            // Titre automatique de la conversation
            $table->string('title')->nullable();

            // Etat de la conversation
            $table->enum('status', [
                'active',
                'closed'
            ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};