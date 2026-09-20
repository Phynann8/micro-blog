# Shipping this Laravel project to Docker

This Docker setup builds a **self-contained production image** (Nginx + PHP-FPM +
the Laravel app + compiled Vite assets) and runs it alongside a **MySQL** database
managed with Docker Compose.

## Files added

| File                    | Purpose                                                        |
|-------------------------|----------------------------------------------------------------|
| `Dockerfile`            | Builds the app image (Nginx + PHP-FPM + Composer deps + Vite)  |
| `docker-compose.yml`    | Orchestrates the `app` and `db` (MySQL 8.4) containers         |
| `docker/nginx-microblog.conf` | Nginx vhost serving Laravel through PHP-FPM on :9000    |
| `docker/entrypoint.sh`  | Boots the app: env + key, storage, caches, migrations, servers |
| `docker/rewrite-env.php`| Copies container env (DB_HOST=db, ...) into `.env` at boot     |
| `.dockerignore`         | Keeps `vendor`, `node_modules`, `.env`, storage out of image   |

## How to run

From the project root (with Docker Desktop running):

```bash
# 1) Build the app image and start everything (first build downloads a lot)
docker compose up --build -d

# 2) Verify both containers are healthy
docker compose ps

# 3) Open the app
#    http://localhost:8000
```

Useful commands:

```bash
docker compose logs -f app      # watch Laravel / Nginx logs
docker compose exec app php artisan tinker   # run artisan inside the container
docker compose down             # stop (keeps the MySQL data volume)
docker compose down -v          # stop AND delete the MySQL database
```

## Configuration

All runtime settings come from the `environment:` block of the `app` service in
`docker-compose.yml` (DB host/credentials, APP_URL, session/cache/queue drivers).
The `db` service uses the same database name and password. To change them, update
both services consistently.

## Notes / things to know

- The app image bakes in the code and dependencies; the only writable, persistent
  data is the MySQL volume (`db-data`) and uploaded files (`storage-data`).
- The entrypoint automatically runs `php artisan migrate` and `db:seed` on every
  start, so a fresh clone will have its database schema ready.
- The frontend (Vite/Tailwind) is compiled during the image build (`npm run build`),
  so `public/build` does **not** need to be committed.
- A fresh `APP_KEY` is generated at boot if the image is run without one.
- `APP_ENV=production` and `APP_DEBUG=false` are set for shipping; flip them in
  `docker-compose.yml` for local debugging.
