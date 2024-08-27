<?php

namespace App\Console;

/**
 * Clase abstracta Command
 *
 * Esta clase define una estructura base para los comandos de consola en la aplicación.
 * Los comandos específicos deben extender esta clase y proporcionar una implementación
 * para el método abstracto `handle`.
 *
 * @package App\Console
 */

abstract class Command
{
  abstract public function handle($args);
}
