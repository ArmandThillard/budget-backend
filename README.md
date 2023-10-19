# Database

This project needs a PostgreSQL database to store data.

## New database for dev environnement

1. Create a new empty PostreSQL database
2. Create schema using Doctrine : `php bin/console doctrine:schema:create`
3. Insert default values using `src/Sql/default-data.sql`

## New database for prod environnement

1. Create a new empty PostgreSQL database
2. Create schema using `src/Sql/schema-ddl.sql`
3. Insert default values using `src/Sql/default-data.sql`

# Test

- `php bin/console --env=test d:d:c`
- `php bin/console --env=test d:s:c`
- `php bin/console --env=test d:f:l`
- `php bin/phpunit`
