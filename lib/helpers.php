<?php

function url(...$segments)
{
   $baseUrl = BASE_URL;
   $url = rtrim($baseUrl, '/') . '/';

   foreach ($segments as $segment) {
      $url .= trim($segment, '/') . '/';
   }

   return rtrim($url, '/');
}

function old($key, $default = null)
{
   if (session_status() == PHP_SESSION_NONE) {
      session_start();
   }
   return isset($_SESSION['old'][$key]) ? htmlspecialchars($_SESSION['old'][$key], ENT_QUOTES, 'UTF-8') : $default;
}

function error($key)
{
   return $_SESSION['errors'][$key] ?? null;
}

function generateCsrfToken()
{
   if (session_status() == PHP_SESSION_NONE) {
      session_start();
   }

   if (!isset($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   }

   return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token)
{
   if (session_status() == PHP_SESSION_NONE) {
      session_start();
   }

   return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function now()
{
   return date('Y-m-d H:i:s');
}

function response()
{
   return new class
   {
      public function json($data, $status = 200)
      {
         if (session_status() == PHP_SESSION_NONE) {
            session_start();
         }
         $_SESSION['response_message'] = $data;
         $_SESSION['response_status'] = $status;

         header('Content-Type: application/json', true, $status);
         echo json_encode($data);
         exit;
      }

      public function withStatus($status)
      {
         http_response_code($status);
         return $this;
      }
   };
}

function getResponseMessage()
{
   if (isset($_SESSION['response_message']) && isset($_SESSION['response_status'])) {
      $response = [
         'message' => $_SESSION['response_message'],
         'status' => $_SESSION['response_status']
      ];
      unset($_SESSION['response_message']); // Limpiar la variable de sesión
      unset($_SESSION['response_status']); // Limpiar la variable de sesión
      return $response;
   }
   return ['message' => null, 'status' => null];
}

//quitar espacios string y convertir a minusculas
function slug($string)
{
   $string = strtolower($string);
   $string = preg_replace('/\s+/', '-', $string);
   $string = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $string);
   return $string;
}

function isCurrentRoute($pattern)
{
   $currentUrl = str_replace(BASE_URL, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
   $pattern = str_replace('*', '.*', $pattern);
   return preg_match("#^{$pattern}$#", $currentUrl);
}

function formatId($id)
{
   return str_pad($id, 4, '0', STR_PAD_LEFT);
}

function cleanText($texto)
{
   $texto = trim($texto);
   return preg_replace('/[^A-Za-z0-9#ñÑáéíóúÁÉÍÓÚ()\- ]/u', '', $texto);
}

function checkAuth()
{
   return \Lib\Auth::check();
}

function auth()
{
   return \Lib\Auth::user();
}

function authRequire()
{
   \Lib\Auth::requireAuth();
}

function checkRole($role)
{
   $user = auth();
   return $user && $user->role === $role;
}

function isAdmin()
{
   $user = auth();
   return $user && $user->role === 'admin';
}

function isUser()
{
   $user = auth();
   return $user && $user->role === 'user';
}

function charAt($nombreUsuario = 'Invitado')
{
   return  substr($nombreUsuario, 0, 1);
}

function logout()
{
   \Lib\Auth::logout();
}

function getMes($date)
{
   $mesesEspanol = [
      1 => 'Enero',
      2 => 'Febrero',
      3 => 'Marzo',
      4 => 'Abril',
      5 => 'Mayo',
      6 => 'Junio',
      7 => 'Julio',
      8 => 'Agosto',
      9 => 'Septiembre',
      10 => 'Octubre',
      11 => 'Noviembre',
      12 => 'Diciembre'
   ];

   $month = date('n', strtotime($date));
   return $mesesEspanol[$month];
}

function publicPath($path = '')
{
   $publicDir = __DIR__ . '/../public';
   if ($path) {
      $publicDir .= '/' . ltrim($path, '/');
   }
   return $publicDir;
}

function redirect($url)
{
   return new \Lib\RedirectResponse($url);
}
