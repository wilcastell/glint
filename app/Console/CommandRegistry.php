<?php

namespace App\Console;

class CommandRegistry
{
  protected $commands = [];

  /**
   * Registra una lista de comandos.
   *
   * @param array $commands Un array asociativo donde las claves son los nombres de los comandos
   *                        y los valores son las clases correspondientes a esos comandos.
   * @return void
   *
   * Este método toma un array de comandos y los almacena en la propiedad $commands.
   */
  public function registerCommands(array $commands)
  {
    $this->commands = $commands;
  }

  /**
   * Ejecuta un comando registrado.
   *
   * @param string $command El nombre del comando a ejecutar.
   * @param array $args Los argumentos a pasar al comando.
   * @return void
   *
   * Este método verifica si el comando especificado está registrado. Si lo está, crea una instancia
   * de la clase del comando y llama a su método handle, pasándole los argumentos. Si el comando no
   * está registrado, muestra un mensaje indicando que el comando no se encontró.
   */
  public function run($command, $args)
  {
    if (isset($this->commands[$command])) {
      $commandClass = new $this->commands[$command]();
      $commandClass->handle($args);
    } else {
      echo "Command not found: $command" . PHP_EOL;
    }
  }
}
