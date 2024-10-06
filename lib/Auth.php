<?php

namespace Lib;

use App\Models\User;

class Auth
{
  protected static $session;

  /**
   * Verifica si el usuario está autenticado.
   *
   * @return bool Devuelve true si el usuario está autenticado, de lo contrario false.
   */
  public static function check()
  {
    return isset($_SESSION['user_id']);
  }

  /**
   * Obtiene la información del usuario autenticado.
   *
   * @return object|null Devuelve un objeto con la información del usuario si está autenticado, de lo contrario null.
   */
  public static function user()
  {
    if (self::check()) {
      return (object) [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
      ];
    }

    return null;
  }

  /**
   * Requiere que el usuario esté autenticado.
   * Si el usuario no está autenticado, redirige a la página de inicio de sesión.
   */
  public static function requireAuth()
  {
    if (!self::check()) {
      header('Location: ' . DOMAIN . BASE_URL . 'login');
      exit;
    }
  }


  /**
   * Inicia sesión para el usuario dado.
   *
   * @param User $user El usuario que va a iniciar sesión.
   */
  public static function login(User $user)
  {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_role'] = $user->role;
  }

  /**
   * Cierra la sesión del usuario actual.
   */
  public static function logout()
  {
    session_unset();
    session_destroy();
  }

  /**
   * Verifica si el usuario autenticado tiene un rol específico.
   *
   * @param string $role El rol a verificar.
   * @return bool Devuelve true si el usuario tiene el rol especificado, de lo contrario false.
   */
  public static function hasRole($role)
  {
    $user = self::user();
    return $user && $user->role === $role;
  }

  /**
   * Verifica si la sesión del usuario ha expirado.
   * Si la sesión ha expirado, cierra la sesión y redirige a la página de inicio de sesión.
   */
  public static function checkSessionTimeout()
  {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
      self::logout();
      header('Location: ' . DOMAIN . BASE_URL . 'login');
      exit;
    }
    $_SESSION['last_activity'] = time();
  }
}
