# CampaignFlow API

REST API for political campaign and mandate office management.

Built as a technical portfolio project focused on:

- Robust Laravel API development
- Database modeling and DBA best practices
- Clean architecture
- Advanced Laravel features (Sanctum, Policies, Form Requests, API Resources, Enums)
- Automated testing with Pest
- Future performance comparison between MySQL and PostgreSQL

## Stack

- Laravel 12
- PHP 8.3+
- MySQL 8 (schema fully compatible with PostgreSQL)
- Laravel Sanctum
- Redis
- Pest (Feature tests)
- Docker

## Current Status

**In active development** — built incrementally.

### Done

- [x] Authentication (Register, Login, Logout, Me)
- [x] Multi-workspace foundation
- [x] Workspace CRUD + Policies
- [x] Roles system (pivot)
- [x] Enums (Type, Status, Role)
- [x] Feature tests (Auth + Workspace)

### Next

- [ ] Voters module
- [ ] Teams (hierarchical)
- [ ] Field activities
- [ ] Demands (mandate mode)
- [ ] Agenda
- [ ] Donations & Expenses
- [ ] Dashboard & Reports
- [ ] Audit log
- [ ] API documentation (Scribe/Scramble)
- [ ] MySQL × PostgreSQL performance comparison

## Getting Started

```bash
cp .env.example .env
docker compose up -d
php artisan key:generate
php artisan migrate
php artisan serve
```
