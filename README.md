# POS (Laravel + Livewire)

Production-grade Point of Sale application built with Laravel 12, Livewire 4, and Tailwind.

## Requirements

- PHP 8.2+
- Composer 2+
- Node.js 18+
- MySQL/MariaDB (recommended for production)
- Redis (recommended for cache/queue/session in production)

## Local Development Setup

1. Install backend dependencies:

```bash
composer install
```

2. Install frontend dependencies:

```bash
npm install
```

3. Create env file and app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env`, then migrate:

```bash
php artisan migrate --force
```

5. Run development services:

```bash
composer run dev
```

## Production Deployment Checklist

1. Use a dedicated production env file (see `.env.production.example`) and set:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL` to your public HTTPS URL
- `APP_FORCE_HTTPS=true`
- Real database, mail, cache, queue, and session backends

2. Install dependencies with optimized autoloading:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
```

3. Run database migrations safely:

```bash
php artisan migrate --force
```

4. Cache framework metadata:

```bash
php artisan optimize
```

5. Ensure queue workers are running (example):

```bash
php artisan queue:work --sleep=1 --tries=3 --max-time=3600
```

6. Configure scheduler (crontab):

```bash
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

7. Ensure writable permissions for:

- `storage/`
- `bootstrap/cache/`

8. Rotate logs and monitor failures:

- Monitor `storage/logs/`
- Review failed jobs with `php artisan queue:failed`

## Security Baseline

This project now includes:

- Global HTTP security headers middleware:
    - `X-Content-Type-Options: nosniff`
    - `X-Frame-Options: SAMEORIGIN`
    - `Referrer-Policy: strict-origin-when-cross-origin`
    - `Permissions-Policy` for camera/microphone/geolocation/payment
    - `Strict-Transport-Security` on secure requests
- Optional forced HTTPS URL generation via `APP_FORCE_HTTPS=true`

## Operational Commands

- CI workflow (required merge gate candidate):

```bash
.github/workflows/ci.yml
```

- PR checklist gate workflow:

```bash
.github/workflows/pr-checklist.yml
```

- Security workflow:

```bash
.github/workflows/security.yml
```

- Secret scanning workflow:

```bash
.github/workflows/secret-scan.yml
```

- Workflow linting gate:

```bash
.github/workflows/workflow-lint.yml
```

- CODEOWNERS lint gate:

```bash
.github/workflows/codeowners-lint.yml
```

- Dependabot configuration:

```bash
.github/dependabot.yml
```

- Code ownership policy:

```bash
.github/CODEOWNERS
```

- Supervisor templates:

```bash
deploy/supervisor/laravel-queue-worker.conf
deploy/supervisor/laravel-scheduler.conf
```

- Full operations runbook:

```bash
docs/operations.md
```

- Clear and rebuild caches:

```bash
php artisan optimize:clear
php artisan optimize
```

- Run tests:

```bash
composer test
```

- Inspect queue failures:

```bash
php artisan queue:failed
php artisan queue:retry all
```

## Recommended Pre-Sale Validation

Before selling/deploying to customers:

1. Run full feature test suite and manual POS flow testing (sale, refund/void workflow if present, receipt print, shift close).
2. Validate role-based access across all management routes.
3. Verify backups for DB and uploaded files.
4. Run a staging environment smoke test with production-like settings.

## License

This project follows the license declared in `composer.json`.
