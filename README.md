# Photogram

A photo-sharing web application built with PHP, MySQL, Bootstrap 5, and Docker.

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Backend | PHP 8.2, MySQL 8.0 |
| Frontend | Bootstrap 5, jQuery 3.7, Masonry, imagesLoaded |
| Date/Time | Carbon (PHP), `diffForHumans()` |
| Container | Docker, Docker Compose |
| Build | npm scripts (concat, minify, obfuscate) |
| CI/CD | GitHub Actions |

## Quick Start

### Docker (recommended)

```bash
git clone <repo-url> photogram && cd photogram
cp .env.example .env
docker compose up -d
```

**Services:**
- **App** — http://localhost:8120
- **MySQL** — localhost:3307
- **Adminer** (DB UI) — http://localhost:8080

The database schema is auto-created on first boot via `db/schema.sql`.

### Docker Hub

```bash
docker pull guruprasanth1/photogram:latest
```

### Bare Metal

```bash
git clone <repo-url> photogram && cd photogram
composer install
cp project/sampleconfig.json project/photogramconfig.json
# Edit photogramconfig.json with your DB credentials
# Point Apache DocumentRoot to htdocs/
```

## Build

```bash
cd project/npm-scripts
npm install
npm run build
```

Concatenates source CSS/JS → minifies → obfuscates JS → outputs to `htdocs/`.

## Author

**Guruprasanth M**
