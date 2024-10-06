<?php

namespace Tests;

use Lib\Logger;
use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias para la clase Logger.
 */
class LoggerTest extends TestCase
{
  private $defaultLogFile;
  private $customLogFile;

  /**
   * Configura el entorno de prueba eliminando los archivos de log si existen.
   */
  protected function setUp(): void
  {
    $this->defaultLogFile = __DIR__ . '/../logs/app.log';
    $this->customLogFile = __DIR__ . '/../logs/custom.log';

    if (file_exists($this->defaultLogFile)) {
      unlink($this->defaultLogFile);
    }
    if (file_exists($this->customLogFile)) {
      unlink($this->customLogFile);
    }
  }

  /**
   * Prueba que el método log crea un archivo de log predeterminado.
   */
  public function testLogCreatesDefaultFile()
  {
    Logger::log('Mensaje de prueba');
    $this->assertFileExists($this->defaultLogFile);
  }

  /**
   * Prueba que el método log crea un archivo de log personalizado.
   */
  public function testLogCreatesCustomFile()
  {
    Logger::log('Mensaje de prueba', 'INFO', $this->customLogFile);
    $this->assertFileExists($this->customLogFile);
  }

  /**
   * Prueba que el método log escribe un mensaje en el archivo de log predeterminado.
   */
  public function testLogWritesMessageToDefaultFile()
  {
    $mensaje = 'Este es un mensaje de prueba';
    Logger::log($mensaje);
    $contenido = file_get_contents($this->defaultLogFile);
    $this->assertStringContainsString($mensaje, $contenido);
  }

  /**
   * Prueba que el método log escribe un mensaje en el archivo de log personalizado.
   */
  public function testLogWritesMessageToCustomFile()
  {
    $mensaje = 'Este es un mensaje de prueba personalizado';
    Logger::log($mensaje, 'INFO', $this->customLogFile);
    $contenido = file_get_contents($this->customLogFile);
    $this->assertStringContainsString($mensaje, $contenido);
  }

  /**
   * Prueba que el método log incluye una marca de tiempo y nivel en el mensaje.
   */
  public function testLogIncludesTimestampAndLevel()
  {
    Logger::log('Mensaje con marca de tiempo', 'WARNING');
    $contenido = file_get_contents($this->defaultLogFile);
    $this->assertMatchesRegularExpression('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]\[WARNING\]/', $contenido);
  }

  /**
   * Prueba que múltiples mensajes se añaden al archivo de log.
   */
  public function testMultipleLogsAppend()
  {
    Logger::log('Primer mensaje');
    Logger::log('Segundo mensaje');
    $contenido = file_get_contents($this->defaultLogFile);
    $this->assertStringContainsString('Primer mensaje', $contenido);
    $this->assertStringContainsString('Segundo mensaje', $contenido);
  }

  /**
   * Prueba que se lanza una excepción con un nivel de log inválido.
   */
  public function testInvalidLogLevelThrowsException()
  {
    $this->expectException(\InvalidArgumentException::class);
    Logger::log('Mensaje con nivel inválido', 'NIVEL_INVALIDO');
  }

  /**
   * Prueba que el método clear elimina el archivo de log predeterminado.
   */
  public function testClearRemovesDefaultLogFile()
  {
    Logger::log('Mensaje de prueba');
    $this->assertFileExists($this->defaultLogFile);
    Logger::clear();
    $this->assertFileDoesNotExist($this->defaultLogFile);
  }

  /**
   * Prueba que el método clear elimina un archivo de log personalizado.
   */
  public function testClearRemovesCustomLogFile()
  {
    Logger::log('Mensaje de prueba', 'INFO', $this->customLogFile);
    $this->assertFileExists($this->customLogFile);
    Logger::clear($this->customLogFile);
    $this->assertFileDoesNotExist($this->customLogFile);
  }

  /**
   * Limpia los archivos de log después de cada prueba.
   */
  protected function tearDown(): void
  {
    if (file_exists($this->defaultLogFile)) {
      unlink($this->defaultLogFile);
    }
    if (file_exists($this->customLogFile)) {
      unlink($this->customLogFile);
    }
  }
}
