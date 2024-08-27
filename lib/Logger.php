<?php

namespace Lib;

class Logger
{
  /**
   * Registra un mensaje en el archivo de log.
   *
   * @param string $message El mensaje que se va a registrar.
   *
   * Este método escribe un mensaje en el archivo de log `app.log` ubicado en el directorio `logs`.
   * El mensaje se precede con la fecha y hora actuales en el formato `YYYY-MM-DD HH:MM:SS`.
   * Si el archivo no existe, se crea automáticamente. Si ya existe, el mensaje se añade al final del archivo.
   *
   * Ejemplo de uso:
   * ```php
   * \Lib\Logger::log('Este es un mensaje de prueba');
   * ```
   */
  public static function log($message)
  {
    $logFile = __DIR__ . '/../logs/app.log';
    $date = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$date] $message" . PHP_EOL, FILE_APPEND);
  }
}
