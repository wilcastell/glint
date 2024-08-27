<?php

namespace App\Http;

/**
 * Trait RequestHeaders
 *
 * Este trait proporciona métodos para manejar los encabezados y parámetros de consulta de las solicitudes HTTP.
 */
trait RequestHeaders
{
  /**
   * Obtiene el valor de un encabezado de la solicitud.
   *
   * @param string $key La clave del encabezado que se desea obtener.
   * @return string|null El valor del encabezado solicitado o null si no existe.
   */
  public function header($key)
  {
    return $_SERVER[$key] ?? null;
  }

  /**
   * Obtiene el valor de un parámetro de consulta de la solicitud.
   *
   * @param string $key La clave del parámetro de consulta que se desea obtener.
   * @param mixed $default El valor predeterminado a devolver si la clave no existe (por defecto es null).
   * @return mixed El valor del parámetro de consulta solicitado o el valor predeterminado si la clave no existe.
   */
  public function query($key, $default = null)
  {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
  }
}
