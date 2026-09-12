<?php

use App\Models\Event;
use App\Models\User;

test('organizer can update and delete their event', function () {
    $organizer = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $organizer->id]);

    expect($organizer->can('update', $event))->toBeTrue();
    expect($organizer->can('delete', $event))->toBeTrue();
});

test('a different user cannot update or delete someone else\'s event', function () {
    $organizer = User::factory()->create();
    $event = Event::factory()->create(['user_id' => $organizer->id]);
    $otherUser = User::factory()->create();

    expect($otherUser->can('update', $event))->toBeFalse();
    expect($otherUser->can('delete', $event))->toBeFalse();
});
