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

- 50,000 voters
- 5,000 field activities
- 2,000 demands
- 1,000 transactions
- 500 agenda items

Seeder: `php artisan db:seed --class=PerformanceSeeder`

### Baseline (Telescope, local)

| Endpoint                          | Duration   |
| --------------------------------- | ---------- |
| GET /voters?status=supporter      | ~200–260ms |
| GET /voters?search=...            | ~180–290ms |
| GET /dashboard/summary (no cache) | ~350-440ms |

### Optimization

- Composite indexes on high-traffic filters (`workspace_id` + status/type/date)
- Redis cache on dashboard summary (TTL 60s)

| Endpoint               | After (cache hit) |
| ---------------------- | ----------------- |
| GET /dashboard/summary | ~191ms            |

### Notes

- `EXPLAIN` on status filter uses `voters_workspace_id_status_index` (`type=ref`)
- `LIKE %term%` search cannot efficiently use B-tree indexes (known limitation)

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

**Next**

- [ ] MySQL × PostgreSQL performance comparison
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
