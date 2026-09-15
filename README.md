**CampaignFlow API**

REST API for political campaign and mandate office management.

Technical portfolio project focused on demonstrating:

- Robust Laravel API development
- Database modeling and DBA best practices
- Clean architecture
- Advanced Laravel features (Sanctum, Policies, Form Requests, API Resources, Enums)
- Automated testing with Pest
- Future performance comparison between MySQL and PostgreSQL

**Stack**

- Laravel 12
- PHP 8.3+
- MySQL 8 (schema fully compatible with PostgreSQL)
- Laravel Sanctum
- Redis
- Pest (Feature tests)
- Docker

**Current Status**

In active development — built incrementally.

## Performance

### Test dataset

- 50,000 voters + related modules
- Seeder: `php artisan db:seed --class=PerformanceSeeder`

### Baseline (Telescope, local)

| Endpoint                            | MySQL      | PostgreSQL |
| ----------------------------------- | ---------- | ---------- |
| GET /voters?status=supporter        | ~200–260ms | ~379ms     |
| GET /voters?search=...              | ~180–290ms | ~193ms     |
| GET /dashboard/summary (cache miss) | ~350–440ms | ~265ms     |
| GET /dashboard/summary (cache hit)  | ~191ms     | ~172ms     |

### Optimizations

- Composite indexes on high-traffic filters
- Redis cache on dashboard summary (TTL 60s)

### Notes

- Schema is MySQL/PostgreSQL compatible
- `EXPLAIN` (MySQL) on status filter uses `voters_workspace_id_status_index` (`type=ref`)
- Leading-wildcard `LIKE %term%` remains index-unfriendly on both engines
- Numbers are single-machine, Telescope-on; treat as relative baseline, not production SLA

**Done**

- [x] Authentication (Register, Login, Logout, Me)
- [x] Multi-workspace foundation
- [x] Workspace CRUD + Policies
- [x] Roles system (pivot)
- [x] Enums (Type, Status, Role)
- [x] Feature tests (Auth + Workspace)
- [x] Voters module
- [x] Teams (hierarchical)
- [x] Field activities
- [x] Demands (mandate mode)
- [x] Agenda
- [x] Donations & Expenses
- [x] Dashboard
- [x] API documentation (Scribe/Scramble)
- [x] Audit log
- [x] MySQL × PostgreSQL performance comparison

**Next**

- [ ] Reports/export (async, optional Python)

**Getting Started**

1. Clone the repository
2. Copy `.env.example` to `.env`
3. Run `docker compose up -d`
4. Run `composer install`
5. Run `php artisan key:generate`
6. Run `php artisan migrate`
7. Run `php artisan serve`

**Running Tests**

`php artisan test`

**Author**

Thierry Matheus  
Backend Developer (Laravel) transitioning to Database Administration.  
Open to remote opportunities and relocation (especially Europe).

---
