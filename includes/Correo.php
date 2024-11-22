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
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Subject = $titulo;
            $this->mail->Body    = $mensaje;

            $this->mail->AltBody = strip_tags($mensaje); 

            $this->mail->send();
            echo 'Correo enviado';
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$this->mail->ErrorInfo}";
        }
    }

    public function correoAT($idReporte, $bandP){
        $correo = new Correo();

        $destinatario = decryptData($correoD);
        $titulo = "Confirmación de Recepción de Tu reporte - $idReporte";
        $mensaje = "Estimado/a Denunciante,\n
        Gracias por confiar en nuestra plataforma para reportar tu caso. Este correo es para confirmarte que hemos recibido correctamente tu denuncia con el número de caso $idReporte.\n
        Queremos reiterarte que tu reporte será tratado con la máxima confidencialidad y que nuestro equipo se encargará de analizarlo de manera justa y profesional. En esta etapa inicial, nuestro equipo está revisando los detalles proporcionados para evaluar las medidas necesarias y los próximos pasos a seguir.\n\n
        ¿Qué puedes esperar ahora?\n
        •	Evaluación inicial: Uno de nuestros especialistas revisará tu caso para determinar las acciones inmediatas necesarias, si aplican.\n
        •	Seguimiento: Te mantendremos informado/a sobre el progreso del caso según el canal de comunicación que elegiste al momento del registro.\n
        •	Apoyo: Si necesitas asistencia adicional, puedes comunicarte con nuestro equipo al correo alertateclag@gmail.com /n/n";

        if($bandP == "Sí"){
            $mensaje += "Apoyo Psicológico.\n
            Hemos registrado que solicitaste asistencia psicológica. Nuestro equipo especializado se pondrá en contacto contigo en las próximas [X horas/días] para coordinar el apoyo que necesites. Si tienes alguna preferencia respecto al horario o el medio de contacto, por favor háznoslo saber respondiendo a este correo.\n";
        }

        $mensaje += "Estamos aquí para apoyarte en este proceso y asegurar que se respete tu integridad y bienestar en todo momento.\n/n
        Habla, nosotros te respaldamos.\n\n
        Atentamente,\n
        Equipo AlertaTec\n
        Instituto Tecnológico de la Laguna\n
        alertateclag@gmail.com | [Teléfono] | [Página Web, si aplica]";

        $correo->enviarCorreo($destinatario, $titulo, $mensaje);

        
    }
}