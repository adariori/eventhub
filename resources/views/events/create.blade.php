<x-layout titre="Nouvel événement">

<h1>Créer un événement</h1>

<form action="{{ route('events.store') }}" method="post">
    @csrf

    <p>
        <label>Titre :</label><br>
        <input type="text" name="titre" value="{{ old('titre') }}">
        @error('titre') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Description :</label><br>
        <textarea name="description">{{ old('description') }}</textarea>
        @error('description') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Date :</label><br>
        <input type="datetime-local" name="date" value="{{ old('date') }}">
        @error('date') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>Lieu :</label><br>
        <input type="text" name="lieu" value="{{ old('lieu') }}">
        @error('lieu') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <button type="submit">Créer l'événement</button>

</form>

</x-layout>
