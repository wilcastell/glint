<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Database\Capsule\Manager as Capsule;

class MigrateCommand extends Command
{
  /**
   * Maneja el comando para ejecutar las migraciones.
   *
   * @param array $args Los argumentos pasados al comando.
   * @return void
   *
   * Este método se asegura de que la tabla de migraciones exista, obtiene las clases de migración,
   * y ejecuta cada migración que no haya sido ejecutada previamente. Registra cada migración
   * ejecutada y muestra mensajes en la consola.
   */
  public function handle($args)
  {
    // Asegurarse de que la tabla de migraciones existe
    $this->ensureMigrationsTableExists();

    $migrations = $this->getMigrationClasses();

    foreach ($migrations as $migrationClass) {
      if (!$this->migrationExecuted($migrationClass)) {
        /** @var \Migrations\Migration $migration */
        $migration = new $migrationClass;
        $migration->up();
        $this->recordMigration($migrationClass);
        echo "Migrated: " . $migrationClass . PHP_EOL;
      } else {
        echo "Migration already executed: " . $migrationClass . PHP_EOL;
      }
    }

    echo "All migrations completed." . PHP_EOL;
  }

  /**
   * Asegura que la tabla de migraciones exista.
   *
   * @return void
   *
   * Este método verifica si la tabla de migraciones existe en la base de datos.
   * Si no existe, la crea con las columnas necesarias.
   */
  private function ensureMigrationsTableExists()
  {
    if (!Capsule::schema()->hasTable('migrations')) {
      Capsule::schema()->create('migrations', function ($table) {
        $table->increments('id');
        $table->string('migration');
        $table->integer('batch');
        $table->timestamps();
      });
    }
  }

  /**
   * Verifica si una migración ya ha sido ejecutada.
   *
   * @param string $migrationClass El nombre de la clase de migración.
   * @return bool
   *
   * Este método consulta la tabla de migraciones para verificar si una migración
   * específica ya ha sido ejecutada.
   */
  private function migrationExecuted($migrationClass)
  {
    return Capsule::table('migrations')->where('migration', $migrationClass)->exists();
  }

  /**
   * Registra una migración como ejecutada.
   *
   * @param string $migrationClass El nombre de la clase de migración.
   * @return void
   *
   * Este método inserta un registro en la tabla de migraciones para indicar que
   * una migración específica ha sido ejecutada.
   */
  private function recordMigration($migrationClass)
  {
    Capsule::table('migrations')->insert([
      'migration' => $migrationClass,
      'batch' => Capsule::table('migrations')->max('batch') + 1,
    ]);
  }

  /**
   * Obtiene las clases de migración.
   *
   * @return array
   *
   * Este método busca los archivos de migración en el directorio de migraciones,
   * los ordena por marca de tiempo y devuelve una lista de nombres de clases de migración.
   */
  private function getMigrationClasses()
  {
    $migrationFiles = glob(__DIR__ . '/../../../migrations/*.php');
    usort($migrationFiles, function ($a, $b) {
      // Extract timestamps from filenames
      $timestampA = substr(basename($a), 0, 17);
      $timestampB = substr(basename($b), 0, 17);
      return strcmp($timestampA, $timestampB);
    });

    $migrationClasses = [];

    foreach ($migrationFiles as $file) {
      // Extract the class name from the filename
      $fileName = basename($file, '.php');
      $parts = explode('_', $fileName);
      // Skip the timestamp part and concatenate the rest to form the class name
      $className = 'Migrations\\' . implode('', array_map('ucfirst', array_slice($parts, 4)));

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
