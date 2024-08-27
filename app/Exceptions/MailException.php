<?php

namespace App\Exceptions;

use Exception;

/**
 * Clase MailException
 *
 * Esta clase extiende la clase base Exception y se utiliza para manejar excepciones relacionadas con el envío de correos electrónicos.
 */
class MailException extends Exception
{

  /**
   * Constructor de la clase MailException
   *
   * Inicializa una nueva instancia de la clase MailException con un mensaje de error predeterminado o personalizado,
   * un código de error opcional y una excepción anterior para encadenar excepciones.
   *
   * @param string $message El mensaje de error (por defecto es una cadena vacía).
   * @param int $code El código de error (por defecto es 0).
   * @param Exception|null $previous La excepción anterior para encadenar excepciones (por defecto es null).
   */
  public function __construct($message = "", $code = 0, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
