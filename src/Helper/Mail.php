<?php
namespace Helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{

  public static function send($repository, $subject ,$message){

    $mail = new PHPMailer(true);

    try {

        $mail->SMTPDebug  = false;
        $mail->Host       = $_ENV['SMTP'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->Subject    = $repository." - WebHook Push Event";
        $mail->Body       = '<h3>'.$subject.'</h3><pre>'.$message.'</pre>';
        $mail->CharSet    = 'UTF-8';
        $mail->Encoding   = 'base64';

        $mail->isSMTP();
        $mail->setFrom($_ENV['SMTP_USER'], 'Kuvvu GmbH');
        $mail->addAddress($_ENV['EMAIL']);
        $mail->isHTML(true);

        $mail->send();

    } catch (Exception $e) {

       echo "Error: Mail \n";
       print_r($e);

    }

  }

}
