<?php

namespace App\Controllers;

use eftec\bladeone\BladeOne;
use Lib\RedirectResponse;

/**
 * Clase base Controller
 *
 * Esta clase proporciona funcionalidades comunes para los controladores de la aplicación,
 * incluyendo la gestión de vistas y redirecciones.
 * además configura el uso de BladeOne para las vistas
 *
 * @package App\Controllers
 */
class Controller
{
  protected $blade;
  const ENDIF = '<?php endif; ?>';

  /**
   * Constructor de la clase Controller
   *
   * Inicializa el motor de plantillas BladeOne y configura directivas personalizadas.
   * También inicia la sesión si no está ya iniciada.
   */
  public function __construct()
  {

    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    $views = realpath(__DIR__ . '/../../resources/views');
    $cache = realpath(__DIR__ . '/../../cache');

    // Limpieza del caché en desarrollo - comentar al pasar a producción
    array_map('unlink', glob("$cache/*"));

    $this->blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

    $this->blade->directive('csrf', function () {
      $inputField = '<input type="hidden" name="token_csrf" value="';
      $inputField .= htmlspecialchars(generateCsrfToken());
      $inputField .= '">';

      return "<?php echo '$inputField'; ?>";
    });

    $this->blade->directive('old', function ($expression) {
      return "<?php echo old($expression); ?>";
    });


    $this->blade->directive('auth', function () {
      return '<?php if (checkAuth()): ?>';
    });

    $this->blade->directive('endauth', function () {
      return self::ENDIF;
    });

    $this->blade->directive('role', function ($role) {
      return "<?php if (checkRole($role)): ?>";
    });

    $this->blade->directive('endrole', function () {
      return self::ENDIF;
    });

    $this->blade->directive('guest', function () {
      return '<?php if (!checkAuth()): ?>';
    });

    $this->blade->directive('endguest', function () {
      return self::ENDIF;
    });

    // Compartir la URL base actual con todas las vistas
    $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $this->blade->share('currentUrl', str_replace(BASE_URL, '', $currentUrl));
  }

  /**
   * Renderiza una vista.
   *
   * @param string $route La ruta de la vista en formato punto (e.g., 'auth.login').
   * @param array $data Los datos a pasar a la vista.
   * @return string El contenido renderizado de la vista o un mensaje de error si la vista no se encuentra.
   */
  public function view($route, $data = [])
  {
    $routeDir = str_replace('.', '/', $route);

    $viewPath = realpath(__DIR__ . "/../../resources/views/{$routeDir}.blade.php");

    if ($viewPath && file_exists($viewPath)) {
      return $this->blade->run($route, $data);
    } else {
      return "404 - Not Found";
    }
  }

  /**
   * Redirige a una URL dada.
   *
   * @param string $url La URL a la que redirigir.
   * @return RedirectResponse
   */
  public function redirect($url): RedirectResponse
  {
    return new RedirectResponse($url);
  }

  /**
   * Limpia los datos antiguos de la sesión.
   *
   * @return void
   */
  protected function clearOldInput()
  {
    unset($_SESSION['old']);
  }
}
