# Checklist de déploiement — EventHub

## 1. Avant de déployer

- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `npm ci && npm run build`
- [ ] Tous les tests passent : `php artisan test --compact`
- [ ] `vendor/bin/pint --test` ne signale rien
- [ ] `.env` de production créé (jamais commité) à partir de `.env.example`
- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] `APP_KEY` généré (`php artisan key:generate`) et différent de celui de dev
- [ ] Connexion base de données de prod configurée (`DB_*`) — MySQL/PostgreSQL recommandé plutôt que SQLite en production
- [ ] `APP_URL` renseignée avec le vrai domaine (nécessaire pour les liens générés, les emails, `Storage::url()`)
- [ ] Configuration mail (`MAIL_*`) pour la vérification d'email et les notifications

## 2. Base de données

- [ ] `php artisan migrate --force` (le `--force` est obligatoire en prod, `APP_ENV=production` bloque sinon)
- [ ] **Ne pas lancer le `DatabaseSeeder`** en production (il crée des utilisateurs/événements factices) — sauf besoin explicite de données de démo
- [ ] Sauvegarde de la base configurée (au minimum avant chaque déploiement)

## 3. Stockage & fichiers

- [ ] `php artisan storage:link` (sinon les images de couverture des événements ne s'affichent pas)
- [ ] Permissions correctes sur `storage/` et `bootstrap/cache/` (writable par le serveur web)
- [ ] Espace disque suivi si beaucoup d'uploads d'images

## 4. Performance

- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] `php artisan event:cache`
- [ ] Après tout changement de code : **vider ces caches puis les régénérer** (`config:clear`, etc.) — un ancien cache de config est une source classique de bugs difficiles à diagnostiquer

## 5. Sécurité

- [ ] HTTPS forcé (certificat SSL valide)
- [ ] `SESSION_SECURE_COOKIE=true` une fois HTTPS actif
- [ ] Variables sensibles (`APP_KEY`, `DB_PASSWORD`, tokens API) uniquement dans `.env`, jamais dans le code ou committées
- [ ] Sanctum : `SANCTUM_STATEFUL_DOMAINS` limité aux vrais domaines si l'API est utilisée par un front séparé

## 6. Après le déploiement

- [ ] Vérifier manuellement : page d'accueil, inscription/connexion, création d'un événement, upload d'image, inscription à un événement, ajout d'un commentaire
- [ ] Vérifier les logs (`storage/logs/laravel.log`) pour toute erreur silencieuse
- [ ] Vérifier que `/api/events` répond bien en JSON

## Notes

- Ce projet peut être déployé sur [Laravel Cloud](https://cloud.laravel.com/), qui gère automatiquement une grande partie des points ci-dessus (cache, migrations, SSL).
- Pour un déploiement manuel (VPS), automatiser les étapes 1, 2 et 4 dans un script de déploiement pour éviter les oublis.

## Déploiement sur Render

Render n'a pas de buildpack PHP natif : le déploiement se fait via Docker. Le `Dockerfile` et le `docker-entrypoint.sh` à la racine du repo sont prêts et **testés en local** (build + exécution complète contre une vraie base Postgres : migrations, upload d'image, inscription — tout fonctionne).

### 1. Créer la base de données
1. Sur [render.com](https://render.com), **New +** → **PostgreSQL**.
2. Une fois créée, note les informations de connexion (onglet *Connect*) : host interne, port, nom de la base, utilisateur, mot de passe.

### 2. Créer le service web
1. **New +** → **Web Service** → connecte le repo GitHub d'EventHub.
2. **Runtime** : Docker (Render détecte le `Dockerfile` automatiquement).
3. **Region** : la même que la base de données (latence + parfois obligatoire pour l'accès réseau interne).

### 3. Variables d'environnement (onglet *Environment*)

| Variable | Valeur |
|---|---|
| `APP_KEY` | générée en local avec `php artisan key:generate --show`, à coller telle quelle |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://<ton-service>.onrender.com` |
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST` | host interne de la base Postgres Render |
| `DB_PORT` | `5432` |
| `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | ceux de la base Postgres Render |
| `SESSION_DRIVER` | `file` (ou `database` si tu veux les sessions en base) |
| `CACHE_STORE` | `file` |
| `LOG_CHANNEL` | `stderr` (Render capture les logs sur stdout/stderr) |

Render fournit automatiquement `PORT` — l'entrypoint l'utilise déjà (`docker-entrypoint.sh`), rien à faire.

### 4. Stockage des images de couverture — point d'attention ⚠️

**Le système de fichiers d'un service Render est éphémère** : à chaque redéploiement, tout ce qui a été écrit dans `storage/app/public` (les images uploadées) est perdu. Deux options :
- **Simple** : ajouter un **Disk** Render (onglet *Disks*), monté sur `/var/www/html/storage/app/public`. Les fichiers survivent aux redéploiements, mais restent limités à cette seule instance (pas de scaling horizontal).
- **Plus robuste** : passer à un stockage S3-compatible (Cloudflare R2, AWS S3) via `FILESYSTEM_DISK=s3` — nécessite d'ajouter le package `league/flysystem-aws-s3-v3` (à valider avant de l'ajouter aux dépendances).

Pour un projet de démonstration/portfolio, l'option Disk suffit largement.

### 5. Vérifications déjà faites (par Claude, en local)
- ✅ Build Docker complet (assets Tailwind + dépendances PHP + image finale PHP 8.4)
- ✅ Démarrage du conteneur contre une vraie base Postgres : migrations exécutées sans erreur
- ✅ `GET /`, `/events`, `/api/events`, `/login` → 200
- ✅ CSS Tailwind servi correctement
- ✅ Inscription + connexion réelles
- ✅ Création d'un événement avec upload d'image → fichier stocké et servi (`/storage/covers/...`) → 200
