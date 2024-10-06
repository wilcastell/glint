<?php

use PHPUnit\Framework\TestCase;
use Lib\Auth;
use App\Models\User;

class AuthTest extends TestCase
{


  protected function setUp(): void
  {
    // Definir la constante BASE_URL
    if (!defined('BASE_URL')) {
      define('BASE_URL', getenv('BASE_URL'));
    }

    // Inicializa la sesión antes de cada prueba.
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

  }

  protected function tearDown(): void
  {
    // Limpia la sesión después de cada prueba.
    $_SESSION = [];
  }
  public function testCheckReturnsTrueIfUserIsLoggedIn()
  {
    // Simula que hay un usuario en sesión.
    $_SESSION['user_id'] = 1;

    // Verifica que el método check() devuelve true.
    $this->assertTrue(Auth::check());
  }

  public function testCheckReturnsFalseIfUserIsNotLoggedIn()
  {
    // Asegura que no haya usuario en la sesión.
    unset($_SESSION['user_id']);

    // Verifica que el método check() devuelve false.
    $this->assertFalse(Auth::check());
  }

  public function testUserReturnsUserObjectWhenLoggedIn()
  {
    // Simula que hay un usuario en sesión.
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'John Doe';
    $_SESSION['user_email'] = 'john@example.com';
    $_SESSION['user_role'] = 'admin';

    // Verifica que el método user() devuelve un objeto con los datos correctos.
    $user = Auth::user();
    $this->assertNotNull($user);
    $this->assertEquals(1, $user->id);
    $this->assertEquals('John Doe', $user->name);
    $this->assertEquals('john@example.com', $user->email);
    $this->assertEquals('admin', $user->role);
  }

  public function testUserReturnsNullWhenNotLoggedIn()
  {
    // Asegura que no haya usuario en la sesión.
    unset($_SESSION['user_id']);

    // Verifica que el método user() devuelve null.
    $this->assertNull(Auth::user());
  }

  public function testRequireAuthRedirectsToLoginIfNotLoggedIn()
  {
    // Asegura que no haya usuario en la sesión.
    unset($_SESSION['user_id']);

    // Limpia las cabeceras previamente enviadas
    if (headers_sent()) {
      throw new \App\Exceptions\ErrorException('Headers already sent');
    }

    // Inicia el buffer de salida
    ob_start();

    // Llama al método requireAuth
    Auth::requireAuth();

    // Limpia el buffer de salida
    ob_end_clean();

    // Captura las cabeceras enviadas
    $headers = function_exists('xdebug_get_headers') ? xdebug_get_headers() : headers_list();

    // Verifica que la redirección está presente en las cabeceras
    $expectedLocation = 'Location: /loginpf';
    $this->assertContains($expectedLocation, $headers);
  }

  public function testLoginStoresUserInfoInSession()
  {
    // Crea un mock de un usuario.
    $user = $this->createMock(User::class);
    $user->method('__get')->willReturnMap([
      ['id', 1],
      ['name', 'John Doe'],
      ['email', 'john@example.com'],
      ['role', 'admin']
    ]);

    // Llama al método login().
    Auth::login($user);

    // Verifica que la información del usuario está en la sesión.
    $this->assertEquals(1, $_SESSION['user_id']);
    $this->assertEquals('John Doe', $_SESSION['user_name']);
    $this->assertEquals('john@example.com', $_SESSION['user_email']);
    $this->assertEquals('admin', $_SESSION['user_role']);
  }

  public function testLogoutClearsSession()
  {
    // Simula que hay un usuario en sesión.
    $_SESSION['user_id'] = 1;

    // Llama al método logout().
    Auth::logout();

    // Verifica que la sesión está vacía.
    $this->assertEmpty($_SESSION);
  }

  public function testHasRoleReturnsTrueForCorrectRole()
  {
    // Simula que hay un usuario en sesión con el rol de 'admin'.
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'admin';
    $_SESSION['user_name'] = 'John Doe';
    $_SESSION['user_email'] = 'john@example.com';

    // Verifica que el método hasRole() devuelve true para el rol 'admin'.
    $this->assertTrue(Auth::hasRole('admin'));
  }

  public function testHasRoleReturnsFalseForIncorrectRole()
  {
    // Simula que hay un usuario en sesión con el rol de 'user'.
    $_SESSION['user_id'] = 1;
    $_SESSION['user_role'] = 'user';
    $_SESSION['user_name'] = 'John Doe';
    $_SESSION['user_email'] = 'john@example.com';

    // Verifica que el método hasRole() devuelve false para el rol 'admin'.
    $this->assertFalse(Auth::hasRole('admin'));
  }

  public function testCheckSessionTimeoutLogsOutUserIfSessionExpired()
  {
    // Simula que hay un usuario en sesión y que la última actividad fue hace más de 1 hora.
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'John Doe';
    $_SESSION['user_email'] = 'john@example.com';
    $_SESSION['user_role'] = 'admin';
    $_SESSION['last_activity'] = time() - 3601;

    // Inicia la captura del buffer de salida
    ob_start();

    // Llama a checkSessionTimeout para ejecutar la lógica de destrucción de sesión
    Auth::checkSessionTimeout();

    // Captura el contenido de la salida
    $output = ob_get_clean();

    // Muestra la salida capturada para debug
    echo $output;

    // Verifica que la salida contiene el mensaje "Session destroyed"
    $this->assertStringContainsString('Session destroyed', $output);

    // Reinicia la sesión para asegurarse de que está vacía
    session_write_close();
    session_start();

    // Verifica que la sesión ha sido destruida
    $this->assertEmpty($_SESSION);
  }

  public function testCheckSessionTimeoutUpdatesLastActivityIfNotExpired()
  {
    // Simula que hay un usuario en sesión y que la última actividad fue hace menos de 1 hora.
    $_SESSION['user_id'] = 1;
    $_SESSION['last_activity'] = time() - 1800;

    // Llama al método checkSessionTimeout().
    Auth::checkSessionTimeout();

    // Verifica que la última actividad se ha actualizado.
    $this->assertGreaterThan(time() - 1, $_SESSION['last_activity']);
  }
}
