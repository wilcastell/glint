<?php

namespace Lib\Validators;

class StringValidator extends BaseValidator
{
    /**
     * Valida que el campo sea requerido.
     *
     * @param string $field El nombre del campo.
     */
    public function validateRequired($field)
    {
        if (!isset($this->data[$field]) || empty($this->data[$field])) {
            $this->addError($field, 'required', "{$field} es requerido");
        }
    }

    /**
     * Valida que el campo tenga un mínimo de caracteres.
     *
     * @param string $field El nombre del campo.
     * @param int $value El número mínimo de caracteres.
     */
    public function validateMin($field, $value)
    {
        if (!isset($this->data[$field]) || strlen($this->data[$field]) < $value) {
            $this->addError($field, 'min', "{$field} debe tener al menos {$value} caracteres", $value);
        }
    }

    /**
     * Valida que el campo no exceda un máximo de caracteres.
     *
     * @param string $field El nombre del campo.
     * @param int $value El número máximo de caracteres.
     */
    public function validateMax($field, $value)
    {
        if (!isset($this->data[$field]) || strlen($this->data[$field]) > $value) {
            $this->addError($field, 'max', "{$field} no debe tener más de {$value} caracteres", $value);
        }
    }

    /**
     * Valida que el campo sea un email válido.
     *
     * @param string $field El nombre del campo.
     */
    public function validateEmail($field)
    {
        if (!isset($this->data[$field]) || !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'email', "{$field} debe ser una dirección de email válida");
        }
    }

    /**
     * Valida que el campo sea un número.
     *
     * @param string $field El nombre del campo.
     */
    public function validateNumber($field)
    {
        if (!is_numeric($this->data[$field])) {
            $this->addError($field, 'number', "{$field} debe ser un número");
        }
    }

    /**
     * Valida que el campo coincida con un patrón regex.
     *
     * @param string $field El nombre del campo.
     * @param string $pattern El patrón regex.
     */
    public function validateRegex($field, $pattern)
    {
        if (!preg_match($pattern, $this->data[$field])) {
            $this->addError($field, 'regex', "{$field} no tiene el formato correcto");
        }
    }
}
