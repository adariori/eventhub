<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\User;

test('guests cannot view the event creation form', function () {
    $this->get(route('events.create'))->assertRedirect(route('login'));
});

test('guests cannot create an event', function () {
    $this->post(route('events.store'), [])->assertRedirect(route('login'));

    $this->assertDatabaseCount('events', 0);
});

test('an event page displays', function () {
    $event = Event::factory()->create(['titre' => 'Concert de jazz']);

    $this->get(route('events.show', $event))
        ->assertOk()
        ->assertSee('Concert de jazz');
});

test('creating an event requires valid data', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('events.store'), [])
        ->assertSessionHasErrors(['titre', 'description', 'date', 'lieu']);

    $this->assertDatabaseCount('events', 0);
});

test('authenticated user can create an event with selected categories', function () {
    $user = User::factory()->create();
    $categories = Category::factory(2)->create();

    $response = $this->actingAs($user)->post(route('events.store'), [
        'titre' => 'Soirée jazz',
        'description' => 'Une belle soirée de jazz au centre-ville.',
        'date' => now()->addWeek()->format('Y-m-d H:i:s'),
        'lieu' => 'Paris',
        'categories' => $categories->pluck('id')->all(),
    ]);

    $event = Event::firstWhere('titre', 'Soirée jazz');

    $response->assertRedirect(route('events.show', $event));
    expect($event->user_id)->toBe($user->id);
    expect($event->categories()->pluck('categories.id')->sort()->values()->all())
        ->toBe($categories->pluck('id')->sort()->values()->all());
});

test('organizer can update their own event', function () {
    $organizer = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $organizer->id]);

    $response = $this->actingAs($organizer)->put(route('events.update', $event), [
        'titre' => 'Titre modifié',
        'description' => $event->description,
        'date' => now()->addWeek()->format('Y-m-d H:i:s'),
        'lieu' => $event->lieu,
    ]);

    $response->assertRedirect(route('events.show', $event));
    expect($event->fresh()->titre)->toBe('Titre modifié');
});

test('a different user cannot update someone else\'s event', function () {
    $organizer = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $organizer->id, 'titre' => 'Original']);
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->put(route('events.update', $event), [
            'titre' => 'Piraté',
            'description' => $event->description,
            'date' => now()->addWeek()->format('Y-m-d H:i:s'),
            'lieu' => $event->lieu,
        ])
        ->assertForbidden();

    expect($event->fresh()->titre)->toBe('Original');
});

test('organizer can delete their own event', function () {
    $organizer = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $organizer->id]);

    $this->actingAs($organizer)
        ->delete(route('events.destroy', $event))
        ->assertRedirect(route('events.index'));

    $this->assertModelMissing($event);
});

test('a different user cannot delete someone else\'s event', function () {
    $organizer = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $organizer->id]);
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->delete(route('events.destroy', $event))
        ->assertForbidden();

    $this->assertModelExists($event);
});

test('events index can be filtered by category', function () {
    $matching = Event::factory()->create();
    $other = Event::factory()->create();
    $category = Category::factory()->create();
    $matching->categories()->attach($category);

    $response = $this->get(route('events.index', ['categorie' => $category->id]));

    $response->assertOk();
    $response->assertSee($matching->titre);
    $response->assertDontSee($other->titre);
});
