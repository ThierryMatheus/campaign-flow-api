# CampaignFlow API

REST API for political campaign and mandate office management.

Technical portfolio project focused on demonstrating:

- Robust Laravel API development
- Database modeling and DBA practices
- Clean architecture (Policies, Form Requests, API Resources, Services, Jobs)
- Advanced Laravel features (Sanctum, Enums, Queues, Cache)
- Automated testing with Pest
- Performance measurement (Telescope, EXPLAIN, MySQL × PostgreSQL)
- Async reporting with optional Python generation (CSV, charts, ZIP)

## Stack

- Laravel 12
- PHP 8.3+
- MySQL 8 (schema compatible with PostgreSQL)
- PostgreSQL 16 (optional comparison environment)
- Laravel Sanctum
- Redis (cache + queues)
- Pest (feature tests)
- Docker
- Python 3 (optional report worker scripts)
- Scramble (API docs)
- Spatie Activitylog (audit)

## Current status

Core product features are in place. Development continues incrementally.

## Modules

- [x] Authentication (register, login, logout, me)
- [x] Multi-workspace + roles (pivot)
- [x] Voters (filters)
- [x] Teams (hierarchical)
- [x] Field activities
- [x] Demands (mandate mode)
- [x] Agenda
- [x] Transactions (donations & expenses)
- [x] Dashboard summary (cached)
- [x] API documentation (Scramble at /docs/api)
- [x] Audit log (Spatie)
- [x] Performance baseline + MySQL × PostgreSQL comparison
- [x] Async reports (queue job + download)
- [x] Python report generation (CSV + matplotlib chart + ZIP)

## Performance

### Test dataset

- 50,000 voters + related modules
- Seeder: php artisan db:seed --class=PerformanceSeeder

### Baseline (Telescope, local)

| Endpoint                            | MySQL      | PostgreSQL |
| ----------------------------------- | ---------- | ---------- |
| GET /voters?status=supporter        | ~200–260ms | ~379ms     |
| GET /voters?search=...              | ~180–290ms | ~193ms     |
| GET /dashboard/summary (cache miss) | ~350–440ms | ~265ms     |
| GET /dashboard/summary (cache hit)  | ~191ms     | ~172ms     |

### Optimizations

- Composite indexes on high-traffic filters (workspace_id + status/type/date)
- Redis cache on dashboard summary (TTL 60s)

### Notes

- Schema is MySQL/PostgreSQL compatible
- MySQL EXPLAIN on status filter uses voters_workspace_id_status_index (type=ref)
- Leading-wildcard LIKE %term% is index-unfriendly on both engines
- Numbers are single-machine with Telescope enabled — relative baseline, not a production SLA

## Reports

Reports are asynchronous:

1. POST /api/reports — enqueue generation (pending → processing → ready / failed)
2. GET /api/reports/{id} — status
3. GET /api/reports/{id}/download — file download

Queue:

- QUEUE_CONNECTION=redis
- php artisan queue:work

Python generation (optional):

- REPORTS_USE_PYTHON=true in .env

When enabled, the worker builds data in Laravel, calls reports-python/generate_report.py, writes CSV, for dashboard also writes a matplotlib bar chart PNG, then packs CSV + PNG into a ZIP.

Fallback when REPORTS_USE_PYTHON=false: CSV generated in PHP only (tests/CI).

Manual Python POC (optional):

1. cd reports-python
2. python -m venv venv
3. Activate venv (Windows: venv\Scripts\activate)
4. python -m pip install requests matplotlib
5. Start API (and worker if testing jobs)
6. python dashboard_report.py

Do not commit venv/ or generated export files.

## Getting started

1. Clone the repository
2. Copy .env.example to .env
3. docker compose up -d
4. composer install
5. php artisan key:generate
6. php artisan migrate
7. Optional: php artisan db:seed --class=PerformanceSeeder
8. php artisan queue:work (separate terminal when using Redis queue)
9. php artisan serve

API docs: http://127.0.0.1:8000/docs/api

## Running tests

php artisan test

Feature tests use QUEUE_CONNECTION=sync so they do not require a running worker.

## Author

Thierry Matheus  
Backend Developer (Laravel) transitioning to Database Administration.  
Open to remote opportunities and relocation (especially Europe).
