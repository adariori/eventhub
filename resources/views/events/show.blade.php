<x-layout titre="{{ $event->titre }}">

<h1>{{ $event->titre }}</h1>

<p>{{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}</p>

<p>{{ $event->description }}</p>

<p>
    @can('update', $event)
    <a href="{{ route('events.edit', $event) }}">Modifier</a>
    @endcan
</p>

<form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
    @csrf
    @method('DELETE')
     @can('delete', $event)
    <button type="submit">Supprimer</button>
    @endcan
</form>

@auth
    @if ($event->participants->contains(auth()->id()))
        <form action="{{ route('events.unregister', $event) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Se désinscrire</button>
        </form>
    @else
        <form action="{{ route('events.register', $event) }}" method="POST">
            @csrf
            <button type="submit">S'inscrire</button>
        </form>
    @endif
@endauth

<p><a href="{{ route('events.index') }}">Retour à la liste</a></p>

</x-layout>
