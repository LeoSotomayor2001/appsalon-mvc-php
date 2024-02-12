<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $token;
    public $nombre;

    public function __construct($email,$nombre,$token)
    {
        $this->email=$email;
        $this->nombre=$nombre;
        $this->token=$token;
    }
    public function enviarConfirmacion(){
        //Crear el objeto de email
        $mail=new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','Appsalon.com');
        $mail->Subject="Confirma tu cuenta";

        //set html
        $mail->isHTML(true);
        $mail->CharSet='UTF-8';

        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $this->nombre ."</strong> has creado tu cuenta en Appsalon, solo debes
        confirmarla presionando el siguiente enlace</p>";
        $contenido.="<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/confirmar-cuenta?token="
        . $this->token . "'>Confirmar Cuenta</a>  </p>";
        $contenido.="Si tu no solicitaste esta cuenta, puedes ignorar el mensaje";
        $contenido.="</html>";
        $mail->Body=$contenido;

        //Enviar el email
        $mail->send();

    }
    public function enviarInstrucciones(){
        //Crear el objeto de email
        $mail=new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com','Appsalon.com');
        $mail->Subject="Restablece tu password";

        //set html
        $mail->isHTML(true);
        $mail->CharSet='UTF-8';

        $contenido="<html>";
        $contenido.="<p><strong>Hola ". $this->nombre ."</strong> has solicitado restablecer
        tu password,sigue el siguiente enlace para hacerlo</p>";
        $contenido.="<p>Presiona aquí: <a href='". $_ENV['APP_URL'] ."/recuperar?token="
        . $this->token . "'>Restablecer password</a>  </p>";
        $contenido.="Si tu no solicitaste esta acción, puedes ignorar el mensaje";
        $contenido.="</html>";
        $mail->Body=$contenido;

        //Enviar el email
        $mail->send();
    }

}