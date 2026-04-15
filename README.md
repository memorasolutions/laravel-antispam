# Laravel Anti-Spam

Invisible anti-spam middleware for Laravel 11/12 that protects forms without friction for legitimate users.

## Features

- **Four invisible protection layers**: honeypot, time trap, JS token (Alpine.js), rate limiting
- **Zero CAPTCHA**: No user friction or accessibility barriers
- **Deceptive bot responses**: Bots receive HTTP 200 (believing they succeeded)
- **Fully configurable** via `.env`
- **Multi-language**: English and French included, extensible
- **Publishable**: Config, views, and language files

## Requirements

- PHP 8.2+
- Laravel 11 or 12
- Alpine.js (for the JS token layer)

## Installation

```bash
composer require memora/laravel-antispam
```

The service provider auto-discovers. No manual registration needed.

## Usage

Apply the middleware to any POST route:

```php
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('antispam');
```

Include the honeypot partial inside your form:

```blade
<form method="POST" action="/contact">
    @csrf
    @include('antispam::partials.honeypot')
    <!-- your form fields -->
</form>
```

## Configuration

Publish the config:

```bash
php artisan vendor:publish --tag=antispam-config
```

Available `.env` variables:

| Variable | Default | Description |
|----------|---------|-------------|
| `ANTISPAM_ENABLED` | `true` | Enable/disable all protection |
| `ANTISPAM_MIN_SECONDS` | `5` | Minimum seconds before submission |
| `ANTISPAM_MAX_ATTEMPTS` | `3` | Max submissions per IP per window |
| `ANTISPAM_DECAY_SECONDS` | `3600` | Rate limit window (seconds) |

## Translations

Publish language files:

```bash
php artisan vendor:publish --tag=antispam-lang
```

Included: **en**, **fr**. Add your own in `lang/vendor/antispam/`.

## How it works

1. **Honeypot** - Hidden field positioned off-screen. Bots fill it, humans don't. Blocked submissions receive a fake HTTP 200 success.
2. **Time trap** - Encrypted timestamp on page load. Rejects if submitted in under 5 seconds (configurable).
3. **JS token** - Alpine.js copies a server-generated hash into a hidden field. Bots without JavaScript fail this check.
4. **Rate limit** - Tracks submissions per IP in cache. Returns HTTP 429 after exceeding the limit.

## Author

[MEMORA solutions](https://memora.solutions) - info@memora.ca

## License

Apache 2.0

---

# Laravel Anti-Spam (FR)

Middleware anti-spam invisible pour Laravel 11/12 qui protège les formulaires sans friction pour les utilisateurs légitimes.

## Fonctionnalités

- **Quatre couches de protection invisibles** : honeypot, piège temporel, jeton JS (Alpine.js), limitation de débit
- **Zéro CAPTCHA** : aucune friction utilisateur ni barrière d'accessibilité
- **Réponses trompeuses aux bots** : les bots reçoivent HTTP 200 (ils croient avoir réussi)
- **Entièrement configurable** via `.env`
- **Multilingue** : anglais et français inclus, extensible
- **Publiable** : configuration, vues et fichiers de langue

## Exigences

- PHP 8.2+
- Laravel 11 ou 12
- Alpine.js (pour la couche jeton JS)

## Installation

```bash
composer require memora/laravel-antispam
```

Le fournisseur de services s'enregistre automatiquement. Aucune configuration manuelle requise.

## Utilisation

Appliquez le middleware sur n'importe quelle route POST :

```php
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('antispam');
```

Incluez le partial honeypot dans votre formulaire :

```blade
<form method="POST" action="/contact">
    @csrf
    @include('antispam::partials.honeypot')
    <!-- vos champs de formulaire -->
</form>
```

## Configuration

Publiez la configuration :

```bash
php artisan vendor:publish --tag=antispam-config
```

Variables `.env` disponibles :

| Variable | Défaut | Description |
|----------|--------|-------------|
| `ANTISPAM_ENABLED` | `true` | Activer/désactiver la protection |
| `ANTISPAM_MIN_SECONDS` | `5` | Délai minimum avant soumission |
| `ANTISPAM_MAX_ATTEMPTS` | `3` | Soumissions max par IP par fenêtre |
| `ANTISPAM_DECAY_SECONDS` | `3600` | Fenêtre de limitation (secondes) |

## Traductions

Publiez les fichiers de langue :

```bash
php artisan vendor:publish --tag=antispam-lang
```

Inclus : **en**, **fr**. Ajoutez les votres dans `lang/vendor/antispam/`.

## Fonctionnement

1. **Honeypot** - Champ caché hors écran. Les bots le remplissent, les humains non. Les soumissions bloquées reçoivent un faux succès HTTP 200.
2. **Piège temporel** - Timestamp chiffré au chargement. Rejet si soumission en moins de 5 secondes (configurable).
3. **Jeton JS** - Alpine.js copie un hash serveur dans un champ caché. Les bots sans JavaScript échouent.
4. **Limitation de débit** - Compteur de soumissions par IP en cache. Retourne HTTP 429 au-delà de la limite.

## Auteur

[MEMORA solutions](https://memora.solutions) - info@memora.ca

## Licence

Apache 2.0
