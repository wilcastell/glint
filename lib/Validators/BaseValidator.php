<?php

namespace Lib\Validators;

abstract class BaseValidator
{
    protected $data;
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function addError($field, $rule, $defaultMessage, $ruleValue = null)
    {
        $attribute = $field;
        $message = $defaultMessage;
        $message = str_replace(':attribute', $attribute, $message);

        if ($ruleValue !== null) {
            $message = str_replace(":$rule", $ruleValue, $message);
        }

        $this->errors[$field][] = $message;
    }
}
