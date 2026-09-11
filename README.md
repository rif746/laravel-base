# Antigravity Architecture: Laravel Modular Monolith

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://www.php.net)
[![Vite](https://img.shields.io/badge/Vite-8.x-646CFF?logo=vite&logoColor=white)](https://vitejs.dev)
[![Pest](https://img.shields.io/badge/Pest-4.x-FF6B6B?logo=pest&logoColor=white)](https://pestphp.com)

This application is built using a strict **Pragmatic Domain-Driven Design (DDD)** architecture. We refer to this as the **"Antigravity"** architecture because it prevents the codebase from collapsing under its own weight as it scales. A hard physical boundary is enforced between the **Delivery Layer** (HTTP, Livewire) and the **Domain** (Business Logic, Actions, DTOs), ensuring the core remains framework-agnostic, testable, and maintainable at any scale.

---

## Quick Start

```bash
# 1. Clone the repository
git clone <repo-url>
cd laravel-base

# 2. Run the automated setup
# Installs PHP/JS deps, creates .env, generates key, and runs migrations
composer setup

# 3. Start the development environment
# Runs Server + Queue + Logs + Vite concurrently
composer dev
```

The application will be available at `http://localhost:8000`.

---

## Table of Contents

| # | Document | Description |
|---|---|---|
| 1 | [Getting Started](./docs/01-getting-started.md) | Technical Stack, Requirements, Setup, Commands, Env Vars |
| 2 | [Architecture & Philosophy](./docs/02-architecture-philosophy.md) | Core Philosophy, Directory Structure, Naming Rules, Verb Matrix |
| 3 | [Domain Rules](./docs/03-domain-rules.md) | DTOs, Actions, Events, Audit Logging, System State |
| 4 | [System Services](./docs/04-system-services.md) | Universal File Management, Dynamic UIs, Excel Import/Export |
| 5 | [Development Tools](./docs/05-development-tools.md) | Generator commands (`domain:make`), Stub Customization, Pest Testing |
| 6 | [AI Agent Instructions](./docs/06-ai-agent-instructions.md) | A standalone system prompt to paste into AI tools working on this repo |
| 7 | [Feature Cookbook](./docs/07-feature-cookbook.md) | Practical step-by-step guides for using the core features |

---

## License

This project is licensed under the **MIT License**.
