<?php

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{


  protected function setUp(): void
  {
    // Definir la constante BASE_URL
    if (!defined('BASE_URL')) {
      define('BASE_URL', getenv('BASE_URL'));
    }

    if (session_status() == PHP_SESSION_NONE) {
      session_start();

    }
  }

  protected function tearDown(): void
  {
    // Limpia la sesión después de cada prueba.
    $_SESSION = [];
  }


  public function testRedirect()
  {
    $response = redirect('login');
    $this->assertInstanceOf(\Lib\RedirectResponse::class, $response);
  }

  public function testOld()
  {
    $_SESSION['old']['username'] = 'testuser';
    $this->assertEquals('testuser', old('username'));
    $this->assertEquals('default', old('nonexistent', 'default'));
  }

  public function testGenerateCsrfToken()
  {
    $token = generateCsrfToken();
    $this->assertNotEmpty($token);
    $this->assertEquals($token, $_SESSION['csrf_token']);
  }

  public function testVerifyCsrfToken()
  {
    $_SESSION['csrf_token'] = 'testtoken';
    $this->assertTrue(verifyCsrfToken('testtoken'));
    $this->assertFalse(verifyCsrfToken('invalidtoken'));
  }

  public function testNow()
  {
    $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', now());
  }

  public function testSlug()
  {
    $this->assertEquals('hola-mundo', slug('Hola Mundo'));
    $this->assertEquals('a-e-i-o-u-n', slug('á é í ó ú ñ'));
  }

  public function testIsCurrentRoute()
  {
    $_SERVER['REQUEST_URI'] = '/admin/dashboard';
    $this->assertTrue(isCurrentRoute('/admin/*'));
    $this->assertFalse(isCurrentRoute('user/*'));
  }

  public function testFormatId()
  {
    $this->assertEquals('0123', formatId(123));
    $this->assertEquals('0001', formatId(1));
  }

  public function testCheckAuth()
  {
    $this->assertFalse(checkAuth());
  }

  public function testAuth()
  {
    $this->assertNull(auth());
  }


  public function testCheckRole()
  {
    $this->assertFalse(checkRole('admin'));
  }

  public function testIsAdmin()
  {
    $this->assertFalse(isAdmin());
  }

  public function testIsUser()
  {
    $this->assertFalse(isUser());
  }

  public function testCharAt()
  {
    $this->assertEquals('J', charAt('Juan Perez'));
    $this->assertEquals('I', charAt());
  }

  public function testLogout()
  {
    logout();
    $this->assertFalse(isset($_SESSION['user']));
  }

  public function testUrl()
  {
    $this->assertEquals('/admin/usuarios', url('/admin', 'usuarios'));
  }

  public function testCleanText()
  {
    /*solo se requiere quitar los acentos, para usar esos strings en clases css*/
    $this->assertEquals('123 áéíóú', cleanText('123 áéíóú%&'));
  }

  public function testGetMes()
  {
    $this->assertEquals('Enero', getMes('2023-01-15'));
    $this->assertEquals('Diciembre', getMes('2023-12-31'));
  }

  public function testPublicPath()
  {
    $expectedPath = dirname(__DIR__) . '/public/css/app.css';
    $this->assertEquals($expectedPath, publicPath('css/app.css'));
  }

  public function testResponse()
  {
    $response = response()->json(['message' => 'Test'], 200);
    $this->assertEquals(['message' => 'Test'], $_SESSION['response_message']);
    $this->assertEquals(200, $_SESSION['response_status']);
  }

  public function testGetResponseMessage()
  {
    $_SESSION['response_message'] = 'Test Message';
    $_SESSION['response_status'] = 201;
    $response = getResponseMessage();
    $this->assertEquals(['message' => 'Test Message', 'status' => 201], $response);
    $this->assertFalse(isset($_SESSION['response_message']));
    $this->assertFalse(isset($_SESSION['response_status']));
  }


}
