<?php

namespace Lib;

use App\Exceptions\ErrorException;

class ErrorHandler
{
  /**
   * Maneja las excepciones no capturadas.
   *
   * @param \Exception $exception La excepción que se ha lanzado.
   */
  public static function handleException($exception)
  {
    // Registrar los detalles de la excepción
    Logger::log($exception->getMessage());
    Logger::log($exception->getFile() . ':' . $exception->getLine());
    Logger::log($exception->getTraceAsString());

    // Mostrar un mensaje de error amigable para el usuario
    http_response_code(500);
    if (self::isDevelopment()) {
      echo "<h1>Exception Occurred</h1>";
      echo "<p>" . $exception->getMessage() . "</p>";
      echo "<p>File: " . $exception->getFile() . "</p>";
      echo "<p>Line: " . $exception->getLine() . "</p>";
      echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    } else {
      echo "Something went wrong. Please try again later.";
    }
  }

  /**
   * Maneja los errores de PHP convirtiéndolos en excepciones.
   *
   * @param int $errno El número del error.
   * @param string $errstr El mensaje del error.
   * @param string $errfile El archivo donde ocurrió el error.
   * @param int $errline La línea donde ocurrió el error.
   * @throws ErrorException
   */
  public static function handleError($errno, $errstr, $errfile, $errline)
  {
    // Convertir errores en excepciones
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }

  /**
   * Maneja el cierre del script y captura errores fatales.
   */
  public static function handleShutdown()
  {
    $error = error_get_last();
    if ($error !== null) {
      $exception = new \ErrorException($error['message'], $error['type'], 0, $error['file'], $error['line']);
      self::handleException($exception);
    }
  }

  /**
   * Determina si el entorno es de desarrollo.
   *
   * @return bool Devuelve true si el entorno es de desarrollo, de lo contrario false.
   */
  private static function isDevelopment()
  {
    // Cambiar este valor manualmente para alternar entre desarrollo y producción
    return true; // Set to false for production
  }
}
