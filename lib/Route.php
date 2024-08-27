<?php

namespace Lib;

use App\Http\Request;

class Route
{
  private static $routes = [];
  private static $middlewares = [];

  /**
   * Registra una ruta GET.
   *
   * @param string $uri La URI de la ruta.
   * @param callable|array $callback La función o controlador que manejará la ruta.
   * @param array $middlewares Los middlewares que se ejecutarán antes de la ruta.
   */
  public static function get($uri, $callback, $middlewares = [])
  {
    self::addRoute('GET', $uri, $callback, $middlewares);
  }

  /**
   * Registra una ruta POST.
   *
   * @param string $uri La URI de la ruta.
   * @param callable|array $callback La función o controlador que manejará la ruta.
   * @param array $middlewares Los middlewares que se ejecutarán antes de la ruta.
   */
  public static function post($uri, $callback, $middlewares = [])
  {
    self::addRoute('POST', $uri, $callback, $middlewares);
  }

  /**
   * Registra una ruta PUT.
   *
   * @param string $uri La URI de la ruta.
   * @param callable|array $callback La función o controlador que manejará la ruta.
   * @param array $middlewares Los middlewares que se ejecutarán antes de la ruta.
   */
  public static function put($uri, $callback, $middlewares = [])
  {
    self::addRoute('PUT', $uri, $callback, $middlewares);
  }

  /**
   * Registra una ruta DELETE.
   *
   * @param string $uri La URI de la ruta.
   * @param callable|array $callback La función o controlador que manejará la ruta.
   * @param array $middlewares Los middlewares que se ejecutarán antes de la ruta.
   */
  public static function delete($uri, $callback, $middlewares = [])
  {
    self::addRoute('DELETE', $uri, $callback, $middlewares);
  }

  /**
   * Añade una ruta a la lista de rutas.
   *
   * @param string $method El método HTTP de la ruta (GET, POST, PUT, DELETE).
   * @param string $uri La URI de la ruta.
   * @param callable|array $callback La función o controlador que manejará la ruta.
   * @param array $middlewares Los middlewares que se ejecutarán antes de la ruta.
   */
  private static function addRoute($method, $uri, $callback, $middlewares)
  {
    $uri = trim($uri, '/');
    self::$routes[$method][$uri] = $callback;
    self::$middlewares[$method][$uri] = $middlewares;
  }


  /**
   * Dispara la solicitud HTTP a la ruta correspondiente.
   *
   * Este método busca la ruta que coincide con la URI solicitada y ejecuta los middlewares y el callback correspondiente.
   */
  public static function dispatch()
  {
    $uri = self::getRequestedUri();
    $method = $_SERVER['REQUEST_METHOD'];

    foreach (self::$routes[$method] as $route => $callback) {
      $routePattern = self::getRoutePattern($route);

      if (preg_match($routePattern, $uri, $matches)) {
        $params = self::extractParams($matches);

        self::runMiddlewares($method, $route);

        $request = new Request();
        $response = self::executeCallback($callback, $request, $params);

        self::sendResponse($response);
        return;
      }
    }
    echo '404 - Not Found';
  }

  /**
   * Envía la respuesta HTTP.
   *
   * @param mixed $response La respuesta a enviar.
   *
   * Este método envía la respuesta al cliente, ya sea en formato JSON o como texto plano.
   */
  private static function sendResponse($response)
  {
    if (is_array($response) || is_object($response)) {
      header('Content-Type: application/json');
      echo json_encode($response);
    } else {
      echo $response;
    }
  }

  /**
   * Obtiene la URI solicitada.
   *
   * @return string La URI solicitada.
   *
   * Este método obtiene la URI solicitada del servidor y la limpia de parámetros de consulta y el prefijo BASE_URL.
   */
  private static function getRequestedUri()
  {
    $uri = $_SERVER['REQUEST_URI'];

    if (strpos($uri, BASE_URL) === 0) {
      $uri = substr($uri, strlen(BASE_URL));
    }
    $uri = trim($uri, '/');

    if (strpos($uri, '?')) {
      $uri = substr($uri, 0, strpos($uri, '?'));
    }

    return $uri;
  }

  /**
   * Convierte una ruta en un patrón de expresión regular.
   *
   * @param string $route La ruta a convertir.
   * @return string El patrón de expresión regular.
   *
   * Este método convierte una ruta con parámetros en un patrón de expresión regular para la coincidencia de rutas.
   */
  private static function getRoutePattern($route)
  {
    $routePattern = preg_replace('#\{(\w+)\}#', '(?P<$1>[\w-]+)', $route);
    return '#^' . trim($routePattern, '/') . '$#';
  }

  /**
   * Extrae los parámetros de la URI coincidente.
   *
   * @param array $matches Las coincidencias de la expresión regular.
   * @return array Los parámetros extraídos.
   *
   * Este método extrae los parámetros de la URI coincidente y los devuelve como un array asociativo.
   */
  private static function extractParams($matches)
  {
    $params = [];
    foreach ($matches as $key => $value) {
      if (!is_int($key)) {
        $params[$key] = $value;
      }
    }
    return $params;
  }

  /**
   * Ejecuta los middlewares para una ruta específica.
   *
   * @param string $method El método HTTP de la ruta.
   * @param string $route La URI de la ruta.
   *
   * Este método ejecuta los middlewares registrados para una ruta específica antes de ejecutar el callback.
   */
  private static function runMiddlewares($method, $route)
  {
    if (isset(self::$middlewares[$method][$route])) {
      foreach (self::$middlewares[$method][$route] as $middleware => $middlewareParams) {
        if (is_string($middleware)) {
          $middleware::handle($middlewareParams);
        }
      }
    }
  }

  /**
   * Ejecuta el callback de una ruta.
   *
   * @param callable|array $callback La función o controlador que manejará la ruta.
   * @param Request $request La solicitud HTTP.
   * @param array $params Los parámetros de la ruta.
   * @return mixed La respuesta del callback.
   *
   * Este método ejecuta el callback de una ruta, ya sea una función o un método de un controlador.
   */
  private static function executeCallback($callback, $request, $params)
  {
    if (is_callable($callback)) {
      return call_user_func($callback, $request, ...array_values($params));
    } elseif (is_array($callback)) {
      $controller = new $callback[0];
      return call_user_func([$controller, $callback[1]], $request, ...array_values($params));
    }
    return null;
  }
}
