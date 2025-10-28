
# Dwarf

  

A URL shortening application with a Laravel backend API and React frontend.

  

## Project Structure

  

```

dwarf/

├── dwarf-back/ # Laravel API backend

└── dwarf-front/ # React + TypeScript frontend

```

Monorepo for deployment convenience.

  

## Quick Start

  

Quick start instructions for installation and configuration can be found in `README.md` inside `dwarf-back` or `dwarf-front`.

  

### Prerequisites

  

-  **Backend**: PHP ≥ 8.2, Composer ≥ 2.x, Node.js, Xdebug (for coverage)

-  **Frontend**: Node.js ≥ 18, pnpm ≥ 8

-  **Docker**: Docker + Docker Compose v2 (for containerized deployment)

  
  

### Production deployment

  

- For convenience, both applications can be ran with a single command `docker compose up --build` with the `docker-compose.yml` file at the root, this will create a `dwarf-back` and a `dwarf-front` service running in Docker containers.

- Alternatively, both services can be run separately using Docker Compose (see `README.md` in `dwarf-back` or `dwarf-front` for instructions).

- Deployed services are live in : `https://back.ghoul.ch` and `https://front.ghoul.ch`, using AWS Route53, AWS ALB, AWS EC2 (`t3.micro`), PostgreSQL RDS (`t4.medium`).

-  `.env` files for both applications must be set inside `dwarf-back` and `dwarf-front` (see `README.md` in `dwarf-back` and `dwarf-front` for instructions).

  

### Environment Configuration

  

-  **Backend**: Configure database and app settings in `dwarf-back/.env`

-  **Frontend**: Set API URLs in `dwarf-front/.env` (variables must be prefixed with `VITE_`)

  

## Features

  

- URL shortening service

- RESTful API with Swagger documentation

- Modern React frontend with TypeScript

- Use of `shadcn/ui`

- Comprehensive test coverage

- Docker containerization

- Production deployment

# Development Notes

This sections captures key design and implementation considerations for the project.

----------

## Architecture Overview

-   **Backend:** Laravel API exposing RESTful endpoints for URL CRUD and redirects.
    
-   **Frontend:** React + TypeScript SPA that consumes the API.
    
-   **Service Layer:** Encapsulates logic and separation of concerns.
    
-   **Infrastructure:** Dockerized services. Deployed on AWS using **EC2** (compute), **RDS** (database), and **ALB** (load balancer).

<img width="2047" height="693" alt="image" src="https://github.com/user-attachments/assets/5cd85f5c-461e-4d04-9f90-cb58b8f95dbb" />


----------

## API Design

-   **Versioning:** Prefix all endpoints with `/api/v1`. Future versions use `/api/v2`, etc. Keep old versions available until clients migrate.
    
-   **RESTful resources (v1):**
    
    -   `GET /api/v1/urls` — list URLs
        
    -   `POST /api/v1/urls` — create short code for a long URL
        
    -   `GET /api/v1/urls/{id}` — fetch URL by id
        
    -   `DELETE /api/v1/urls/{id}` — delete URL
        
    -   `GET /api/v1/urls/code/{code}` — fetch by code
        
    -   `GET /v1/{code}/redirect` — perform redirect (public)
        
-   **Idempotency:** Creating the same URL should return the same code where feasible; the controller should use the service consistently.
    

----------

## Service Layer Logic

-   **Algorithm summary (`UrlService::shorten`):**
    
    -   Compute `md5($url)` and take the first 10 hex chars.
        
    -   Convert to decimal with `hexdec`.
        
    -   Base62 encode with `0-9a-zA-Z`.
        
    -   Truncate to **max 8 chars**.
        
    -   Resolve collisions by incrementing the decimal and re-encoding until a free code appears.
        
-   **Notes:**
    
    -   `md5` is non-cryptographic. Used only for deterministic hashing.
        
    -   Create index for `code` for fast lookups.
    
        

----------

## Validation and Error Handling

-   **Request validation:**
    
Use of Request classes in Laravel for separating request validation from Controller.
        

    
-   **Consistent JSON errors:**
    
    -   400 validation
        
    -   404 not found
        
    -   500 for server errors
    

----------

## Redirect Behavior and Client Caching

-   **Endpoint:** `GET /v1/{code}/redirect`
    
-   **Status:** `302` for non permanent redirects to lever client side caching.
    
-   **Cache headers:** `Cache-Control: public, max-age=86400` and `ETag` to enable browser caching of the redirect response.
    

----------

## Persistence and Data Model

-   **Table `urls`:** `{ id, url, code, created_at, ... }`
    
-   **Indexes:** `code` for single key fast lookups.
    
-   **Migrations:** for database table creations.
    

----------

## React + TypeScript Frontend

-   Typed API client.
    
-   URL normalization client-side before submit.
    
-   UX: visual user feedback, intuitive interface, modern styling with `shadcn/ui`.    

----------

## Docker and Deployment

-   **Containers:** Frankenphp app server written in `Go`, and built React app served with `serve`.
    
-   **AWS:** EC2 ASG behind ALB, RDS, ACM TLS on ALB.
    
-   **Health checks:** `/health` on target group.
    

----------

## Testing

-   Unit: hashing, base62.
    
-   Feature: endpoints, validation, error contracts.

----------

## Known Trade-offs

-   `md5` collisions are possible. Collision loop and unique index mitigate.
    
-   8-char truncation increases collision risk slightly.
    
-   Client caching redirects improves latency but prevents to accurate analytics and monitoring.
    

----------

## Future Enhancements
    
-   Expiration and soft-delete.
- Track user activity.
- User authentication mechanisms.
- Server-side caching with `Redis`.
- NoSQL database like `MongoDB`  for scaling since queries are made by a single key lookup.
