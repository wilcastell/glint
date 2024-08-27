<?php

namespace App\Console\Commands;

use App\Console\Command;

class MakeMigrationCommand extends Command
{
  /**
   * Maneja el comando para crear una nueva migración.
   *
   * @param array $args Los argumentos pasados al comando.
   * @return void
   *
   * Este método toma un array de argumentos, extrae el nombre de la migración,
   * y genera un nuevo archivo de migración basado en una plantilla (stub template).
   * Se asegura de que el directorio de migraciones exista antes de escribir el nuevo
   * archivo de migración. Si no se proporciona un nombre de migración, solicita al usuario
   * que lo proporcione.
   */

  public function handle($args)
  {
    $name = $args[0] ?? null;
    if ($name) {
      $className = 'Create' . ucfirst($name) . 'Table';
      $tableName = strtolower($name);

      $migrationTemplate = file_get_contents(__DIR__ . '/stubs/migration.stub');
      $migrationTemplate = str_replace('{{className}}', $className, $migrationTemplate);
      $migrationTemplate = str_replace('{{tableName}}', $tableName, $migrationTemplate);

      // Ensure the Migrations directory exists
      if (!is_dir("migrations")) {
        mkdir("migrations", 0777, true);
      }

      $timestamp = date('Y_m_d_His');
      $fileName = $timestamp . "_create_{$tableName}_table.php";

      file_put_contents("migrations/{$fileName}", $migrationTemplate);
      echo "Migration created successfully." . PHP_EOL;
    } else {
      echo "Please provide a migration name." . PHP_EOL;
    }
  }
}
