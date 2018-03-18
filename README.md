# erin-symfony

## Deployment via Dokku

- `dokku apps:create erin`
- `dokku mariadb:create erin`
- `dokku mariadb:link erin-db erin`
- `dokku config:set erin APP_ENV=prod`
- `dokku config:set erin EACH_ENV_VAR=value`
- git push to the dokku host

## DB setup

- Run migrations
- Fixtures must be loaded (positions)

## Commands

- `app:updateplayers` fetches players from MFL's API (cron: twice daily)
- `app:syncfranchisedata` fetches rosters from MFL's API and links players to franchises (cron: every minute)
- `app:synctradebaitdata` fetches trade bait from MFL's API and flags players as listed as bait (cron: every 5 minutes)
- `app:syncassetsdata` fetches all tradeable asset data from MFL's API (current draft picks, future draft picks, players), and updates owners of draft picks (cron: every 5 minutes)

The above commands are all run on deployment if deploying via Dokku, via the pre/post-deploy commands set in `app.json`. They must be set up as cron jobs on the Dokku server.

- `app:createdraft year` creates a draft
- `app:setdraftorder year` sets the draft order for a draft

## Notes

### Draft creation/maintenance

For a draft and its picks to exist and their owners to be updateable via the `app:syncassetsdata` command, the draft must have been first created using `app:createdraft xxxx`.

MFL sees picks for the next draft as "current year picks", and the API doesn't provide the "original owner" of these picks in its responses. Therefore, as soon as a new league year begins, the draft order must be set for the upcoming draft using `app:setdraftorder xxxx`.

`xxxx` in the above examples is the calendar year in which the draft will take place.