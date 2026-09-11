# Getting Started

This guide covers everything you need to get the **Antigravity** Laravel Modular Monolith up and running on your local machine.

---

## 1. Technical Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8.4+ & Laravel 13.0 |
| **Frontend** | Vite, AlpineJS, Livewire, Tailwind CSS |
| **Database** | SQLite (default), MySQL, or PostgreSQL |
| **Testing** | Pest PHP |
| **Package Managers** | Composer (PHP), NPM (JS) |

---

## 2. Requirements

- **PHP:** `^8.4`
- **Node.js:** Latest LTS recommended
- **Composer:** `^2.0`
- **Extensions:** `ext-zip`, `ext-pdo_sqlite` (if using SQLite)

---

## 3. Setup & Installation

The project includes a unified setup script in `composer.json`.

```bash
# 1. Clone the repository
git clone <repo-url>
cd laravel-base

# 2. Run the automated setup
# This installs PHP/JS deps, creates .env, generates key, and runs migrations
composer setup
```

---

## 4. Running the Application

Use the pre-configured development command which runs the server, queue listener, logs, and Vite concurrently:

```bash
composer dev
```

The application will be available at `http://localhost:8000`.

---

## 5. Scripts & Commands

### Composer Scripts

| Command | Description |
|---|---|
| `composer setup` | Initial project bootstrap. |
| `composer dev` | Start development environment (Server + Queue + Logs + Vite). |
| `composer test` | Run the full test suite. |

### Artisan Commands

| Command | Description |
|---|---|
| `php artisan domain:make` | Custom generator for the DDD architecture. See [Development Tools](./05-development-tools.md). |
| `php artisan domain:new` | Scaffold a new domain. See [Development Tools](./05-development-tools.md). |
| `php artisan domain:datatable` | Generate a domain-bound DataTable and its view. See [Development Tools](./05-development-tools.md). |
| `php artisan domain:make-page` | Generate a Blade view or Livewire modal in a domain. See [Development Tools](./05-development-tools.md). |
| `php artisan system:prune-files` | Clean up orphaned database records and stranded disk files. See [System Services](./04-system-services.md). |

### NPM Scripts

| Command | Description |
|---|---|
| `npm run dev` | Start Vite dev server. |
| `npm run build` | Build assets for production. |

---

## 6. Environment Variables

Key variables used in `.env`:

| Variable | Description |
|---|---|
| `APP_NAME` | Name of the application. |
| `APP_ENV` | Application environment (`local`, `production`, etc.). |
| `APP_KEY` | Application encryption key. |
| `DB_CONNECTION` | Database driver (`sqlite`, `mysql`, `pgsql`). |
| `QUEUE_CONNECTION` | Queue driver (default: `database`). |
| `MAIL_MAILER` | Mail driver (default: `log`). |

See `.env.example` for the full list of available options.
