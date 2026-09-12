<x-layout titre="Events">

<h1>Events</h1>

@if (session('status'))
    <p style="color: green;">{{ session('status') }}</p>
@endif

<p><a href="{{ route('events.create') }}">Nouvel événement</a></p>

<form action="{{ route('events.index') }}" method="GET">
    <label for="categorie">Filtrer par catégorie :</label>
    <select name="categorie" id="categorie" onchange="this.form.submit()">
        <option value="">Toutes les catégories</option>
        @foreach ($categories as $categorie)
            <option value="{{ $categorie->id }}" @selected($categorieId == $categorie->id)>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>
    <noscript><button type="submit">Filtrer</button></noscript>
</form>

@forelse ($events as $event)
    <article>
        <h2><a href="{{ route('events.show', $event) }}">{{ $event->titre }}</a></h2>
        <p>{{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}</p>
        <p>{{ $event->categories->pluck('nom')->implode(', ') }}</p>
    </article>
@empty
    <p>Aucun événement pour cette sélection.</p>
@endforelse

</x-layout>
