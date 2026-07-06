<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('connecte un utilisateur avec les bons identifiants', function () {
    $user = User::factory()->create([
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

it('rejette la connexion avec un mauvais mot de passe', function () {
    $user = User::factory()->create([
        'email' => 'creator@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'creator@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(401);
});