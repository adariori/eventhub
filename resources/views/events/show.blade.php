<x-layout titre="{{ $event->titre }}">

<a href="{{ route('events.index') }}" class="text-sm text-gray-500 hover:text-indigo-600">
    &larr; Retour à la liste
</a>

<div class="mt-4 overflow-hidden rounded-xl bg-white border border-gray-100 shadow-sm">
    @if ($event->cover_path)
        <img src="{{ Storage::url($event->cover_path) }}" alt="Couverture de l'événement" class="h-64 w-full object-cover">
    @else
        <div class="h-40 w-full bg-gradient-to-br from-indigo-400 to-fuchsia-400"></div>
    @endif

    <div class="p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $event->titre }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $event->date?->format('d/m/Y H:i') }} — {{ $event->lieu }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Organisé par {{ $event->organizer->name }}
                </p>
            </div>

            <div class="flex gap-2">
                @can('update', $event)
                    <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                        Modifier
                    </a>
                @endcan

                @can('delete', $event)
                    <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 transition ease-in-out duration-150">
                            Supprimer
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        @if ($event->categories->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1">
                @foreach ($event->categories as $categorie)
                    <span class="inline-block rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
                        {{ $categorie->nom }}
                    </span>
                @endforeach
            </div>
        @endif

        <p class="mt-6 whitespace-pre-line text-gray-700">{{ $event->description }}</p>

        @auth
            <div class="mt-6">
                @if ($event->participants->contains(auth()->id()))
                    <form action="{{ route('events.unregister', $event) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                            Se désinscrire
                        </button>
                    </form>
                @elseif ($event->date->isFuture())
                    <form action="{{ route('events.register', $event) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 transition ease-in-out duration-150">
                            S'inscrire
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">Cet événement est déjà passé, inscription impossible.</p>
                @endif
            </div>
        @endauth
    </div>
</div>

<div class="mt-8 rounded-xl bg-white border border-gray-100 shadow-sm p-6">
    <h2 class="font-semibold text-gray-900">
        Participants ({{ $event->participants->count() }})
    </h2>

    @forelse ($event->participants as $participant)
        <p class="mt-2 text-sm text-gray-600">{{ $participant->name }}</p>
    @empty
        <p class="mt-2 text-sm text-gray-500">Aucun participant pour le moment.</p>
    @endforelse
</div>

<div class="mt-8 rounded-xl bg-white border border-gray-100 shadow-sm p-6">
    <h2 class="font-semibold text-gray-900">
        Commentaires ({{ $event->comments->count() }})
    </h2>

    @auth
        <form action="{{ route('comments.store', $event) }}" method="POST" class="mt-4">
            @csrf
            <label for="body" class="sr-only">Votre commentaire</label>
            <textarea id="body" name="body" rows="3" placeholder="Votre commentaire..."
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('body') }}</textarea>
            <x-input-error :messages="$errors->get('body')" class="mt-2" />
            <x-primary-button class="mt-2">Publier</x-primary-button>
        </form>
    @endauth

    <div class="mt-6 space-y-4">
        @forelse ($event->comments as $comment)
            <div class="border-t border-gray-100 pt-4">
                <p class="text-sm">
                    <span class="font-semibold text-gray-900">{{ $comment->author->name }}</span>
                    <span class="text-gray-400"> — {{ $comment->created_at->format('d/m/Y H:i') }}</span>
                </p>
                <p class="mt-1 text-gray-700">{{ $comment->body }}</p>

                @can('delete', $comment)
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="mt-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-600 hover:underline">Supprimer</button>
                    </form>
                @endcan
            </div>
        @empty
            <p class="text-sm text-gray-500">Aucun commentaire pour le moment.</p>
        @endforelse
    </div>
</div>

</x-layout>
