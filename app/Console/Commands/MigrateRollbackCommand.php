<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Database\Capsule\Manager as Capsule;

class MigrateRollbackCommand extends Command
{
  /**
   * Maneja el comando para revertir las migraciones.
   *
   * @param array $args Los argumentos pasados al comando.
   * @return void
   *
   * Este método obtiene el último lote de migraciones y verifica si hay migraciones para revertir.
   * Si no hay migraciones, muestra un mensaje y termina la ejecución. Si hay migraciones, obtiene
   * las clases de migración, deshabilita temporalmente las restricciones de clave foránea, y luego
   * itera sobre las migraciones ejecutadas en el último lote, revirtiéndolas y eliminando sus registros.
   * Finalmente, vuelve a habilitar las restricciones de clave foránea y muestra un mensaje indicando
   * que las migraciones han sido revertidas.
   */
  public function handle($args)
  {
    // Obtener el último lote de migraciones
    $lastBatch = Capsule::table('migrations')->max('batch');

    if ($lastBatch === null) {
      echo "No hay migraciones que revertir." . PHP_EOL;
      return;
    }

    $migrations = $this->getMigrationClasses();

    // Deshabilitar las restricciones de clave foránea temporalmente
    Capsule::connection()->statement('SET FOREIGN_KEY_CHECKS=0;');

    foreach ($migrations as $migrationClass) {
      if ($this->migrationExecuted($migrationClass, $lastBatch)) {
        /** @var \Migrations\Migration $migration */
        $migration = new $migrationClass;
        $migration->down();
        $this->removeMigrationRecord($migrationClass);
        echo "Rolled back: " . $migrationClass . PHP_EOL;
      }
    }

    // Volver a habilitar las restricciones de clave foránea
    Capsule::connection()->statement('SET FOREIGN_KEY_CHECKS=1;');

    echo "Migracion revertida, queda " . $lastBatch - 1 . " por ejecutar " . PHP_EOL;
  }

  /**
   * Verifica si una migración específica ha sido ejecutada en un lote determinado.
   *
   * @param string $migrationClass El nombre de la clase de migración.
   * @param int $batch El número del lote de migración.
   * @return bool
   *
   * Este método consulta la tabla de migraciones para verificar si una migración específica
   * ha sido ejecutada en el lote proporcionado. Devuelve true si la migración existe en el lote,
   * de lo contrario, devuelve false.
   */
  private function migrationExecuted($migrationClass, $batch)
  {
    return Capsule::table('migrations')->where('migration', $migrationClass)->where('batch', $batch)->exists();
  }

  /**
   * Elimina el registro de una migración específica.
   *
   * @param string $migrationClass El nombre de la clase de migración.
   * @return void
   *
   * Este método elimina el registro de una migración específica de la tabla de migraciones
   * en la base de datos, utilizando el nombre de la clase de migración como criterio de búsqueda.
   */
  private function removeMigrationRecord($migrationClass)
  {
    Capsule::table('migrations')->where('migration', $migrationClass)->delete();
  }

  /**
   * Obtiene las clases de migración.
   *
   * @return array
   *
   * Este método busca los archivos de migración en el directorio de migraciones,
   * los ordena por marca de tiempo en orden descendente y devuelve una lista de nombres
   * de clases de migración. Para cada archivo de migración, extrae el nombre de la clase
   * del nombre del archivo, incluye el archivo para asegurarse de que la clase esté cargada,
   * y verifica si la clase existe. Si la clase existe, la agrega a la lista de clases de migración.
   * Si no, muestra un mensaje indicando que la clase no se encontró en el archivo correspondiente.
   */
  private function getMigrationClasses()
  {
    $migrationFiles = glob(__DIR__ . '/../../../migrations/*.php');
    usort($migrationFiles, function ($a, $b) {
      // Extract timestamps from filenames
      $timestampA = substr(basename($a), 0, 17);
      $timestampB = substr(basename($b), 0, 17);
      return strcmp($timestampB, $timestampA);
    });

    $migrationClasses = [];

    foreach ($migrationFiles as $file) {
      // Extract the class name from the filename
      $fileName = basename($file, '.php');
      $parts = explode('_', $fileName);
      // Skip the timestamp part and concatenate the rest to form the class name
      $className = 'Migrations\\' . implode('', array_map('ucfirst', array_slice($parts, 4)));

      // Include the file to ensure the class is loaded
      require_once $file;

      if (class_exists($className)) {
        $migrationClasses[] = $className;
      } else {
        echo "Class $className not found in file $file\n";
      }
    }

    return $migrationClasses;
  }
}
