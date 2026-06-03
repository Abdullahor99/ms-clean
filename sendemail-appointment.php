<?php

// Define some constants
define( "RECIPIENT_NAME", "Abdullah Orabi" );
define( "RECIPIENT_EMAIL", "kontakt@msclean-mannheim.de" );


// Read the form values
$success = false;
$userName = isset( $_POST['username'] ) ? preg_replace( "/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['username'] ) : "";
$senderEmail = isset( $_POST['email'] ) ? preg_replace( "/[^\.\-\_\@a-zA-Z0-9]/", "", $_POST['email'] ) : "";
$userPhone = isset( $_POST['phone'] ) ? preg_replace( "/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['phone'] ) : "";
$userSubject = isset( $_POST['subject'] ) ? preg_replace( "/[^\s\S\.\-\_\@a-zA-Z0-9]/", "", $_POST['subject'] ) : "";
$message = isset( $_POST['message'] ) ? preg_replace( "/(From:|To:|BCC:|CC:|Subject:|Content-Type:)/", "", $_POST['message'] ) : "";

// If all values exist, send the email
if ( $userName && $senderEmail && $userPhone && $message) {
  $recipient = RECIPIENT_NAME . " <" . RECIPIENT_EMAIL . ">";
  $subject   = "Neue Angebotsanfrage von " . $userName . " \xe2\x80\x93 Ms Clean";
  $headers   = "From: Ms Clean Website <noreply@ms-clean.de>\r\n"
             . "Reply-To: " . $senderEmail . "\r\n"
             . "Content-Type: text/plain; charset=UTF-8\r\n";
  $msgBody   = "Name: "    . $userName    . "\n"
             . "E-Mail: "  . $senderEmail . "\n"
             . "Telefon: " . $userPhone   . "\n\n"
             . "Anfrage:\n" . $message;
  $success = mail( $recipient, $subject, $msgBody, $headers );

  if ( $success ) {
    header('Location: appointment.html?status=Erfolgreich');
  } else {
    header('Location: appointment.html?status=Fehler');
  }
}

else{
	//Set Location After Unsuccessful Submission
  	header('Location: appointment.html?status=Fehler');
}

?>
