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

## Local test

### Database

1. Create and edit `.env.test.local` with test database URL :
   `DATABASE_URL=postgres://$user:$password@172.26.58.160:5432/budget-test`
2. Create the test database : `php bin/console --env=test doctrine:database:create`
3. Create the tables/columns in the test database : `php bin/console --env=test doctrine:schema:create`
4. Load fixtures : `php bin/console --env=test doctrine:fixtures:load`
5. Run test cases : `php bin/phpunit`
