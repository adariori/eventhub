<x-layout titre="{{ $event->titre }}">

@if (session('status'))
    <p style="color: green;">{{ session('status') }}</p>
@endif

@if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<h1>{{ $event->titre }}</h1>

<p>{{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}</p>

<p>{{ $event->description }}</p>

<p>
    @can('update', $event)
    <a href="{{ route('events.edit', $event) }}">Modifier</a>
    @endcan
</p>

@if ($event->cover_path)
<img src="{{ Storage::url($event->cover_path) }}" alt="Couverture" style="max-width: 100%;">
@endif

<form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
    @csrf
    @method('DELETE')
     @can('delete', $event)
    <button type="submit">Supprimer</button>
    @endcan
</form>

<h2>Participants ({{ $event->participants->count() }})</h2>

@forelse ($event->participants as $participant)
    <p>{{ $participant->name }}</p>
@empty
    <p>Aucun participant pour le moment.</p>
@endforelse

@auth
    @if ($event->participants->contains(auth()->id()))
        <form action="{{ route('events.unregister', $event) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Se désinscrire</button>
        </form>
    @elseif ($event->date->isFuture())
        <form action="{{ route('events.register', $event) }}" method="POST">
            @csrf
            <button type="submit">S'inscrire</button>
        </form>
    @else
        <p>Cet événement est déjà passé, inscription impossible.</p>
    @endif
@endauth

<h2>Commentaires ({{ $event->comments->count() }})</h2>

@auth
    <form action="{{ route('comments.store', $event) }}" method="POST">
        @csrf
        <textarea name="body" placeholder="Votre commentaire...">{{ old('body') }}</textarea>
        @error('body') <span style="color: red;">{{ $message }}</span> @enderror
        <button type="submit">Publier</button>
    </form>
@endauth

@forelse ($event->comments as $comment)
    <p>
        <strong>{{ $comment->author->name }}</strong>
        — {{ $comment->created_at->format('d/m/Y H:i') }}<br>
        {{ $comment->body }}
        @can('delete', $comment)
            <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Supprimer</button>
            </form>
        @endcan
    </p>
@empty
    <p>Aucun commentaire pour le moment.</p>
@endforelse

<p><a href="{{ route('events.index') }}">Retour à la liste</a></p>

</x-layout>
