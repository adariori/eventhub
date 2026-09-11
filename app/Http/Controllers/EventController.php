<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::latest()->get();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('nom')->get();

        return view('events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $data = $request->safe()->except('categories');

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $event = $request->user()->organizedEvents()->create($data);

        $event->categories()->sync($request->validated()['categories'] ?? []);

        return redirect()->route('events.show', $event)->with('status', 'Événement créé.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        Gate::authorize('update', $event);

        $data = $request->validated();

        if ($request->hasFile('cover')) {
            if ($event->cover_path) {
                Storage::disk('public')->delete($event->cover_path);
            }

            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        $event->update($data);

        return redirect()->route('events.show', $event)->with('status', 'Événement mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);

        if ($event->cover_path) {
            Storage::disk('public')->delete($event->cover_path);
        }

        $event->delete();

        return redirect()->route('events.index')->with('status', 'Événement supprimé.');
    }

    /**
     * Register the authenticated user as a participant.
     */
    public function register(Request $request, Event $event)
    {
        $event->participants()->syncWithoutDetaching([$request->user()->id]);

        return back()->with('status', 'Inscription confirmée.');
    }

    /**
     * Remove the authenticated user from the participants.
     */
    public function unregister(Request $request, Event $event)
    {
        $event->participants()->detach($request->user()->id);

        return back()->with('status', 'Désinscription effectuée.');
    }
}
