<x-layout titre="Events">

<h1>Events</h1>

@if (session('status'))
    <p style="color: green;">{{ session('status') }}</p>
@endif

<p><a href="{{ route('events.create') }}">Nouvel événement</a></p>

@forelse ($events as $event)
    <article>
        <h2><a href="{{ route('events.show', $event) }}">{{ $event->titre }}</a></h2>
        <p>{{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}</p>
    </article>
@empty
    <p>Aucun événement pour le moment.</p>
@endforelse

</x-layout>
