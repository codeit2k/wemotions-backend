# WEMOTIONS — Backend Submission

This repository is a complete submission package for the WEMOTIONS Backend Technical Assignment.
It contains a Symfony-style PHP 8.1+ project skeleton demonstrating:
- JWT-based auth (register + login scaffolding)
- User & Video entities (Doctrine-style)
- Chunked upload flow (local assembly)
- Video processing message handler (ffmpeg example)
- Feed & search skeleton
- Docker setup
- SQL migration (schema.sql)
- Postman collection for testing

> NOTE: This is a code submission skeleton intended for reviewers. To run it you should install dependencies (Composer) and optionally use Docker.

## Quick steps for grader (high level)
1. Copy `.env.example` to `.env` and adjust values.
2. (Optional) Build & run with Docker:
   ```
   docker compose up --build -d
   ```
3. Install composer deps (inside PHP container or locally):
   ```
   composer install
   ```
4. Create DB & run migrations or import `sql/schema.sql`.
5. Generate JWT keys:
   ```
   mkdir -p config/jwt
   openssl genrsa -out config/jwt/private.pem 4096
   openssl rsa -in config/jwt/private.pem -pubout -out config/jwt/public.pem
   chmod 600 config/jwt/*
   ```
6. Start worker for processing (optional):
   ```
   php bin/console messenger:consume async -vv
   ```

## What is included
- src/: Controllers, Entities, Message & Handler, Services
- config/: YAML configs
- public/: public front controller and uploads folder
- sql/schema.sql: SQL to create core tables
- postman/: Postman collection import file
- docker-compose.yml, Dockerfile
- README, SUBMISSION.md

## Contact
Prepared by: [Your Name]
Submission date: YYYY-MM-DD
