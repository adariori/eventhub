<x-layout>
    <h1>Modifier : {{ $event->titre }}</h1>

  <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

            <p>
            <label>Titre :</label><br>
            <input type="text" name="titre" value="{{ old('titre', $event->titre) }}">
            @error('titre') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

        <p>
            <label>Description :</label><br>
            <textarea name="description">{{ old('description', $event->description) }}</textarea>
            @error('description') <span style="color: red;">{{ $message }}</span> @enderror
        </p>

    <p>
        <label>Date :</label>
        <input type="datetime-local" name="date" value="{{ old('date', $event->date?->format('Y-m-d\TH:i')) }}">
        @error('date') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <label>lieu</label>
        <input type="text" name="lieu" value="{{ old('lieu', $event->lieu) }}">
        @error('lieu') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

      <p>
        <label>Image de couverture :</label><br>
        <input type="file" name="cover">
        @error('cover') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

    <p>
        <span>Catégories :</span><br>
        @foreach ($categories as $categorie)
            <label>
                <input type="checkbox" name="categories[]" value="{{ $categorie->id }}"
                    @checked(in_array($categorie->id, old('categories', $event->categories->pluck('id')->all())))>
                {{ $categorie->nom }}
            </label><br>
        @endforeach
        @error('categories') <span style="color: red;">{{ $message }}</span> @enderror
    </p>

        <button type="submit">Enregistrer les modifications</button>
    </form>
</x-layout>