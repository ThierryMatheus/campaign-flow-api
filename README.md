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

**Done**

- [x] Authentication (Register, Login, Logout, Me)
- [x] Multi-workspace foundation
- [x] Workspace CRUD + Policies
- [x] Roles system (pivot)
- [x] Enums (Type, Status, Role)
- [x] Feature tests (Auth + Workspace)
- [x] Voters module

**Next**

- [ ] Teams (hierarchical)
- [ ] Field activities
- [ ] Demands (mandate mode)
- [ ] Agenda
- [ ] Donations & Expenses
- [ ] Dashboard & Reports
- [ ] Audit log
- [ ] API documentation (Scribe/Scramble)
- [ ] MySQL × PostgreSQL performance comparison

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
