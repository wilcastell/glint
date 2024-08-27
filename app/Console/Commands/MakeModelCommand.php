<?php

namespace App\Console\Commands;

use App\Console\Command;

class MakeModelCommand extends Command
{
  /**
   * Maneja el comando para crear un nuevo modelo.
   *
   * @param array $args Los argumentos pasados al comando.
   * @return void
   *
   * Este método toma un array de argumentos, extrae el nombre del modelo,
   * y genera un nuevo archivo de modelo basado en una plantilla (stub template).
   * Se asegura de que el directorio de modelos exista antes de escribir el nuevo
   * archivo de modelo. Si no se proporciona un nombre de modelo, solicita al usuario
   * que lo proporcione.
   */

  public function handle($args)
  {
    $name = $args[0] ?? null;
    if ($name) {
      $table = strtolower($name) . 's';
      // Assuming a plural naming convention for tables

      $modelTemplate = file_get_contents(__DIR__ . '/stubs/model.stub');
      $modelTemplate = str_replace('{{modelName}}', $name, $modelTemplate);
      $modelTemplate = str_replace('{{tableName}}', $table, $modelTemplate);

      // Ensure the Models directory exists
      if (!is_dir("app/Models")) {
        mkdir("app/Models", 0777, true);
      }

      file_put_contents("app/Models/{$name}.php", $modelTemplate);
      echo "Model created successfully." . PHP_EOL;
    } else {
      echo "Please provide a model name." . PHP_EOL;
    }
  }
}
