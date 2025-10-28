# Dwarf Backend README.md

## Requirements

* PHP ≥ 8.2
* Composer ≥ 2.x
* Laravel framework (installed via Composer)
* Xdebug (enable for test coverage)
* Node.js with npm

## Install

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
```

Set database vars in `.env` (defaults to sqlite).

## Commands

```bash
php artisan migrate
npm install && npm run build
php artisan l5-swagger:generate # http://localhost:8000/api/documentation
php artisan serve               # http://localhost:8000
```

## Tests and Coverage

```bash
php artisan test            # to run tests
php artisan test --coverage # for html report in ./coverage
```

## Production (Docker)

Requirements:

* Docker
* Docker Compose v2
* Production `.env` file with correct settings (e.g., `APP_ENV=production`, `APP_DEBUG=false`)

Example flow:

```bash
# build and start
docker compose up -d --build

# first‑time bootstrap
docker compose exec app php artisan migrate --force
```

Notes:

* Application server is Frankenphp, so in production it is recommended to setup a load balancer.
* API documentation available after running `php artisan l5-swagger:generate` in `/api/documentation`.