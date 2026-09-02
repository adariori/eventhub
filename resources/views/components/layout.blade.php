<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre ?? 'eventhub' }}</title>
</head>
<body>
    <header>
        <h2>EventHub</h2>
        <nav>
            <a href="/events">Events</a> 
        </nav>
        <hr>
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer>
        <hr>
        <p>© 2026 EventHub.</p>
    </footer>
</body>
</html>