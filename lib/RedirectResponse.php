<?php

namespace Lib;

class RedirectResponse
{
  protected $url;
  protected $withFlash = [];
  protected $withInput = false;

  /**
   * Constructor de la clase RedirectResponse.
   *
   * @param string $url La URL a la que se redirigirá.
   *
   * Este constructor inicializa la URL de redirección.
   */
  public function __construct($url)
  {
    $this->url = $url;
  }

  /**
   * Añade mensajes flash a la respuesta de redirección.
   *
   * @param string $type El tipo de mensaje (por ejemplo, 'error', 'success').
   * @param array|string $message El contenido del mensaje.
   * @return $this
   *
   * Este método permite añadir mensajes flash que se mostrarán después de la redirección.
   * Los mensajes pueden ser de diferentes tipos, como 'error' o 'success'.
   */
  public function with($type, $message)
  {
    if (!is_array($message)) {
      $message = [$message];
    }
    $this->withFlash[$type] = $message;
    return $this;
  }

  /**
   * Indica que se debe incluir la entrada del usuario en la redirección.
   *
   * @return $this
   *
   * Este método permite que los datos de entrada del usuario se incluyan en la redirección,
   * lo que puede ser útil para preservar los datos del formulario en caso de error.
   */
  public function withInput()
  {
    $this->withInput = true;
    return $this;
  }

  /**
   *   Destructor de la clase RedirectResponse.
   * Este método se llama automáticamente cuando el objeto es destruido
   *  se usa destruct para que implicitamente se envíe el método send().
   * esto se une al método redirect en el helper .
   * ejemplo de uso en los controladores: redirect(self::HOME);
   * con metodos encadenados redirect('login')->with('error', $validator->errors());
   */

  public function __destruct()
  {
    $this->send();
  }

  /**
   * Envía la respuesta de redirección.
   *
   * Este método se encarga de enviar los mensajes flash, preservar los datos de entrada del usuario,
   * y redirigir a la URL especificada.
   */
  public function send()
  {
    if (!empty($this->withFlash)) {
      foreach ($this->withFlash as $type => $message) {
        $this->flash($type, $message);
      }
    }

    if ($this->withInput) {
      $_SESSION['old'] = $_POST;
    }

    header('Location: ' . BASE_URL . $this->url);
    exit;
  }

  /**
   * Añade un mensaje flash a la sesión.
   *
   * @param string $type El tipo de mensaje (por ejemplo, 'error', 'success').
   * @param array|string $message El contenido del mensaje.
   *
   * Este método añade un mensaje flash a la sesión, que se mostrará después de la redirección.
   */
  protected function flash($type, $message)
  {
    if (!isset($_SESSION['flash_messages'])) {
      $_SESSION['flash_messages'] = [];
    }

    if (!is_array($message)) {
      $message = [$message];
    }

    $_SESSION['flash_messages'][$type] = $message;
  }
}
