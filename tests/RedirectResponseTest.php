<?php

use Lib\RedirectResponse;
use PHPUnit\Framework\TestCase;

class RedirectResponseTest extends TestCase
{
  /**
   * Configura el entorno de prueba antes de ejecutar cada test.
   */
  protected function setUp(): void
  {
    // Definir la constante BASE_URL para las pruebas
    if (!defined('BASE_URL')) {
      define('BASE_URL', getenv('BASE_URL'));
    }

    // Iniciar la sesión si no está iniciada
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    if (!function_exists('xdebug_get_headers')) {
      /**
       * Función simulada para satisfacer Intelephense.
       * Esta nunca se llamará porque se usará la función real si Xdebug está instalado.
       * @SuppressWarnings(php:S100)
       */
      function xdebug_get_headers()
      {
        return [];
      }
    }
  }

  /**
   * Cierra la sesión después de cada test.
   */
  protected function tearDown(): void
  {
    session_destroy();
  }

  /**
   * Test para obtener la URL de redirección.
   */
  public function testGetUrl()
  {
    $url = '/test-url';
    $response = new RedirectResponse($url);
    $this->assertEquals($url, $response->getUrl());
  }

  /**
   * Test para añadir un mensaje flash.
   */
  public function testWithFlashMessage()
  {
    $url = '/test-url';
    $response = new RedirectResponse($url);

    $response->with('success', 'Operation completed successfully');
    $flashMessages = $response->getFlashMessages();

    $this->assertArrayHasKey('success', $flashMessages);
    $this->assertEquals(['Operation completed successfully'], $flashMessages['success']);
  }

  /**
   * Test para añadir datos de entrada.
   */
  public function testWithInput()
  {
    $url = '/test-url';
    $response = new RedirectResponse($url);

    $response->withInput();

    $this->assertTrue($response->isWithInput());
  }

  /**
   * Test para enviar una redirección.
   */
  public function testSend()
  {
    $url = '/test-url';
    $response = new RedirectResponse($url);

    $response->send();

    $this->assertContains('Location: ' . BASE_URL . $url, xdebug_get_headers(), "La cabecera Location no fue encontrada.");
  }

  /**
   * Test para enviar una redirección.
   */
  public function testSendRedirectsToCorrectUrl()
  {
    $url = '/test-url';
    $response = new RedirectResponse($url);

    // Ejecuta el método send() sin que detenga el script
    $response->send();

    // Verifica si la cabecera de redirección fue enviada correctamente
    $headers = xdebug_get_headers();
    $this->assertContains('Location: ' . BASE_URL . $url, $headers, "La cabecera Location no fue encontrada.");
  }



}
