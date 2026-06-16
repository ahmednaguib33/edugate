# EduGate API

Backend API for **EduGate** — a platform that helps Arab and Gulf students
study at **accredited Egyptian universities**. It powers the EduGate designs: a
bilingual (Arabic / English) catalog of universities, faculties and degree
programs (Bachelor / Master / PhD), student accounts, an application/lead
pipeline, and a back-office admin area.

Built with **Laravel 13**, **JWT authentication**, and an **SQLite** database by
default (swappable to MySQL/PostgreSQL).

---

## Features

- **Public catalog** — browse universities, faculties and programs with
  filtering (degree level, faculty, university, tuition range, language),
  full-text search and sorting.
- **Bilingual content** — every catalog entity exposes English & Arabic fields.
- **JWT auth** — student registration, login, profile, token refresh/logout.
- **Applications** — students submit and track applications; each gets a unique
  application number (`EG-YYYY-XXXXXX`).
- **Admin / back-office** — dashboard stats, full catalog CRUD, and an
  application pipeline with status management and agent assignment.
- **Role-based access** — `student`, `agent`, `admin`.
- **OpenAPI / Swagger UI** at `/docs`.
- **Feature test suite** (24 tests).

---

## Tech stack

| Concern        | Choice                                   |
| -------------- | ---------------------------------------- |
| Framework      | Laravel 13 (PHP 8.2+)                     |
| Auth           | JWT (`php-open-source-saver/jwt-auth`)    |
| Database       | SQLite (default) · MySQL / PostgreSQL    |
| API docs       | OpenAPI 3.0 + Swagger UI                  |
| Code style     | Laravel Pint                             |

---

## Requirements

- PHP **8.2+** with `pdo_sqlite` (and `mbstring`, `openssl`, `tokenizer`)
- Composer

---

## Getting started

```bash
# 1. Install dependencies
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate
php artisan jwt:secret          # generates JWT_SECRET

# 3. Database (SQLite by default)
touch database/database.sqlite
php artisan migrate --seed

# 4. Run
php artisan serve               # http://localhost:8000
```

Open **http://localhost:8000/docs** for interactive API documentation.

### Using MySQL/PostgreSQL instead

Edit `.env`:

```dotenv
DB_CONNECTION=pgsql            # or mysql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=edugate
DB_USERNAME=postgres
DB_PASSWORD=secret
```

---

## Seeded demo accounts

After `php artisan migrate --seed`:

| Role    | Email                 | Password   |
| ------- | --------------------- | ---------- |
| Admin   | `admin@edugate.test`  | `password` |
| Agent   | `agent@edugate.test`  | `password` |
| Student | `student@edugate.test`| `password` |

The seeders also load **6 universities**, **22 faculties** and **43 programs**
(21 Bachelor + 11 Master + 11 PhD) derived from the EduGate design cards.

---

## Authentication

```bash
# Login → returns { access_token, token_type, expires_in, user }
curl -X POST http://localhost:8000/api/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"admin@edugate.test","password":"password"}'

# Use the token
curl http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer <access_token>"
```

---

## API overview

Base path: `/api`

### Auth
| Method | Endpoint          | Access  |
| ------ | ----------------- | ------- |
| POST   | `/auth/register`  | public  |
| POST   | `/auth/login`     | public  |
| GET    | `/auth/me`        | auth    |
| POST   | `/auth/logout`    | auth    |
| POST   | `/auth/refresh`   | auth    |

### Catalog (public)
| Method | Endpoint                | Notes |
| ------ | ----------------------- | ----- |
| GET    | `/universities`         | search, paginate |
| GET    | `/universities/{slug}`  | |
| GET    | `/faculties`            | |
| GET    | `/faculties/{slug}`     | |
| GET    | `/programs`             | filter: `degree_level`, `faculty`, `university`, `language`, `min_tuition`, `max_tuition`, `featured`, `q`, `sort` |
| GET    | `/programs/{slug}`      | |

### Leads & applications
| Method | Endpoint               | Access  | Notes |
| ------ | ---------------------- | ------- | ----- |
| POST   | `/leads`               | public  | Capture a prospective student (no account needed) |
| GET    | `/applications`        | student | List my applications |
| POST   | `/applications`        | student | Submit an application |
| GET    | `/applications/{id}`   | student | View my application |

> A **public lead** (`POST /leads`) and a **student application** (`POST /applications`)
> both create an `Application`; leads have no `user_id` and `source: website`,
> student applications have `source: portal`. The admin pipeline manages both.

### Admin (`/admin`, role: admin/agent)
| Method | Endpoint                       | Access |
| ------ | ------------------------------ | ------ |
| GET    | `/admin/dashboard/stats`       | admin/agent |
| GET    | `/admin/applications`          | admin/agent |
| GET    | `/admin/applications/{id}`     | admin/agent |
| PATCH  | `/admin/applications/{id}`     | admin/agent |
| DELETE | `/admin/applications/{id}`     | admin |
| GET    | `/admin/students`              | admin/agent |
| GET    | `/admin/students/{id}`         | admin/agent |
| —      | `/admin/universities` (CRUD)   | admin |
| —      | `/admin/faculties` (CRUD)      | admin |
| —      | `/admin/programs` (CRUD)       | admin |

Full request/response schemas: [`docs/openapi.yaml`](docs/openapi.yaml) or `/docs`.

---

## Data model

```
User (role: admin|agent|student)
 ├─ applications  (as student)
 └─ assignedApplications (as agent)

University ──< program_university >── Program >── Faculty
                                       │
Application ── Program, ── University (preferred), ── User (student), ── User (agent)
```

- **University** — accredited Egyptian university (Cairo, Ain Shams, …).
- **Faculty** — discipline (Medicine, Engineering, Law, …).
- **Program** — a `(faculty + degree level)` offering with tuition range,
  minimum admission rate, duration and the universities that provide it.
- **Application** — a student's application to a program (the sales lead),
  with status pipeline and agent assignment.

---

## Testing

```bash
php artisan test
```

Runs against an in-memory SQLite database (24 feature tests covering auth,
catalog, applications and the admin area).

---

## Code style

```bash
vendor/bin/pint
```
