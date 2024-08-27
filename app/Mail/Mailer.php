<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Exceptions\MailException;

class Mailer
{
  protected $mail;

  /**
   * Constructor de la clase Mailer.
   * Inicializa una nueva instancia de PHPMailer y configura los ajustes iniciales.
   */
  public function __construct()
  {
    $this->mail = new PHPMailer(true);
    $this->setup();
  }

  /**
   * Configura los ajustes del correo electrónico.
   * Establece el uso de SMTP, el host, la autenticación, el usuario, la contraseña,
   * el tipo de seguridad, el puerto y la codificación.
   * También establece la dirección y el nombre del remitente.
   *
   * @throws MailException Si las variables de entorno MAIL_FROM_ADDRESS o MAIL_FROM_NAME no están configuradas.
   */
  protected function setup()
  {
    $this->mail->isSMTP();
    $this->mail->Host = getenv('MAIL_HOST');
    $this->mail->SMTPAuth = true;
    $this->mail->Username = getenv('MAIL_USERNAME');
    $this->mail->Password = getenv('MAIL_PASSWORD');
    $this->mail->SMTPSecure = 'tls';
    $this->mail->Port = getenv('MAIL_PORT');
    $this->mail->CharSet = 'UTF-8'; // Establecer la codificación a UTF-8

    $fromAddress = getenv('MAIL_FROM_ADDRESS');
    $fromName = getenv('MAIL_FROM_NAME');

    error_log("From Address: $fromAddress, From Name: $fromName");
    if (!$fromAddress || !$fromName) {
      throw new MailException(
        'Las variables de entorno MAIL_FROM_ADDRESS o MAIL_FROM_NAME no están configuradas.'
      );
    }
    $this->mail->setFrom((string)$fromAddress, (string)$fromName);
  }

  /**
   * Envía un correo electrónico.
   *
   * @param string $to Dirección de correo electrónico del destinatario.
   * @param string $subject Asunto del correo electrónico.
   * @param string $body Cuerpo del correo electrónico.
   */
  public function send($to, $subject, $body)
  {
    try {
      $this->mail->addAddress($to);
      $this->mail->Subject = $subject;
      $this->mail->Body    = $body;
      $this->mail->isHTML(true);
      $this->mail->send();
    } catch (Exception $e) {
      error_log("Error al enviar correo: {$this->mail->ErrorInfo}");
    }
  }
}
