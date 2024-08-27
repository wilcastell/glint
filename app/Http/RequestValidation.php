<?php

namespace App\Http;

use Lib\Validator;

/**
 * Trait RequestValidation
 *
 * Este trait proporciona métodos para validar los datos de las solicitudes HTTP.
 */
trait RequestValidation
{
  /**
   * Valida los datos de la solicitud según las reglas especificadas.
   *
   * @param array $rules Las reglas de validación.
   * @param array $messages Los mensajes personalizados para las reglas de validación (opcional).
   * @param array $attributes Los nombres personalizados para los atributos (opcional).
   * @return array Un arreglo con los errores de validación, si los hay.
   */
  public function validate(array $rules, array $messages = [], array $attributes = [])
  {
    $validator = new Validator($this->data, $rules, $messages, $attributes);
    if ($validator->fails()) {
      $_SESSION['errors'] = $validator->errors();
      $_SESSION['old'] = $this->data;
      header('Location: ' . $_SERVER['HTTP_REFERER']);
      exit;
    }
    return $validator->errors();
  }

  /**
   * Almacena los datos antiguos de la solicitud en la sesión.
   *
   * @return void
   */
  protected function storeOldInput()
  {
    $_SESSION['old'] = $this->data;
  }
}
