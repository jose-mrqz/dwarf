# Dwarf

A URL shortening application with a Laravel backend API and React frontend.

## Project Structure

```
dwarf/
├── dwarf-back/     # Laravel API backend
└── dwarf-front/    # React + TypeScript frontend
```
Monorepo for deployment convenience.

## Quick Start

Quick start instructions for installation and configuration can be found in `README.md` inside `dwarf-back` or `dwarf-front`.

### Prerequisites

- **Backend**: PHP ≥ 8.2, Composer ≥ 2.x, Node.js, Xdebug (for coverage)
- **Frontend**: Node.js ≥ 18, pnpm ≥ 8
- **Docker**: Docker + Docker Compose v2 (for containerized deployment)


### Production deployment

- For convenience, both applications can be ran with a single command `docker compose up --build` with the `docker-compose.yml` file at the root, this will create a `dwarf-back` and a `dwarf-front` service running in Docker containers.
- Alternatively, both services can be run separately using Docker Compose (see `README.md` in `dwarf-back` or `dwarf-front` for instructions).
- Deployed services are live in : `https://back.ghoul.ch` and `https://front.ghoul.ch`, using AWS Route53, AWS ALB, AWS EC2 (`t3.micro`), PostgreSQL RDS (`t4.medium`).
- `.env` files for both applications must be set inside `dwarf-back` and `dwarf-front` (see `README.md` in `dwarf-back` and `dwarf-front` for instructions).

### Environment Configuration

- **Backend**: Configure database and app settings in `dwarf-back/.env`
- **Frontend**: Set API URLs in `dwarf-front/.env` (variables must be prefixed with `VITE_`)

## Features

- URL shortening service
- RESTful API with Swagger documentation
- Modern React frontend with TypeScript
- Use of `shadcn/ui`
- Comprehensive test coverage
- Docker containerization
- Production deployment