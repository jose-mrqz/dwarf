# Dwarf Frontend README.md

## 1) Requirements

* Node.js ≥ 18
* npm
* **pnpm** ≥ 8 (`npm i -g pnpm`)
* (For containers) Docker + Docker Compose v2

## 2) Install & Run (local)

```bash
cp .env.example .env
pnpm install
pnpm dev          # http://localhost:5173
```

## 3) Build & Preview (local)

```bash
pnpm build        # outputs to dist/
pnpm preview      # serves dist on http://localhost:4173
```

## 4) Scripts (reference)

```json
"scripts": {
  "dev": "vite",
  "build": "tsc -b && vite build",
  "format": "prettier --write .",
  "format:check": "prettier --check .",
  "preview": "vite preview"
}
```

## 5) Environment file

Create `.env` from the example and adjust URLs.

```
# .env.example
DWARF_API_BASE_URL=http://localhost:8000/api
DWARF_API_BASE_URL=http://localhost:8000
```

Vite exposes only variables prefixed with `VITE_`.

## 6) Docker Compose

A `docker-compose.yml` is provided to build and run the production image.


### Build and run container

```bash
# Uses .env for build
docker compose up --build -d
```

### Notes

* Ensure `.env` exists with the proper URLs.
* For a production environment, the app is built and served using `serve` (`npm install -g serve`).
