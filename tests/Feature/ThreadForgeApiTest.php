<?php

use App\Jobs\GeneratePostJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;


it('cree un utilisateur avec register et retourne un token', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Creator Tech',
        'email' => 'creator@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'user',
            'token',
            'token_type',
        ]);
});

it('connecte un utilisateur avec les bons identifiants et retourne un token', function () {
    User::factory()->create([
        'email' => 'creator@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'creator@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'token',
        ]);
});

it('rejette le login avec un mauvais mot de passe', function () {
    User::factory()->create([
        'email' => 'creator@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'creator@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});

it('bloque la route campaigns sans token', function () {
    $response = $this->getJson('/api/campaigns');

    $response->assertStatus(401);
});

it('autorise la route campaigns avec un token valide', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $user->campaigns()->create([
        'name' => 'Laravel Expert',
        'target_audience' => 'Développeurs Laravel',
        'tone' => 'Professionnel',
        'max_characters' => 280,
        'max_hashtags' => 3,
        'style_rules' => 'Commencer par un hook puissant.',
    ]);

    $response = $this->getJson('/api/campaigns');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'target_audience',
                    'tone',
                    'max_characters',
                    'max_hashtags',
                    'style_rules',
                ],
            ],
        ]);
});

it('retourne 422 si le champ name est manquant lors de la creation campaign', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/campaigns', [
        'target_audience' => 'Développeurs Laravel',
        'tone' => 'Professionnel',
        'max_characters' => 280,
        'max_hashtags' => 3,
        'style_rules' => 'Commencer par un hook puissant.',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

it('dispatch le job de generation sans appeler la vraie IA', function () {
    Queue::fake();

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $campaign = $user->campaigns()->create([
        'name' => 'Laravel Expert',
        'target_audience' => 'Développeurs Laravel',
        'tone' => 'Professionnel',
        'max_characters' => 280,
        'max_hashtags' => 3,
        'style_rules' => 'Commencer par un hook puissant.',
    ]);

    $response = $this->postJson('/api/content/repurpose', [
        'campaign_id' => $campaign->id,
        'content' => 'Aujourd hui j ai appris Laravel Queue.',
        'source_type' => 'dev_note',
    ]);

    $response->assertStatus(202);

    Queue::assertPushed(GeneratePostJob::class);
});