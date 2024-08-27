<?php

namespace App\Middleware;

use App\Models\User;
use Lib\Auth;

class RoleMiddleware
{
  /**
   * Maneja la verificación de roles para el usuario actual.
   *
   * Este método verifica si el usuario actual tiene uno de los roles permitidos.
   * Si no tiene un rol permitido, redirige al usuario a la página de inicio de sesión
   * o a una página de no autorizado.
   *
   * @param array|string $roles Los roles permitidos para acceder a la ruta.
   */

  const LOCATION = 'Location: ';

  public static function handle($roles)
  {


    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    Auth::checkSessionTimeout();

    $user = User::find($_SESSION['user_id'] ?? null);
    if (!$user) {
      Auth::logout();
      header(self::LOCATION . BASE_URL . '/login');
      exit;
    }

    if (!isset($_SESSION['user_id'])) {
      header(self::LOCATION . BASE_URL . '/login');
      exit;
    }

    $user = User::find($_SESSION['user_id']);

    // Asegúrate de que $roles es un array y verifica si el rol del usuario está en ese array
    if (!is_array($roles)) {
      $roles = [$roles];
    }

    if (!in_array($user->role, $roles)) {
      header(self::LOCATION . BASE_URL . '/auth/unauthorized');
      exit;
    }
  }
}
