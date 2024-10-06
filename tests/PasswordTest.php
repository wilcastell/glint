<?php

use PHPUnit\Framework\TestCase;
use Lib\Password;

class PasswordTest extends TestCase
{
  public function testMake()
  {
    $password = 'password123';
    $hashedPassword = Password::make($password);

    $this->assertTrue(password_verify($password, $hashedPassword), 'La contraseña encriptada no coincide con la contraseña original.');
  }

  public function testVerify()
  {
    $password = 'password123';
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $this->assertTrue(Password::verify($password, $hashedPassword), 'La contraseña no coincide con el hash encriptado.');
  }
}
