# Energy Providers

A PHP application for publishing and ranking energy providers: listings, articles, comments, and site-wide settings backed by MySQL. The front end uses [Smarty](https://www.smarty.net/) templates, Bootstrap-based styling, and Apache-friendly URL rewriting.

---

## Table of contents

- [Features](#features)
- [Tech stack](#tech-stack)
- [Requirements](#requirements)
- [Quick start](#quick-start)
- [Configuration](#configuration)
- [Database](#database)
- [Local development](#local-development)
- [Deployment](#deployment)
- [Troubleshooting](#troubleshooting)
- [Repository](#repository)

---

## Features

- **Provider directory** — Submissions ranked by score and activity; dedicated routes for full listings and newest updates.
- **Content & comments** — Article-style pages with optional comment threads.
- **Multi-site settings** — `web_settings` keyed by install path or hostname for branding, contact info, and footer links.
- **Admin-style receiver** — POST endpoint for updating site configuration (protect in production as your deployment requires).
- **HTTPS-first** — Optional strict HTTPS redirect via `.htaccess` and `lib/require_https.php`, with documented escape hatches for local dev and reverse proxies.

---

## Tech stack

| Layer        | Technology                          |
| ------------ | ----------------------------------- |
| Runtime      | PHP 7.4+ (8.x recommended)          |
| Database     | MySQL 8.0+ (utf8mb4)                |
| Templates    | Smarty (bundled under `libs/`)      |
| Web server   | Apache (`mod_rewrite`) or PHP built-in server |

---

## Requirements

- PHP extensions: **PDO**, **pdo_mysql**, typical session/iconv/mbstring usage as required by your host.
- MySQL user with **ALL PRIVILEGES** on the application database.
- For production: Apache with `AllowOverride` so `.htaccess` rewrite and HTTPS rules apply (or equivalent nginx rules).

---

## Quick start

1. **Clone the repository**

   ```bash
   git clone https://github.com/abbasrezaey1/energy-providers.git
   cd energy-providers
   ```

2. **Environment file**

   ```bash
   cp .env.example .env
   ```

   Edit `.env` and set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` to match your MySQL database.

3. **Import the schema**

   Create an empty database, grant your app user full access, then import the main dump (see [Database](#database)).

4. **Point the web root** at this folder (or a subfolder — then set `BASE_PATH` in `.env`).

---

## Configuration

Environment variables are loaded from `.env` (see `.env.example`).

| Variable | Description |
| -------- | ----------- |
| `APP_DEBUG` | When `true`, surfaces more diagnostic output. Use **only** while debugging; turn off on public sites. |
| `DB_HOST` | MySQL host (often `localhost` on shared hosting). |
| `DB_NAME` | Database name. |
| `DB_USER` / `DB_PASSWORD` | Credentials. Quote passwords that contain `#` or spaces. |
| `BASE_PATH` | URL segment after the domain with **no** leading or trailing slashes. Empty = site at domain root; e.g. `energy-providers` for `https://example.org/energy-providers/`. |
| `SITE_ADDRESS`, `SITE_EMAIL`, `SITE_TEL` | Fallback contact details when `web_settings` has no address/email/tel. |
| `FORCE_HTTP` | Local dev: set to `true` to allow HTTP and avoid HTTPS redirect loops. |
| `HTTPS_TRUST_PROXY` | Set when TLS terminates at Cloudflare or a load balancer and you see redirect issues. |

**Debug without `.env`:** create an empty file named `DEBUG_ON` next to `index.php` (remove when finished). See `lib/debug_bootstrap.php` for behavior.

---

## Database

- **Primary schema:** import `xafh7070_webs.sql` into your database. The file header includes example `CREATE DATABASE` / `GRANT` / `mysql` import commands.
- **Optional SQL** under `sql/`:
  - `seed_energy_providers_demo.sql` — demo data where applicable.
  - `migrate_submissions_energy_columns.sql`, `patch_provider_images.sql`, `cleanup_legacy_dummy_submissions.sql` — use when upgrading or cleaning an existing install (review each file before running).

The application resolves `web_id` from `BASE_PATH` or from the request host when installed at the site root (`index.php` / `lib/config.php`).

---

## Local development

**PHP built-in server** (uses `router.php` as front controller):

```bash
php -S localhost:8080 router.php
```

Then open `http://localhost:8080/`. If HTTPS redirects interfere, set `FORCE_HTTP=true` in `.env` for local use only.

**Apache:** ensure `mod_rewrite` is enabled and document root points at the project directory. Adjust `RewriteBase` in `.htaccess` if the app lives in a subdirectory (often set to `/your-subfolder/`).

---

## Deployment

- Upload the project (excluding `.env` from version control — it is gitignored; create it on the server).
- Set correct `BASE_PATH` for subdirectory installs.
- On cPanel-style hosts: create the MySQL database and user, **Add User To Database** with all privileges, then put the same values in `.env` (not in `.htaccess`).
- After go-live, keep `APP_DEBUG=false` and remove `DEBUG_ON` if present.

---

## Troubleshooting

| Symptom | Things to check |
| ------- | ---------------- |
| Blank page or 503 | Database credentials in `.env`; MySQL user attached to the correct database. Error `1045` is usually wrong user/password or missing grant. |
| Redirect loop (HTTPS) | `HTTPS_TRUST_PROXY=true` behind a proxy; or temporarily `FORCE_HTTP=true` locally. |
| Wrong base URL / assets | `BASE_PATH` must match the URL path segment; no slashes in the value. |
| Smarty compile errors | Ensure `templates_c/` is writable by the web server (compiled templates are ignored by git except the folder pattern in `.gitignore`). |

---

## Repository

**Upstream:** [github.com/abbasrezaey1/energy-providers](https://github.com/abbasrezaey1/energy-providers)

```bash
git remote add origin https://github.com/abbasrezaey1/energy-providers.git
git push -u origin main
```

Contributions and issues are welcome via GitHub.
