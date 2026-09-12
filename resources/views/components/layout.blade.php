<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titre ?? 'EventHub' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('events.index') }}" class="text-lg font-bold text-indigo-600">
                    EventHub
                </a>

                <div class="flex items-center gap-6 text-sm font-medium">
                    <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-indigo-600">
                        Événements
                    </a>

                    @auth
                        <a href="{{ route('events.create') }}" class="text-gray-600 hover:text-indigo-600">
                            Nouvel événement
                        </a>

                        <div class="flex items-center gap-3">
                            <span class="text-gray-400">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-indigo-600">
                                    Se déconnecter
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-indigo-500 transition">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="py-8 text-center text-sm text-gray-400">
        © {{ date('Y') }} EventHub.
    </footer>
</body>
</html>
