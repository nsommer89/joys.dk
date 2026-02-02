# Joys.dk

Velkommen til **Joys.dk** – en moderne medlemsplatform bygget med Laravel, Livewire og Tailwind CSS.

## 🚀 Teknologier

- **Framework**: [Laravel 12+](https://laravel.com)
- **Frontend**: [Livewire 4](https://livewire.laravel.com), [Alpine.js](https://alpinejs.dev) og [Tailwind CSS](https://tailwindcss.com)
- **Admin**: [Filament v5](https://filamentphp.com)
- **Billedbehandling**: [Intervention Image v3](https://image.intervention.io) (med understøttelse af optimering af profilbilleder)
- **Rettigheder**: [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- **Queue/Workers**: [Laravel Horizon](https://laravel.com/docs/horizon)
- **Deployment**: [Deployer](https://deployer.org)

## 🛠 Installation

### Systemkrav
- PHP 8.2 eller højere
- Node.js & NPM
- SQLite (standard) eller MySQL/PostgreSQL

### Lokal udvikling med Laravel Sail
1. Klone projektet:
   ```bash
   git clone git@github.com:nsommer89/joys.dk.git
   cd joys.dk
   ```
2. Installer PHP dependencies:
   ```bash
   composer install
   ```
3. Opsæt miljø:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Kør Sail og migrationer:
   ```bash
   ./vendor/bin/sail up -d
   ./vendor/bin/sail artisan migrate --seed
   ```
5. Installer frontend dependencies og kør build:
   ```bash
   npm install
   npm run build
   ```

## ✨ Funktioner

- **Medlemsområde**: Komplet Dashboard, Udforsk, Beskeder og Vennelister.
- **Profilredigering**: State-of-the-art profilredigering med "sticky" gem-funktion, real-time avatar opdatering og baggrunds-optimering af billeder.
- **Offentlige Profiler**: Flotte, responsive profilvisninger med understøttelse af kønsspecifikke ikoner og præferencer.
- **Floating Save Bar**: En smart, flydende bjælke der kun viser sig når der er ugemte ændringer.
- **Toast Notifikationer**: Top-placerede, responsive notifikationer for bedre UX.

## 📦 Scripts

Vi har inkluderet nogle praktiske scripts i `composer.json`:

- `composer run setup`: Komplet opsætning af projektet (install, migrate, build).
- `composer run dev`: Starter udviklingsserver, queue worker, logs og vite samtidigt.
- `composer run test`: Kører alle PHPUnit tests.

## 📄 Licens
Dette projekt er privat og ejes af Niko Sommer.
