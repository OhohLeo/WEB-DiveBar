<?php

class TestContact extends PHPUnit_Framework_TestCase
{
    function post($url, $data)
    {
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
    }

    public function test_contact()
    {
	$this->assertEquals(
	    $this->post("http://localhost/divebar/php/contact.php", ""),
	    "Nous sommes désolés, mais tous les champs sont requis!\n");

	$this->assertEquals(
	    $this->post("http://localhost/divebar/php/contact.php",
			"contact_email=lmartin@jojo.com"),
	    "Nous sommes désolés, mais tous les champs sont requis!");

	$this->assertEquals(
	    $this->post("http://localhost/divebar/php/contact.php",
			"contact_name=leo&contact_email=lmartin.com&contact_msg=test"),
	    "Nous avons détecté des erreurs dans le formulaire!<br />Voici la liste des erreurs:<br /><br />L'adresse email ne semble pas valide.<br /><br /><br />");

	$this->assertEquals(
	    $this->post("http://localhost/divebar/php/contact.php",
			"contact_name=leo&contact_email=lmartin@gmail.com&contact_msg=t"),
	    "Nous avons détecté des erreurs dans le formulaire!<br />Voici la liste des erreurs:<br /><br />Veuillez écrire votre message.<br /><br /><br />");

	$this->assertEquals(
	    $this->post("http://localhost/divebar/php/contact.php",
			"contact_name=leo&contact_email=lmartin@gmail.com&contact_msg=test"),
	    "Echec d'envoi de l'email!");
    }
}

?>
