# Website  for GNSS/GSM tracker device

1. Install Docker Compose (v2.10+), local php 8.3
2. `php bin/console asset-map:compile`
3. `docker compose build --no-cache`
4. `docker compose up --pull always -d --wait`
5. `docker compose down --remove-orphans`

