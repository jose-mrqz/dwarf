# Dwarf

A URL shortening application with a Laravel backend API and React frontend.

## Project Structure

```
dwarf/
├── dwarf-back/     # Laravel API backend
└── dwarf-front/    # React + TypeScript frontend
```

## Quick Start

Quick start instructions for installation and configuration can be found in `README.md` inside `dwarf-back` or `dwarf-front`.

### Prerequisites

- **Backend**: PHP ≥ 8.2, Composer ≥ 2.x, Node.js, Xdebug (for coverage)
- **Frontend**: Node.js ≥ 18, pnpm ≥ 8
- **Docker**: Docker + Docker Compose v2 (for containerized deployment)


### Production deployment

- Both services can be deployed using Docker Compose (see `README.md` in `dwarf-back` or `dwarf-front`).
- Deployed services are live in : `https://back.ghoul.ch` and `https://front.ghoul.ch`.

### Environment Configuration

- **Backend**: Configure database and app settings in `dwarf-back/.env`
- **Frontend**: Set API URLs in `dwarf-front/.env` (variables must be prefixed with `VITE_`)

## Features

- URL shortening service
- RESTful API with Swagger documentation
- Modern React frontend with TypeScript
- Comprehensive test coverage
- Docker containerization
- Production deployment