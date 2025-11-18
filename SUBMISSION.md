WEMOTIONS Submission Notes

Repo: wemotions-backend (this package)
Includes:
- README with run instructions
- SQL schema
- src/ with controllers, entities, message handlers
- Dockerfile + docker-compose.yml
- postman collection

How to run (quick):
1. Copy .env.example to .env and set values
2. docker compose up --build -d
3. composer install
4. Import sql/schema.sql into MySQL or run migrations
5. Generate JWT keys (see README)
6. Use Postman to test endpoints
