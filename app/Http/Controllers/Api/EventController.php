<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EventResource::collection(
            Event::with('organizer', 'categories')->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $event = $request->user()->organizedEvents()->create(
            $request->safe()->only(['titre', 'description', 'date', 'lieu'])
        );

        return (new EventResource($event))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new EventResource(
            Event::with('organizer', 'categories')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, string $id)
    {
        $event = Event::findOrFail($id);
        Gate::authorize('update', $event);
        $event->update($request->safe()->only(['titre', 'description', 'date', 'lieu']));

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);
        Gate::authorize('delete', $event);
        $event->delete();

        return response()->json(null, 204);
    }
}
