<?php

use App\Models\Event;
use App\Models\User;

test('guests cannot register for an event', function () {
    $event = Event::factory()->create();

    $this->post(route('events.register', $event))->assertRedirect(route('login'));

    expect($event->participants()->count())->toBe(0);
});

test('authenticated user can register for an upcoming event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['date' => now()->addWeek()]);

    $response = $this->actingAs($user)->post(route('events.register', $event));

    $response->assertRedirect()->assertSessionHas('status');
    expect($event->participants()->pluck('users.id')->all())->toBe([$user->id]);
});

test('registering twice does not create a duplicate participant', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['date' => now()->addWeek()]);

    $this->actingAs($user)->post(route('events.register', $event));
    $this->actingAs($user)->post(route('events.register', $event));

    expect($event->participants()->count())->toBe(1);
});

test('authenticated user can unregister from an event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['date' => now()->addWeek()]);
    $event->participants()->attach($user);

    $response = $this->actingAs($user)->delete(route('events.unregister', $event));

    $response->assertRedirect()->assertSessionHas('status');
    expect($event->participants()->count())->toBe(0);
});

test('user cannot register for an event that has already passed', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create(['date' => now()->subDay()]);

    $response = $this->actingAs($user)->post(route('events.register', $event));

    $response->assertRedirect()->assertSessionHas('error');
    expect($event->participants()->count())->toBe(0);
});
