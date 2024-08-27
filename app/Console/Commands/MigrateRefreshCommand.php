<?php

namespace App\Console\Commands;

use App\Console\Command;
use Illuminate\Database\Capsule\Manager as Capsule;
use Lib\Auth;

class MigrateRefreshCommand extends Command
{
  /**
   * Maneja el comando para refrescar las migraciones.
   *
   * @param array $args Los argumentos pasados al comando.
   * @return void
   *
   * Este método verifica si la tabla de migraciones existe. Si no existe, muestra un mensaje
   * y termina la ejecución. Si la tabla de migraciones existe, revierte todas las migraciones,
   * elimina la tabla de migraciones, elimina tablas adicionales, limpia carpetas específicas,
   * copia archivos necesarios y finalmente ejecuta el comando de migración.
   */
  public function handle($args)
  {

    // Cerrar la sesión del usuario autenticado si existe
    Auth::logout();

    if (!$this->migrationsTableExists()) {
      echo "No se encontró ninguna tabla de migraciones. Primero ejecute 'php artisan migrate'." . PHP_EOL;
      return;
    }
    $this->rollbackAllMigrations();
    $this->dropMigrationsTable();
    $this->dropAdditionalTables();
    $this->cleanFolders();
    $this->copyFiles();
    $this->call('migrate');
  }


  /**
   * Verifica si la tabla de migraciones existe.
   *
   * @return bool
   *
   * Este método comprueba si la tabla de migraciones está presente en la base de datos.
   * Utiliza el esquema de Capsule para verificar la existencia de la tabla 'migrations'.
   */
  private function migrationsTableExists()
  {
    return Capsule::schema()->hasTable('migrations');
  }

  /**
   * Limpia las carpetas especificadas.
   *
   * @return void
   *
   * Este método obtiene una lista de carpetas a limpiar y luego itera sobre cada una de ellas,
   * llamando al método `cleanFolder` para realizar la limpieza de cada carpeta.
   */
  private function cleanFolders()
  {
    $foldersToClean = $this->getFoldersToClean();
    foreach ($foldersToClean as $folder) {
      $this->cleanFolder($folder);
    }
  }

  /**
   * Limpia una carpeta específica.
   *
   * @param string $folder La ruta de la carpeta a limpiar.
   * @return void
   *
   * Este método verifica si la ruta proporcionada es una carpeta. Si es así, obtiene todos los archivos
   * dentro de la carpeta y elimina cada archivo. Finalmente, muestra un mensaje indicando que la carpeta
   * ha sido limpiada.
   */
  private function cleanFolder($folder)
  {
    if (is_dir($folder)) {
      $files = glob($folder . '/*');
      foreach ($files as $file) {
        if (is_file($file)) {
          unlink($file);
        }
      }
      echo "Cleaned folder: " . $folder . PHP_EOL;
    }
  }

  private function copyFiles()
  {
    $filesToCopy = $this->getFilesToCopy();
    foreach ($filesToCopy as $source => $destination) {
      $this->copyFile($source, $destination);
    }
  }

  /**
   * Copia los archivos especificados a sus destinos.
   *
   * @return void
   *
   * Este método obtiene una lista de archivos a copiar y sus destinos correspondientes.
   * Luego, itera sobre cada par de origen y destino, llamando al método `copyFile` para
   * realizar la copia de cada archivo.
   */
  private function copyFile($source, $destination)
  {
    if (file_exists($source)) {
      if (copy($source, $destination)) {
        echo "Copied file: " . $source . " to " . $destination . PHP_EOL;
      } else {
        echo "Failed to copy file: " . $source . " to " . $destination . PHP_EOL;
      }
    } else {
      echo "Source file does not exist: " . $source . PHP_EOL;
    }
  }

  /**
   * Obtiene la lista de carpetas a limpiar.
   *
   * @return array
   *
   * Este método devuelve un array con las rutas de las carpetas que deben ser limpiadas.
   * Las rutas incluyen directorios como logs, cache, archive y modulos. Se pueden agregar
   * más carpetas según sea necesario.
   */
  private function getFoldersToClean()
  {
    return [
      __DIR__ . '/../../../logs',
      __DIR__ . '/../../../cache',
      __DIR__ . '/../../../public/archive',
      __DIR__ . '/../../../public/img/modulos',
      // Agrega más carpetas según sea necesario
    ];
  }

  /**
   * Obtiene la lista de archivos a copiar y sus destinos.
   *
   * @return array
   *
   * Este método devuelve un array asociativo donde las claves son las rutas de los archivos
   * de origen y los valores son las rutas de los archivos de destino. Los archivos especificados
   * incluyen una plantilla de carga masiva y una imagen general, que se copiarán a sus respectivas
   * ubicaciones en el directorio público.
   */
  private function getFilesToCopy()
  {
    return [
      __DIR__ . '/../../../storage/plantillaCargaMasiva.xlsx'
      => __DIR__ . '/../../../public/archive/plantillaCargaMasiva.xlsx',
      __DIR__ . '/../../../storage/general.jpg'
      => __DIR__ . '/../../../public/img/modulos/general.jpg',
    ];
  }

  /**
   * Llama a un comando registrado con argumentos opcionales.
   *
   * @param string $command El nombre del comando a ejecutar.
   * @param array $args Los argumentos opcionales para pasar al comando.
   * @return void
   *
   * Este método crea una instancia del registro de comandos, registra los comandos disponibles
   * (como 'migrate' y 'migrate:rollback'), y luego ejecuta el comando especificado con los argumentos
   * proporcionados.
   */
  private function call($command, $args = [])
  {
    $commandRegistry = new \App\Console\CommandRegistry();
    $commandRegistry->registerCommands([
      'migrate' => \App\Console\Commands\MigrateCommand::class,
      'migrate:rollback' => \App\Console\Commands\MigrateRollbackCommand::class
    ]);

    $commandRegistry->run($command, $args);
  }

  /**
   * Revierte todas las migraciones.
   *
   * @return void
   *
   * Este método obtiene todas las migraciones ordenadas por lote en orden descendente.
   * Luego, agrupa las migraciones por lote y llama al comando 'migrate:rollback' para
   * cada lote, revirtiendo así todas las migraciones en orden inverso.
   */
  private function rollbackAllMigrations()
  {
    $migrations = Capsule::table('migrations')->orderBy('batch', 'desc')->get();
    $batches = $migrations->pluck('batch')->unique()->sortDesc();

    foreach ($batches as $batch) {
      $this->call('migrate:rollback', ['--batch' => $batch]);
    }
  }

  /**
   * Elimina la tabla de migraciones.
   *
   * @return void
   *
   * Este método verifica si la tabla de migraciones existe en la base de datos.
   * Si existe, la elimina y muestra un mensaje indicando que la tabla de migraciones
   * ha sido eliminada.
   */
  private function dropMigrationsTable()
  {
    $schema = Capsule::schema();
    if ($schema->hasTable('migrations')) {
      $schema->drop('migrations');
      echo "La tabla de migraciones ha sido eliminada." . PHP_EOL;
    }
  }

  /**
   * Elimina tablas adicionales especificadas.
   *
   * @return void
   *
   * Este método obtiene el esquema de la base de datos y una lista de tablas que deben ser eliminadas.
   * Luego, itera sobre cada tabla en la lista y verifica si existe en la base de datos. Si la tabla
   * existe, la elimina y muestra un mensaje indicando que la tabla ha sido eliminada.
   */
  private function dropAdditionalTables()
  {
    $schema = Capsule::schema();
    $tablesToDrop = ['users', 'other_table', 'another_table'];
    // Añade aquí las tablas que necesites eliminar manualmente

    foreach ($tablesToDrop as $table) {
      if ($schema->hasTable($table)) {
        $schema->drop($table);
        echo "La tabla $table ha sido eliminada." . PHP_EOL;
      }
    }
  }
}
