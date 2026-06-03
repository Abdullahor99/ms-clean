<?php

require_once __DIR__ . '/smtp-config.php';
require_once __DIR__ . '/phpmailer/Exception.php';
require_once __DIR__ . '/phpmailer/PHPMailer.php';
require_once __DIR__ . '/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

define('RECIPIENT_NAME',  'MS Clean');
define('RECIPIENT_EMAIL', 'kontakt@msclean-mannheim.de');

$userName    = isset($_POST['username']) ? preg_replace("/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['username']) : "";
$senderEmail = isset($_POST['email'])    ? preg_replace("/[^\.\-\_\@a-zA-Z0-9]/",    "", $_POST['email'])    : "";
$userPhone   = isset($_POST['phone'])    ? preg_replace("/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone'])   : "";
$message     = isset($_POST['message'])  ? preg_replace("/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message']) : "";

if ($userName && $senderEmail && $userPhone && $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host        = SMTP_HOST;
        $mail->SMTPAuth    = SMTP_AUTH;
        $mail->Username    = SMTP_USER;
        $mail->Password    = SMTP_PASSWORD;
        $mail->SMTPSecure  = SMTP_SECURE;
        $mail->SMTPAutoTLS = false;
        $mail->Port        = SMTP_PORT;
        $mail->CharSet     = 'UTF-8';

        $mail->setFrom(SMTP_FROM, 'MS Clean Website');
        $mail->addReplyTo($senderEmail, $userName);
        $mail->addAddress(RECIPIENT_EMAIL, RECIPIENT_NAME);

        $mail->Subject = "Neue Kontaktanfrage von " . $userName . " \xe2\x80\x93 Ms Clean";
        $mail->Body    = "Name: "    . $userName    . "\n"
                       . "E-Mail: "  . $senderEmail . "\n"
                       . "Telefon: " . $userPhone   . "\n\n"
                       . "Nachricht:\n" . $message;

        $mail->send();
        header('Location: contact.html?message=Erfolgreich');
    } catch (Exception $e) {
        header('Location: contact.html?message=Failed');
    }
} else {
    header('Location: contact.html?message=Failed');
}
