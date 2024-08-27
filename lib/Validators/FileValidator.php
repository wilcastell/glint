<?php

namespace Lib\Validators;

class FileValidator extends BaseValidator
{
    /**
     * Valida que el campo sea un archivo válido.
     *
     * @param string $field El nombre del campo.
     */
    public function validateFile($field)
    {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] != UPLOAD_ERR_OK) {
            $this->addError($field, 'file', "{$field} no es un archivo válido");
        }
    }

    /**
     * Valida que el archivo no exceda un tamaño máximo.
     *
     * @param string $field El nombre del campo.
     * @param int $maxSize El tamaño máximo en bytes.
     */
    public function validateFileSize($field, $maxSize)
    {
        if (isset($_FILES[$field]) && $_FILES[$field]['size'] > $maxSize) {
            $this->addError($field, 'fileSize', "{$field} debe ser menos de " . ($maxSize / 1024 / 1024) . " MB");
        }
    }

    /**
     * Valida que el archivo tenga una extensión permitida.
     *
     * @param string $field El nombre del campo.
     * @param string $extensions Las extensiones permitidas separadas por comas.
     */
    public function validateFileExtension($field, $extensions)
    {
        $extensions = explode(',', $extensions);
        if (isset($_FILES[$field])) {
            $fileExtension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
            if (!in_array($fileExtension, $extensions)) {
                $this->addError($field, 'fileExtension', "{$field} el archivo debe ser de tipo: " . implode(', ', $extensions));
            }
        }
    }

    /**
     * Valida que el archivo tenga un tipo MIME permitido.
     *
     * @param string $field El nombre del campo.
     * @param string $mimes Los tipos MIME permitidos separados por comas.
     */
    public function validateMimes($field, $mimes)
    {
        $mimes = explode(',', $mimes);
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] == UPLOAD_ERR_OK) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $fileMimeType = $finfo->file($_FILES[$field]['tmp_name']);
            if (!in_array($fileMimeType, $mimes)) {
                $this->addError($field, 'mimes', "{$field} el archivo debe ser de tipo: " . implode(', ', $mimes));
            }
        }
    }
}
