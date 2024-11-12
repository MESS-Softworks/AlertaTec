<?php
use PHPmailer\PHPMailer\PHPMailer;
use PHPmailer\PHPMailer\SMTP;
use PHPmailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/PHPMailer.php';

//Correo Actualizado
class Correo {
    private $mail;
    
    public function __construct(){
        $this->mail = new PHPMailer(true);

    try{
        //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username   = 'alertateclag@gmail.com'; 
        $this->mail->Password   = 'wifpbuxjfmepskei'; 
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        
        $this->mail->setFrom('alertateclag@gmail.com', 'ALERTATEC');
       
    }catch(Exception $e){
        echo 'Mensaje '. $this->mail->ErrorInfo;
    }
  }

  public function enviarCorreo($destinatario, $titulo, $mensaje) {
    try {
        $this->mail->clearAddresses(); // Limpiar direcciones anteriores
        $this->mail->addAddress($destinatario); 
        $this->mail->isHTML(true);    
        $this->mail->Subject = $titulo;
        $this->mail->Body    = $mensaje;

        $this->mail->send();
        echo 'Correo enviado';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$this->mail->ErrorInfo}";
    }
}
}