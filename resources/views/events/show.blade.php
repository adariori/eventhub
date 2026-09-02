<x-layout titre="{{ $event->titre }}">

<h1>{{ $event->titre }}</h1>

<p>{{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}</p>

<p>{{ $event->description }}</p>

<p>
    <a href="{{ route('events.edit', $event) }}">Modifier</a>
</p>

<form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
    @csrf
    @method('DELETE')
    <button type="submit">Supprimer</button>
</form>

<p><a href="{{ route('events.index') }}">Retour à la liste</a></p>

</x-layout>
