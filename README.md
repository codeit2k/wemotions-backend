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


API Endpoints Implemented
Authentication
Method	Endpoint	Description	Auth
POST	/api/auth/register	Register a new user	❌
POST	/api/auth/login	Login & receive JWT token	❌

User Profile & Social
Method	Endpoint	Description	Auth
GET	/api/user/me	Get logged-in user profile	✔️
PUT	/api/user/update	Update user profile	✔️
DELETE	/api/user/delete	Delete user account	✔️
POST	/api/user/follow/{id}	Follow another user	✔️
POST	/api/user/unfollow/{id}	Unfollow a user	✔️

Video Upload & Management
Method	Endpoint	Description	Auth
POST	/api/video/upload/init	Initialise chunk upload session	✔️
POST	/api/video/upload/chunk	Upload a video chunk	✔️
POST	/api/video/upload/finish	Finalise upload & trigger processing	✔️
GET	/api/video/{id}	Get video metadata	❌
DELETE	/api/video/{id}	Delete own video	✔️

Feed
Method	Endpoint	Description	Auth
GET	/api/feed/home	Home feed (followed creators)	✔️
GET	/api/feed/trending	Trending videos	❌
GET	/api/feed/all	Global feed (cursor-based pagination)	❌

Search & Hashtags
Method	Endpoint	Description	Auth
GET	/api/search?q=	Full-text search	❌
GET	/api/hashtags/trending	Trending hashtags	❌



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
Prepared by: Khushi Singh
Submission date: 2025-12-18
