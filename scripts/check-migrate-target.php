<?php

declare(strict_types=1);
use Illuminate\Contracts\Console\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';

$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$database = config('database.connections.mysql.database');
$connection = config('database.default');
$url = config('database.connections.mysql.url');
$host = config('database.connections.mysql.host');
$port = config('database.connections.mysql.port');
$username = config('database.connections.mysql.username');
$socket = config('database.connections.mysql.unix_socket');

if (app()->environment() !== 'local'
    || $connection !== 'mysql'
    || $database !== 'orderlab_dev'
    || ! is_string($host)
    || ! is_string($username)
    || $host !== getenv('CONFIRM_DB_HOST')
    || (string) $port !== getenv('CONFIRM_DB_PORT')
    || $username !== getenv('CONFIRM_DB_USERNAME')
    || filled($socket)
    || filled($url)) {
    fwrite(STDERR, "Migración denegada: destino efectivo distinto de MySQL/orderlab_dev local.\n");
    exit(1);
}

fwrite(STDOUT, "Destino de migración verificado: MySQL/orderlab_dev local.\n");
