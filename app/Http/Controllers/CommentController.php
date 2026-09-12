<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created comment for the given event.
     */
    public function store(StoreCommentRequest $request, Event $event)
    {
        $event->comments()->create([
            'body' => $request->validated('body'),
            'user_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Commentaire ajouté.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('status', 'Commentaire supprimé.');
    }
}
