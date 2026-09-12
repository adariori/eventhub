<?php

use App\Models\Event;
use App\Models\User;

test('creating an event via the api requires valid data', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/events', [])
        ->assertJsonValidationErrors(['titre', 'description', 'date', 'lieu']);

    $this->assertDatabaseCount('events', 0);
});

test('authenticated user can create an event via the api', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/events', [
        'titre' => 'Soirée jazz',
        'description' => 'Une belle soirée de jazz au centre-ville.',
        'date' => now()->addWeek()->format('Y-m-d H:i:s'),
        'lieu' => 'Paris',
    ]);

    $response->assertCreated();
    $this->assertDatabaseHas('events', ['titre' => 'Soirée jazz', 'user_id' => $user->id]);
});

test('updating an event via the api requires valid data', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $user->id, 'titre' => 'Original']);

    $this->actingAs($user)
        ->putJson("/api/events/{$event->id}", ['titre' => ''])
        ->assertJsonValidationErrors('titre');

    expect($event->fresh()->titre)->toBe('Original');
});
