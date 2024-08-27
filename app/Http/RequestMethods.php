<?php

namespace App\Http;

namespace App\Http;

/**
 * Trait RequestMethods
 *
 * Este trait proporciona métodos para manejar y obtener información sobre las solicitudes HTTP.
 */
trait RequestMethods
{
  /**
   * Obtiene la ruta de la solicitud.
   *
   * @return string La ruta de la solicitud.
   */
  public function path()
  {
    return $_SERVER['REQUEST_URI'];
  }

  /**
   * Obtiene la URL completa de la solicitud.
   *
   * @return string La URL completa de la solicitud.
   */
  public function url()
  {
    return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $this->path();
  }

  /**
   * Verifica si la ruta de la solicitud coincide con una ruta dada.
   *
   * @param string $path La ruta con la que se desea comparar.
   * @return bool True si la ruta coincide, false en caso contrario.
   */
  public function is($path)
  {
    return $this->path() === $path;
  }

  /**
   * Obtiene el método HTTP de la solicitud.
   *
   * @return string El método HTTP de la solicitud.
   */
  public function method()
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  /**
   * Verifica si el método HTTP de la solicitud coincide con un método dado.
   *
   * @param string $method El método con el que se desea comparar.
   * @return bool True si el método coincide, false en caso contrario.
   */
  public function isMethod($method)
  {
    return strtoupper($method) === $this->method();
  }
}
