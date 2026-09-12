<x-layout titre="Événements">

<div class="flex items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Événements</h1>

    @auth
        <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition ease-in-out duration-150">
            Nouvel événement
        </a>
    @endauth
</div>

<form action="{{ route('events.index') }}" method="GET" class="mb-8">
    <label for="categorie" class="sr-only">Filtrer par catégorie</label>
    <select name="categorie" id="categorie" onchange="this.form.submit()"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
        <option value="">Toutes les catégories</option>
        @foreach ($categories as $categorie)
            <option value="{{ $categorie->id }}" @selected($categorieId == $categorie->id)>
                {{ $categorie->nom }}
            </option>
        @endforeach
    </select>
    <noscript><button type="submit" class="ms-2 text-sm underline">Filtrer</button></noscript>
</form>

<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    @forelse ($events as $event)
        <a href="{{ route('events.show', $event) }}" class="block overflow-hidden rounded-xl bg-white border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition">
            @if ($event->cover_path)
                <img src="{{ Storage::url($event->cover_path) }}" alt="" class="h-40 w-full object-cover">
            @else
                <div class="h-40 w-full bg-gradient-to-br from-indigo-400 to-fuchsia-400"></div>
            @endif

            <div class="p-4">
                <h2 class="font-semibold text-gray-900">{{ $event->titre }}</h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}
                </p>

                @if ($event->categories->isNotEmpty())
                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach ($event->categories as $categorie)
                            <span class="inline-block rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
                                {{ $categorie->nom }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </a>
    @empty
        <p class="col-span-full text-gray-500">Aucun événement pour cette sélection.</p>
    @endforelse
</div>

</x-layout>
