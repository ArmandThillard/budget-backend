<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

// executes the "php bin/console cache:clear" command
passthru(sprintf(
  'APP_ENV=%s php "%s/../bin/console" cache:clear --no-warmup',
  $_ENV['APP_ENV'],
  __DIR__
));

// // executes the "php bin/console --env=test doctrine:database:create" command
// passthru(sprintf(
//     'APP_ENV=%s php "%s/../bin/console" doctrine:database:create',
//     $_ENV['APP_ENV'],
//     __DIR__
// ));

// // executes the "php bin/console --env=test doctrine:schema:create" command
// passthru(sprintf(
//     'APP_ENV=%s php "%s/../bin/console" doctrine:schema:create',
//     $_ENV['APP_ENV'],
//     __DIR__
// ));

// executes the "php bin/console --env=test doctrine:schema:create" command
passthru(sprintf(
    'APP_ENV=%s php "%s/../bin/console" --env=test --no-interaction doctrine:fixtures:load',
    $_ENV['APP_ENV'],
    __DIR__
));