<?php

namespace App\Console\Commands;

use App\Console\Command;

class MakeControllerCommand extends Command
{
  /**
   * Maneja el comando para crear un nuevo controlador.
   *
   * @param array $args Los argumentos pasados al comando.
   * @return void
   *
   * Este método toma un array de argumentos, extrae el nombre del controlador,
   * y genera un nuevo archivo de controlador basado en una plantilla (stub template).
   * Se asegura de que el directorio de destino exista antes de escribir el nuevo archivo
   * de controlador. Si no se proporciona un nombre de controlador, solicita al usuario
   * que lo proporcione.
   */

  public function handle($args)
  {
    $name = $args[0] ?? null;
    if ($name) {
      $controllerTemplate = file_get_contents(__DIR__ . '/stubs/controller.stub');
      $controllerTemplate = str_replace('{{controllerName}}', $name, $controllerTemplate);

      // Asegúrate de que la carpeta `app/Controllers` exista
      if (!is_dir("app/Controllers")) {
        mkdir("app/Controllers", 0777, true);
      }

      file_put_contents("app/Controllers/{$name}Controller.php", $controllerTemplate);
      echo "Controller created successfully." . PHP_EOL;
    } else {
      echo "Please provide a controller name." . PHP_EOL;
    }
  }
}
