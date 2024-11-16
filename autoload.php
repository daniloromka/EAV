<?php

require_once __DIR__ . '/vendor/autoload.php';

if (isset($argv[1]) && strpos($argv[1], 'PDO_PGSQL_DSN=') === 0) {
    putenv($argv[1]);
} elseif (file_exists(getcwd() . '/.env')) {
    $env = parse_ini_file(getcwd() . '/.env');
    putenv('PDO_PGSQL_DSN=' . $env['PDO_PGSQL_DSN']);
}
