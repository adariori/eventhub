<?php

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;

test('guests cannot comment on an event', function () {
    $event = Event::factory()->create();

    $this->post(route('comments.store', $event), ['body' => 'Hâte d\'y être !'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('comments', 0);
});

test('a comment requires a body', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    $this->actingAs($user)
        ->post(route('comments.store', $event), ['body' => ''])
        ->assertSessionHasErrors('body');

    $this->assertDatabaseCount('comments', 0);
});

test('authenticated user can comment on an event', function () {
    $user = User::factory()->create();
    $event = Event::factory()->create();

    $response = $this->actingAs($user)->post(route('comments.store', $event), [
        'body' => 'Hâte d\'y être !',
    ]);

    $response->assertRedirect()->assertSessionHas('status');
    $this->assertDatabaseHas('comments', [
        'event_id' => $event->id,
        'user_id' => $user->id,
        'body' => 'Hâte d\'y être !',
    ]);
});

test('comment author can delete their own comment', function () {
    $user = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->delete(route('comments.destroy', $comment))
        ->assertRedirect();

    $this->assertModelMissing($comment);
});

test('a different user cannot delete someone else\'s comment', function () {
    $author = User::factory()->create();
    $comment = Comment::factory()->create(['user_id' => $author->id]);
    $otherUser = User::factory()->create();

    $this->actingAs($otherUser)
        ->delete(route('comments.destroy', $comment))
        ->assertForbidden();

    $this->assertModelExists($comment);
});
