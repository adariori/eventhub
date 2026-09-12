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
