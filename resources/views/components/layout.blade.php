<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ $titre ?? 'eventhub' }}</title>
    <style>
        body {
            background: #ffffff;
            color: #1a1a1a;
            font-family: system-ui, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 1rem;
            line-height: 1.5;
        }

        a {
            color: #1d4ed8;
        }
    </style>
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