# Website  for GNSS/GSM tracker device

1. Install Docker Compose (v2.10+), local php 8.3
2. `php bin/console asset-map:compile`
3. `docker compose build --no-cache`
4. `docker compose up --pull always -d --wait`
5. `docker compose down --remove-orphans`

## Running on production

Run `composer dump-env prod`. Then edit .env.local.php. 
To update Composer files use `composer dump-autoload`.

```shell
SERVER_NAME=example.com \
APP_SECRET=change \
CADDY_MERCURE_JWT_SECRET=change \
POSTGRES_PASSWORD=change \
docker compose -f compose.yaml -f compose.prod.yaml up -d --wait
```

