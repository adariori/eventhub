# EventHub

Plateforme de gestion et de découverte d'événements construite avec Laravel. Les utilisateurs créent des comptes, publient des événements (avec catégories et image de couverture), s'inscrivent à ceux des autres, et échangent en commentaires.

## Fonctionnalités

- **Authentification** complète (inscription, connexion, réinitialisation de mot de passe) via Laravel Breeze
- **CRUD d'événements** : créer, consulter, modifier, supprimer — modification/suppression réservées à l'organisateur (Policy)
- **Catégories** : associées à un événement via des cases à cocher, filtrage de la liste par catégorie
- **Participants** : inscription / désinscription, compteur et liste des inscrits, inscription bloquée pour un événement déjà passé
- **Commentaires** sur chaque événement (suppression réservée à l'auteur)
- **Image de couverture** : upload, remplacement et suppression propres (pas de fichiers orphelins)
- **API JSON** (`/api/events`) avec Resource dédiée et authentification par token (Sanctum)
- **Validation** complète via Form Requests sur tous les formulaires
- **Données de test** : factories + seeder générant utilisateurs, événements, catégories, participants et commentaires aléatoires

## Stack technique

- [Laravel 13](https://laravel.com) (PHP 8.4+)
- [Tailwind CSS 3](https://tailwindcss.com)
- [Laravel Sanctum](https://laravel.com/docs/sanctum) (authentification API par token)
- [Pest](https://pestphp.com) (tests)
- SQLite en développement, PostgreSQL recommandé en production

## Installation locale

```bash
git clone https://github.com/adariori/EventHub.git
cd EventHub

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

npm run build
php artisan serve
```

L'application est accessible sur `http://localhost:8000`. Le seeder crée un compte de test :

- **Email** : `test@example.com`
- **Mot de passe** : `password`

Pour le développement avec rechargement à chaud des assets : `npm run dev` (dans un terminal séparé).

## Tests

```bash
php artisan test --compact
```

50 tests couvrent l'authentification, les autorisations (Policies), la validation, les catégories, les inscriptions et les commentaires.

## Déploiement

Voir [`DEPLOYMENT.md`](DEPLOYMENT.md) — checklist générale + guide dédié pour un déploiement sur [Render](https://render.com) (`Dockerfile` et `docker-entrypoint.sh` fournis et testés).

## Licence

MIT — voir [`LICENSE`](LICENSE).

## Auteur

**ARIORI OLOROUNKO Adéliyi Odjouola Moshood**
[GitHub](https://github.com/adariori) · [Portfolio](https://portefolio-nine-iota.vercel.app/) · adariori3@gmail.com
