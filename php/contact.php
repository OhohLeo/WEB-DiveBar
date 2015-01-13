<?php

require_once 'swiftmailer/lib/swift_required.php';

if(isset($_POST['contact_email'])) {

    $email_to = "divebartheband@gmail.com";
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

    $name = $_POST['contact_name']; // required
    $email_from = $_POST['contact_email']; // required
    $comments = $_POST['contact_msg']; // required

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

    $email_message = "Voici le nouveau message:\n\n";

    function clean_string($string) {
	$bad = array("content-type","bcc:","to:","cc:","href");
	return str_replace($bad,"",$string);
    }

    $email_message .= "Name: " . clean_string($name) . "\n";
    $email_message .= "Email: " . clean_string($email_from) . "\n";
    $email_message .= "Message: " . clean_string($comments) . "\n";

    // create email headers
    $headers = "From: " . $email_from . "\r\n"
	     . "Reply-To: " . $email_from . "\r\n"
	     . "MIME-Version: 1.0\r\n"
	     . "X-Priority: 3\r\n"
	     . 'X-Mailer: PHP/' . phpversion();


    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
                   ->setUsername($email_to)
		   ->setPassword('');

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance($email_subject)
		   ->setFrom(array($email_to => $email_subject))
		   ->setTo(array($email_to))
		   ->setBody($email_message);

    $result = $mailer->send($message);

    die();
}

echo "Nous sommes désolés, mais tous les champs sont requis!";

?>

