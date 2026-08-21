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

// Bedient zwei Formulare: das Kontaktformular (kontakt.html) und das
// Frageformular am Ende der FAQ-Seite (faq.html). Das versteckte Feld
// "formsource" steuert nur, wohin im Fehlerfall zurueckgeleitet wird.
$sources     = ['kontakt' => 'kontakt.html', 'faq' => 'faq.html'];
$sourceKey   = isset($_POST['formsource']) && isset($sources[$_POST['formsource']]) ? $_POST['formsource'] : 'kontakt';
$errorTarget = $sources[$sourceKey];

function reject($target) {
    header('Location: ' . $target . '?message=Failed');
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
    reject($errorTarget);
}

$userName    = isset($_POST['username']) ? trim(preg_replace("/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['username'])) : "";
$senderEmail = isset($_POST['email'])    ? trim(preg_replace("/[^\.\-\_\@a-zA-Z0-9]/",    "", $_POST['email']))    : "";
$userPhone   = isset($_POST['phone'])    ? trim(preg_replace("/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone']))   : "";
$userSubject = isset($_POST['subject'])  ? trim(preg_replace("/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['subject'])) : "";
$message     = isset($_POST['message'])  ? trim(preg_replace("/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'])) : "";

// Telefon ist nur im Kontaktformular Pflicht - das FAQ-Formular soll moeglichst
// niedrigschwellig bleiben.
if (!$userName || !$senderEmail || !$message || !filter_var($senderEmail, FILTER_VALIDATE_EMAIL)) {
    reject($errorTarget);
}
if ($sourceKey === 'kontakt' && !$userPhone) {
    reject($errorTarget);
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

    if ($sourceKey === 'faq') {
        $mail->Subject = "Neue Frage \xc3\xbcber die FAQ-Seite von " . $userName . " \xe2\x80\x93 Ms Clean";
    } else {
        $mail->Subject = "Neue Kontaktanfrage von " . $userName . " \xe2\x80\x93 Ms Clean";
    }

    $body = "Name: " . $userName . "\n"
          . "E-Mail: " . $senderEmail . "\n";
    if ($userPhone) {
        $body .= "Telefon: " . $userPhone . "\n";
    }
    if ($userSubject) {
        $body .= "Betreff: " . $userSubject . "\n";
    }
    $body .= "\nNachricht:\n" . $message;

    $mail->Body = $body;

    $mail->send();
    header('Location: danke.html');
} catch (Exception $e) {
    reject($errorTarget);
}
