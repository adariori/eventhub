<x-layout titre="Nouvel événement">

<h1 class="text-2xl font-bold text-gray-900 mb-6">Créer un événement</h1>

<div class="rounded-xl bg-white border border-gray-100 shadow-sm p-6">
    <form action="{{ route('events.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div>
            <x-input-label for="titre" value="Titre" />
            <x-text-input id="titre" name="titre" type="text" class="block mt-1 w-full" :value="old('titre')" />
            <x-input-error :messages="$errors->get('titre')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="description" value="Description" />
            <textarea id="description" name="description" rows="5"
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="date" value="Date" />
            <x-text-input id="date" name="date" type="datetime-local" class="block mt-1 w-full" :value="old('date')" />
            <x-input-error :messages="$errors->get('date')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="lieu" value="Lieu" />
            <x-text-input id="lieu" name="lieu" type="text" class="block mt-1 w-full" :value="old('lieu')" />
            <x-input-error :messages="$errors->get('lieu')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="cover" value="Image de couverture" />
            <input id="cover" name="cover" type="file"
                class="block mt-1 w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:uppercase file:tracking-widest file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
            <x-input-error :messages="$errors->get('cover')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label value="Catégories" />
            <div class="mt-2 flex flex-wrap gap-x-6 gap-y-2">
                @foreach ($categories as $categorie)
                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="categories[]" value="{{ $categorie->id }}"
                            @checked(in_array($categorie->id, old('categories', [])))
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        {{ $categorie->nom }}
                    </label>
                @endforeach
            </div>
            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-primary-button>Créer l'événement</x-primary-button>
        </div>
    </form>
</div>

</x-layout>
