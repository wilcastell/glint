<?php

namespace App\Http;

/**
 * Trait RequestFiles
 *
 * Este trait proporciona métodos para manejar archivos subidos en las solicitudes HTTP.
 */
trait RequestFiles
{
  /**
   * Obtiene un archivo subido por su clave.
   *
   * @param string $key La clave del archivo que se desea obtener.
   * @return array|null El archivo subido o null si no existe.
   */
  public function file($key)
  {
    return $this->files[$key] ?? null;
  }

  /**
   * Verifica si un archivo ha sido subido correctamente.
   *
   * @param string $key La clave del archivo que se desea verificar.
   * @return bool True si el archivo ha sido subido correctamente, false en caso contrario.
   */
  public function hasFile($key)
  {
    return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
  }

  /**
   * Obtiene la ruta real del archivo subido.
   *
   * @param string $key La clave del archivo que se desea obtener.
   * @return string|null La ruta temporal del archivo subido o null si no existe.
   */
  public function getRealPath($key)
  {
    return $this->hasFile($key) ? $this->files[$key]['tmp_name'] : null;
  }

  /**
   * Verifica si un archivo subido es válido.
   *
   * @param string $key La clave del archivo que se desea verificar.
   * @return bool True si el archivo es válido, false en caso contrario.
   */
  public function isValid($key)
  {
    if (!isset($_FILES[$key])) {
      return false;
    }

    $file = $_FILES[$key];

    if ($file['error'] !== UPLOAD_ERR_OK) {
      return false;
    }

    return true;
  }
}
