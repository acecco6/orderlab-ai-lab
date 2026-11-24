<?php

declare(strict_types=1);

require dirname(__DIR__).'/vendor/autoload.php';

use Dotenv\Dotenv;

$root = dirname(__DIR__);
$testFile = $root.'/.env.testing';

if (! is_file($testFile) || is_file($root.'/bootstrap/cache/config.php')) {
    fwrite(STDERR, "Se requiere .env.testing y config sin cache.\n");
    exit(1);
}

$test = Dotenv::parse(file_get_contents($testFile));
$dev = is_file($root.'/.env') ? Dotenv::parse(file_get_contents($root.'/.env')) : [];
$value = static fn (string $key): string => (string) (getenv($key) !== false ? getenv($key) : ($test[$key] ?? ''));

if ($value('APP_ENV') !== 'testing'
    || $value('DB_CONNECTION') !== 'mysql'
    || $value('DB_DATABASE') !== 'orderlab_test'
    || $value('DB_USERNAME') === ''
    || $value('DB_HOST') === ''
    || $value('DB_DATABASE') === ($dev['DB_DATABASE'] ?? null)
    || $value('DB_USERNAME') === ($dev['DB_USERNAME'] ?? null)
    || $value('DB_URL') !== '') {
    fwrite(STDERR, "Destino MySQL de pruebas no aislado o incompleto.\n");
    exit(1);
}

fwrite(STDOUT, "Destino de pruebas verificado: orderlab_test, usuario separado.\n");
