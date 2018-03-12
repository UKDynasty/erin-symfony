# erin-symfony

- `dokku apps:create erin`
- `dokku mariadb:create erin`
- `dokku mariadb:link erin-db erin`
- `dokku config:set erin APP_ENV=prod`
- `dokku config:set erin EACH_ENV_VAR=value`
- push