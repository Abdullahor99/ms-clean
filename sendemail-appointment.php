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

function reject() {
    header('Location: angebot-anfordern.html?status=Fehler');
    exit;
}

// --- Spamschutz -----------------------------------------------------------
// 1. Honeypot: fuer Menschen per CSS unsichtbar, Bots fuellen es aus.
if (!empty($_POST['website'])) {
    // Bots bekommen dieselbe Antwort wie ein Erfolg, damit sie nicht lernen.
    header('Location: danke.html');
    exit;
}

// 2. Zeitfalle: ein echtes Formular wird nicht in unter 3 Sekunden ausgefuellt.
$formTs = isset($_POST['form_ts']) ? (int) $_POST['form_ts'] : 0;
if ($formTs <= 0 || (time() - $formTs) < 3 || (time() - $formTs) > 86400) {
    header('Location: danke.html');
    exit;
}

// --- Pflichtfelder --------------------------------------------------------
// Einwilligung nach DSGVO
if (empty($_POST['privacy'])) {
    reject();
}

$userName    = isset($_POST['username']) ? trim(preg_replace("/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['username'])) : "";
$senderEmail = isset($_POST['email'])    ? trim(preg_replace("/[^\.\-\_\@a-zA-Z0-9]/",    "", $_POST['email']))    : "";
$userPhone   = isset($_POST['phone'])    ? trim(preg_replace("/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone']))   : "";
$message     = isset($_POST['message'])  ? trim(preg_replace("/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'])) : "";

if (!$userName || !$senderEmail || !$userPhone || !$message || !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    reject();
}

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

    $mail->Subject = "Neue Angebotsanfrage von " . $userName . " \xe2\x80\x93 Ms Clean";
    $mail->Body    = "Name: "    . $userName    . "\n"
                   . "E-Mail: "  . $senderEmail . "\n"
                   . "Telefon: " . $userPhone   . "\n\n"
                   . "Anfrage:\n" . $message;

    $mail->send();
    header('Location: danke.html');
} catch (Exception $e) {
    reject();
}
