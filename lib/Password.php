<?php

namespace Lib;

class Password
{
  /**
   * Encriptar el password.
   *
   * @param string $password La contraseña que se desea encriptar.
   * @return string La contraseña encriptada.
   *
   * Este método utiliza el algoritmo BCRYPT para encriptar la contraseña.
   * BCRYPT es un algoritmo de hashing adaptativo que utiliza un salt para proteger contra ataques de fuerza bruta.
   */
  public static function make($password)
  {
    return password_hash($password, PASSWORD_BCRYPT);
  }

  /**
   * Verificar el password.
   *
   * @param string $password La contraseña sin encriptar que se desea verificar.
   * @param string $hash El hash de la contraseña encriptada.
   * @return bool Retorna true si la contraseña coincide con el hash, de lo contrario retorna false.
   *
   * Este método verifica si la contraseña proporcionada coincide con el hash encriptado utilizando el algoritmo BCRYPT.
   */
  public static function verify($password, $hash)
  {
    return password_verify($password, $hash);
  }
}
