<?php

namespace Lib;

use Lib\Validators\StringValidator;
use Lib\Validators\FileValidator;
use Lib\Validators\DatabaseValidator;

class Validator
{
  protected $data;
  protected $rules;
  protected $messages;
  protected $attributes;
  protected $errors = [];
  protected $customRules = [];

  /**
   * Constructor de la clase Validator.
   *
   * @param array $data Los datos a validar.
   * @param array $rules Las reglas de validación.
   * @param array $messages Los mensajes de error personalizados.
   * @param array $attributes Los nombres de los atributos.
   */
  public function __construct(array $data, array $rules, array $messages = [], array $attributes = [])
  {
    $this->data = $data;
    $this->rules = $rules;
    $this->messages = $messages;
    $this->attributes = $attributes;
    $this->validate();
  }

  /**
   * Realiza la validación de los datos.
   */
  protected function validate()
  {
    $stringValidator = new StringValidator($this->data);
    $fileValidator = new FileValidator($this->data);
    $databaseValidator = new DatabaseValidator($this->data);

    foreach ($this->rules as $field => $rules) {
      $rules = explode('|', $rules);
      foreach ($rules as $rule) {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleValue = $ruleParts[1] ?? null;

        if (isset($this->customRules[$ruleName])) {
          $callback = $this->customRules[$ruleName];
          if (!$callback($this->data[$field] ?? null, $ruleValue)) {
            $this->errors[$field][] = "El campo $field no pasó la validación de la regla $ruleName.";
          }
        } else {
          switch ($ruleName) {
            case 'required':
            case 'min':
            case 'max':
            case 'email':
            case 'number':
            case 'regex':
              $stringValidator->{"validate" . ucfirst($ruleName)}($field, $ruleValue);
              break;
            case 'file':
            case 'fileSize':
            case 'fileExtension':
            case 'mimes':
              $fileValidator->{"validate" . ucfirst($ruleName)}($field, $ruleValue);
              break;
            case 'unique':
            case 'exists':
              $databaseValidator->{"validate" . ucfirst($ruleName)}($field, $ruleValue);
              break;
            default:
              $this->errors[$field][] = "Regla de validación desconocida: $ruleName.";
              break;
          }
        }
      }
    }

    $this->errors = array_merge(
      $stringValidator->getErrors(),
      $fileValidator->getErrors(),
      $databaseValidator->getErrors()
    );
  }

  /**
   * Verifica si la validación ha fallado.
   *
   * @return bool Retorna true si hay errores, de lo contrario false.
   */
  public function fails()
  {
    return !empty($this->errors);
  }

  /**
   * Obtiene los errores de validación.
   *
   * @return array Un array con los errores de validación.
   *
   * Este método retorna un array con los errores de validación que se han producido.
   */
  public function errors()
  {
    return $this->errors;
  }

  /**
   * Agrega una regla de validación personalizada.
   *
   * @param string $ruleName El nombre de la regla.
   * @param callable $callback La función de validación que se ejecutará.
   */
  public function addCustomRule($ruleName, callable $callback)
  {
    $this->customRules[$ruleName] = $callback;
  }
}
