<?php

namespace App\Exceptions;

use Exception;

/**
 * Clase ErrorException
 *
 * Esta clase extiende la clase base Exception y proporciona información adicional
 * sobre los errores, incluyendo el número de error, el archivo y la línea donde ocurrió el error.
 */
class ErrorException extends Exception
{
	protected $errno;
	protected $errfile;
	protected $errline;

	/**
	 * Constructor de la clase ErrorException
	 *
	 * Inicializa una nueva instancia de la clase ErrorException con información detallada sobre el error.
	 *
	 * @param string $message El mensaje de error.
	 * @param int $code El código de error.
	 * @param int $errno El número de error.
	 * @param string $errfile El archivo donde ocurrió el error.
	 * @param int $errline La línea donde ocurrió el error.
	 * @param Exception|null $previous La excepción anterior para encadenar excepciones.
	 */
	public function __construct(
		$message = "",
		$code = 0,
		$errno = 0,
		$errfile = "",
		$errline = 0,
		Exception $previous = null
	) {
		parent::__construct($message, $code, $previous);
		$this->errno = $errno;
		$this->errfile = $errfile;
		$this->errline = $errline;
	}

	/**
	 * Obtiene el número de error.
	 *
	 * @return int El número de error.
	 */
	public function getErrno()
	{
		return $this->errno;
	}

	/**
	 * Obtiene el archivo donde ocurrió el error.
	 *
	 * @return string El archivo donde ocurrió el error.
	 */
	public function getErrFile()
	{
		return $this->errfile;
	}

	/**
	 * Obtiene la línea donde ocurrió el error.
	 *
	 * @return int La línea donde ocurrió el error.
	 */
	public function getErrLine()
	{
		return $this->errline;
	}
}
