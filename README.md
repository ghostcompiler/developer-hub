<p align="center">
  <img src="https://res.cloudinary.com/djgvfl1tv/image/upload/v1780666791/logo_mqnqn4.png" alt="Developer Hub" width="180">
</p>

<h1 align="center">Developer Hub</h1>

<p align="center">
  An open-source platform to showcase GitHub repositories, publish developer blogs, and boost discoverability through SEO-optimized project pages, sitemaps, and <code>llms.txt</code> — built for developers who want their work indexed, linked, and shared.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Vite-8-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/SEO-Optimized-22C55E?style=for-the-badge" alt="SEO">
  <img src="https://img.shields.io/badge/Built%20By-Ghost%20Compiler-0F172A?style=for-the-badge" alt="Ghost Compiler">
</p>

---

## About

**Developer Hub** is an open-source platform for developer visibility. It syncs GitHub repositories into rich, crawlable documentation pages and gives registered users a dashboard to submit blogs, link external repos, and share suggestions — all moderated through an admin approval workflow.

Whether you are publishing SDK docs, listing community projects, or growing organic traffic to your GitHub profile, Developer Hub handles the SEO layer so your repositories and writing get found.

## Features

- **GitHub repo sync** — Automatically pulls repositories, READMEs, file trees, and metadata from a GitHub profile via `github:sync`
- **SEO-first project pages** — Structured project pages with code explorer, schema markup, `sitemap.xml`, and `llms.txt` for search engines and AI crawlers
- **Community blogs** — Users can register, write Markdown posts, and publish after admin approval
- **Linked repositories** — Submit and showcase external GitHub repos alongside official projects
- **User dashboard** — Manage profile, blogs, linked repos, and API tokens
- **Admin command center** — Approve submissions, manage users, and configure site settings
- **Authentication** — Email verification, password reset, social login (OAuth), and two-factor authentication
- **REST API** — Sanctum-powered API for linked repo integrations

## Requirements

- PHP 8.3+
- Composer 2
- Node.js 18+ and npm
- SQLite (default) or MySQL / PostgreSQL

## Installation

```bash
# Clone the repository
git clone https://github.com/ghostcompiler/ghostcompiler.git
cd ghostcompiler

# Install dependencies and set up the app
composer setup
```

Or step by step:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

### Environment

Copy `.env.example` to `.env` and configure at minimum:

| Variable | Description |
|---|---|
| `APP_NAME` | Application name (e.g. `Developer Hub`) |
| `APP_URL` | Public URL of your deployment |
| `DB_CONNECTION` | Database driver (`sqlite` by default) |
| `GITHUB_TOKEN` | GitHub personal access token for repo sync |

### Sync GitHub repositories

```bash
php artisan github:sync
```

### Development server

```bash
composer dev
```

This starts the Laravel server, queue worker, log tail, and Vite dev server concurrently.

## Project structure

| Path | Purpose |
|---|---|
| `app/Http/Controllers/` | Web and API controllers |
| `app/Console/Commands/` | `github:sync`, project tree caching |
| `app/Models/` | Projects, blogs, linked repos, users |
| `resources/views/` | Blade templates for public and dashboard UI |
| `routes/web.php` | Public routes, auth, dashboard, SEO endpoints |
| `routes/api.php` | Sanctum API routes |

## SEO endpoints

| URL | Description |
|---|---|
| `/sitemap.xml` | Dynamic sitemap for projects, blogs, and repo files |
| `/llms.txt` | Machine-readable site summary for AI crawlers |
| `/robots.txt` | Crawler directives with sitemap reference |

## Testing

```bash
composer test
```

---

## License

This project is open-sourced under the MIT license.
