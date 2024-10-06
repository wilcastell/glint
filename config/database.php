<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbUser = getenv('DB_USER');
$port = getenv('DB_PORT') ?: '3306';
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');
$dbPrefix = getenv('DB_PREFIX');
$baseURL = getenv('BASE_URL');
$domain = getenv('APP_URL');

// Verificar si las variables de entorno se cargaron correctamente
if (!$dbHost || !$dbUser || !$dbName || !$domain || !$baseURL) {
    die('Error: Las variables de entorno para la base de datos no se cargaron correctamente.');
}

define('BASE_URL', $baseURL);
define('DOMAIN', $domain);

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $dbHost,
    'database'  => $dbName,
    'username'  => $dbUser,
    'password'  => $dbPass,
    'charset'   => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix'    => $dbPrefix
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
