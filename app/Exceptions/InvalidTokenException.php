<?php

namespace App\Exceptions;

use Exception;

/**
 * Clase InvalidTokenException
 *
 * Esta clase extiende la clase base Exception y se utiliza para manejar excepciones relacionadas con tokens inválidos.
 */
class InvalidTokenException extends Exception
{
  /**
   * Constructor de la clase InvalidTokenException
   *
   * Inicializa una nueva instancia de la clase InvalidTokenException con un mensaje de error predeterminado o personalizado,
   * un código de error opcional y una excepción anterior para encadenar excepciones.
   *
   * @param string $message El mensaje de error (por defecto es "Invalid token").
   * @param int $code El código de error (por defecto es 0).
   * @param Exception|null $previous La excepción anterior para encadenar excepciones (por defecto es null).
   */
  public function __construct($message = "Invalid token", $code = 0, Exception $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
