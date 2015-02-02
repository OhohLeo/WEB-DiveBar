<?php

if(isset($_POST['contact_email'])) {

    $email_to = "%MAIL%";
    $email_subject = "Site Web Dive Bar : nouveau message!!";

    function died($error) {
	echo "Nous avons détecté des erreurs dans le formulaire!<br />"
		 . "Voici la liste des erreurs:<br /><br />"
		 . $error."<br /><br />";
	die();
    }

    // validation expected data exists
    if(!isset($_POST['contact_name']) ||
       !isset($_POST['contact_email']) ||
       !isset($_POST['contact_msg'])) {
	echo "Nous sommes désolés, mais tous les champs sont requis!";
	die();
    }

    $name = urldecode($_POST['contact_name']); // required
    $email_from = urldecode($_POST['contact_email']); // required
    $comments = urldecode($_POST['contact_msg']); // required

    $error_message = "";

    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if(!preg_match($email_exp, $email_from)) {
	$error_message .= 'L\'adresse email ne semble pas valide.<br />';
    }

    $string_exp = "/^[A-Za-z .'-]+$/";

    if(strlen($comments) < 2) {
	$error_message .= 'Veuillez écrire votre message.<br />';
    }

    if(strlen($error_message) > 0) {
	died($error_message);
    }

    $email_message = "Name: " . $name . "\n";
    $email_message .= "Email: " . $email_from . "\n";
    $email_message .= "Message: " . $comments . "\n";

    if (mail($email_to, $email_subject, $email_message))
    {
	echo "Merci! Nous vous recontacterons sous peu!";
    }
    else
    {
	echo "Echec d'envoi de l'email: " . $mail->ErrorInfo;
    }

    die();
}

echo "Nous sommes désolés, mais tous les champs sont requis!";

?>
