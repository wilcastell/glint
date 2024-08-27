<?php

namespace App\Http;

/**
 * Trait RequestData
 *
 * Este trait proporciona métodos para manejar los datos de las solicitudes HTTP.
 */
trait RequestData
{
  /**
   * Obtiene el valor de un dato de la solicitud.
   *
   * @param string $key La clave del dato que se desea obtener.
   * @param mixed $default El valor predeterminado a devolver si la clave no existe (por defecto es null).
   * @return mixed El valor del dato solicitado o el valor predeterminado si la clave no existe.
   */
  public function input($key, $default = null)
  {
    return $this->data[$key] ?? $default;
  }

  /**
   * Obtiene todos los datos de la solicitud, incluyendo archivos subidos.
   *
   * @return array Un arreglo que contiene todos los datos de la solicitud y los archivos subidos.
   */
  public function all()
  {
    return array_merge($this->data, $this->files);
  }

  /**
   * Verifica si un dato específico existe en la solicitud.
   *
   * @param string $key La clave del dato que se desea verificar.
   * @return bool True si el dato existe, false en caso contrario.
   */
  public function has($key)
  {
    return isset($this->data[$key]);
  }

  /**
   * Fusiona un arreglo de datos con los datos actuales de la solicitud.
   *
   * @param array $data Un arreglo de datos que se desea fusionar con los datos actuales.
   * @return $this La instancia actual para permitir el encadenamiento de métodos.
   */
  public function merge(array $data)
  {
    $this->data = array_merge($this->data, $data);
    return $this;
  }

  /**
   * Método mágico para acceder a los datos de la solicitud como propiedades del objeto.
   *
   * @param string $key La clave del dato que se desea obtener.
   * @return mixed El valor del dato solicitado.
   */
  public function __get($key)
  {
    return $this->input($key);
  }
}
