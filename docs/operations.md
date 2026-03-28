# Operations Runbook

This runbook covers the minimum production operations baseline for this POS application.

## 1) CI And Merge Gates

CI workflow file:

- `.github/workflows/ci.yml`
- `.github/workflows/security.yml`
- `.github/workflows/secret-scan.yml`
- `.github/workflows/workflow-lint.yml`
- `.github/workflows/codeowners-lint.yml`
- `.github/CODEOWNERS`

CI includes three required jobs:

- `Lint (Pint)`
- `Tests (Laravel)`
- `Frontend Build (Vite)`

Security workflow checks to require:

- `Dependency Review`
- `CodeQL Analysis (php)`
- `CodeQL Analysis (javascript)`
- `Secret Scan (Gitleaks)`

Branch protection recommendation for `main`:

1. Enable pull request reviews.
2. Require review from Code Owners.
3. Require status checks to pass before merging.
4. Add required checks:
    - `Lint (Pint)`
    - `Tests (Laravel)`
    - `Frontend Build (Vite)`
    - `Validate PR Checklist`
    - `Workflow Lint (actionlint)`
    - `Validate CODEOWNERS`
    - `Dependency Review`
    - `CodeQL Analysis (php)`
    - `CodeQL Analysis (javascript)`
    - `Secret Scan (Gitleaks)`
5. Require branch to be up to date before merging.
6. Disable direct pushes to `main`.

Code ownership policy:

- `.github/CODEOWNERS`

Protect sensitive paths (workflows, deploy, migrations, config, providers) through mandatory Code Owner review.

Pull request quality template:

- `.github/pull_request_template.md`
- `.github/workflows/pr-checklist.yml`

Require contributors to complete the CI gate checklist in the PR body before review/merge.
Add `Validate PR Checklist` from this workflow as a required branch protection check.

If you use Flux private packages, configure repository secrets:

- `FLUX_USERNAME`
- `FLUX_LICENSE_KEY`

## 2) Supervisor Process Templates

Template files:

- `deploy/supervisor/laravel-queue-worker.conf`
- `deploy/supervisor/laravel-scheduler.conf`

Install on server (Ubuntu example):

```bash
sudo apt-get update
sudo apt-get install -y supervisor
sudo cp deploy/supervisor/laravel-queue-worker.conf /etc/supervisor/conf.d/
sudo cp deploy/supervisor/laravel-scheduler.conf /etc/supervisor/conf.d/
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start pos-queue-worker:*
sudo supervisorctl start pos-scheduler
sudo supervisorctl status
```

Common controls:

```bash
sudo supervisorctl restart pos-queue-worker:*
sudo supervisorctl restart pos-scheduler
sudo supervisorctl tail -f pos-queue-worker:pos-queue-worker_00
sudo supervisorctl tail -f pos-scheduler
```

## 3) Release Procedure

1. Ensure CI is green on the release commit.
2. Pull latest code on server.
3. Install dependencies and build assets:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
```

4. Run migrations:

```bash
php artisan migrate --force
```

5. Refresh caches:

```bash
php artisan optimize:clear
php artisan optimize
```

6. Restart long-running processes:

```bash
sudo supervisorctl restart pos-queue-worker:*
sudo supervisorctl restart pos-scheduler
```

## 4) Runtime Checks

Run after each deployment:

```bash
php artisan about
php artisan queue:failed
php artisan schedule:list
```

Logs to monitor:

- `storage/logs/laravel.log`
- `storage/logs/supervisor-queue-worker.log`
- `storage/logs/supervisor-scheduler.log`

## 5) Rollback (Simple)

1. Checkout previous known-good release.
2. Re-run dependency install and build commands.
3. If migration rollback is required, execute only reviewed rollback steps.
4. Restart supervisor programs.

Keep DB backups before each production migration.
