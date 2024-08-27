<?php

namespace Lib;

/**
 * Este archivo se encarga de cargar las variables de entorno desde un archivo
 * .env y establecerlas en los superglobales $_ENV y $_SERVER,
 * así como en el entorno del proceso utilizando putenv.
 */

$envFile = dirname(__DIR__) . '/.env';

if (file_exists(dirname(__DIR__) . '/.env.local')) {
  $envFile = dirname(__DIR__) . '/.env.local';
} elseif (file_exists(dirname(__DIR__) . '/.env.production')) {
  $envFile = dirname(__DIR__) . '/.env.production';
}

if (!file_exists($envFile)) {
  die('Error: El archivo .env no existe.');
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
  if (strpos(trim($line), '#') === 0) {
    continue;
  }
  list($key, $value) = explode('=', $line, 2);
  $_ENV[$key] = $value;
  $_SERVER[$key] = $value;
  putenv("$key=$value");
}

// Verificar si las variables de entorno se cargaron correctamente
if (!getenv('DB_HOST')) {
  die('Error: No se pudieron cargar las variables de entorno.');
}
