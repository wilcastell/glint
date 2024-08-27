<?php

namespace Lib\Validators;

use Illuminate\Database\Capsule\Manager as DB;

class DatabaseValidator extends BaseValidator
{
    /**
     * Valida que el campo sea único en la base de datos.
     *
     * @param string $field El nombre del campo.
     * @param string $tableAndColumn La tabla y columna en formato 'tabla,columna'.
     */
    public function validateUnique($field, $tableAndColumn)
    {
        $parts = explode(',', $tableAndColumn);
        $table = $parts[0] ?? null;
        $column = $parts[1] ?? null;
        $id = $parts[2] ?? null;

        if ($table === null || $column === null) {
            $this->addError($field, 'unique', "La configuración de la regla 'unique' es inválida");
            return;
        }

        $value = $this->data[$field] ?? null;

        if ($value === null) {
            $this->addError($field, 'unique', "El campo {$field} no está definido");
            return;
        }

        $query = DB::table($table)->where($column, $value);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        $exists = $query->exists();

        if ($exists) {
            $this->addError($field, 'unique', "{$field} debe ser único");
        }
    }

    /**
     * Valida que el campo exista en la base de datos.
     *
     * @param string $field El nombre del campo.
     * @param string $tableAndColumn La tabla y columna en formato 'tabla,columna'.
     */
    public function validateExists($field, $tableAndColumn)
    {
        $parts = explode(',', $tableAndColumn);
        $table = $parts[0] ?? null;
        $column = $parts[1] ?? null;

        if ($table === null || $column === null) {
            $this->addError($field, 'exists', "La configuración de la regla 'exists' es inválida");
            return;
        }

        $value = $this->data[$field] ?? null;

        if ($value === null) {
            $this->addError($field, 'exists', "El campo {$field} no está definido");
            return;
        }

        $exists = DB::table($table)->where($column, $value)->exists();

        if (!$exists) {
            $this->addError($field, 'exists', "{$field} no existe");
        }
    }
}
