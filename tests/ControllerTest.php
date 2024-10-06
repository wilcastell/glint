<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\Controller;
use Lib\RedirectResponse;

class ControllerTest extends TestCase
{
  protected $controller;

  protected function setUp(): void
  {
    parent::setUp();
    $_SERVER['REQUEST_URI'] = '/test-uri';

    if (!defined('BASE_URL')) {
      define('BASE_URL', '/formacion-arl');
    }

    $this->controller = new Controller();

  }

  public function testViewValid()
  {
    $viewContent = $this->controller->view('auth.login');
    $this->assertStringContainsString('<form', $viewContent); // Assuming the view contains a form
  }

  public function testViewInvalid()
  {
    // Simula una vista inválida
    $viewContent = $this->controller->view('invalid.view');
    $this->assertEquals('404 - Not Found', $viewContent);
  }

  public function testRedirect()
  {
    $url = 'http://example.com';
    $response = $this->controller->redirect($url);
    $this->assertInstanceOf(RedirectResponse::class, $response);
    $this->assertEquals($url, $response->getUrl());
  }

  public function testClearOldInput()
  {
    $_SESSION['old'] = ['data' => 'test'];
    $this->controller->clearOldInput();
    $this->assertArrayNotHasKey('old', $_SESSION);
  }

}
