<?php

require("conf/cfg-phpinfo.inc.php3");
function EmailOK($email) 
{
    return( ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
                 '@'.
                 '([-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]{2,}\.){1,3}'.
                 '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]{2,3}$',
                 $email) );
}

//-----------------
// LIVRE D'OR
//-----------------

/* DebutTableau("#FFFFFF", "1", "0", "100%");
DebutTableau("#B8C8FE", "10", "0", "100%"); */

/* echo Titre("Le Livre d'Or");
echo "<HR COLOR='5A6BA5' NOSHADE>\n"; */

$ok = true;

// Vérification de la connexion MySQL
if ( !($connecte = @mysql_connect($cfgHote, $cfgUser, $cfgPass)) ) echo erreurServeurMySQL();


// ---------------------------------------------------------------------------
// Insérer dans la base une nouvelle signature
// ---------------------------------------------------------------------------
if ($connecte && $rub == 'ajouter') {
  
  $Nom     = trim($ztNom);
  $Email   = trim($ztEmail);
  $Comment = trim($ztComment);
  $Date    = date("Y/m/d H:i");

  if ($Nom == "" || $Comment == "") {
    $ok = false;
    $msg = "Les champs 'Nom' et 'Commentaire' sont obligatoires !";
  } elseif ($Email != '' && !EmailOK($Email)) {
    $ok = false;
    $msg = "Adresse email invalide !";
  }

  if (!$ok) {
    echo "<CENTER><A CLASS='erreur'>&nbsp;$msg&nbsp;</A></CENTER><BR>";
	$rub = 'signer';
  } else {
    
	$liste_champs = 'date, nom, email, commentaire';
    $liste_valeurs = "'$Date', '$Nom', '$Email', '$Comment'";
  
	$sql = "INSERT INTO livredor ($liste_champs) VALUES ($liste_valeurs)";
    $resultat = mysql_db_query($cfgBase, $sql);

	$rub = 'voir';

  }
  
}


// ---------------------------------------------------------------------------
// Saisie d'une signature
// ---------------------------------------------------------------------------
if ($connecte && ($rub == 'signer' || $rub == '')) {

  echo "<CENTER>\n";
  DebutTableau("#FFFFFF", "1", "0", "");
    DebutTableau("#354785", "15", "0", "100%");

	  echo "<FORM METHOD='POST' ACTION='livredor/index.php3?rub=ajouter'>\n";	

      echo "<TABLE BORDER=0>\n";
    
	  echo "<TR><TD CLASS='livre'>&nbsp;<B>Nom</B>&nbsp;</TD>";
	  echo "<TD>&nbsp;<INPUT TYPE='text' NAME='ztNom' VALUE=\"".stripslashes(htmlspecialchars($Nom))."\">&nbsp;</TD>";
	
	  echo "<TD ROWSPAN='2'>&nbsp;<INPUT TYPE='submit' VALUE=' Ok '>&nbsp;</TD></TR>";

	  echo "<TR><TD CLASS='livre'>&nbsp;E-mail (opt.)&nbsp;</TD>";
	  echo "<TD>&nbsp;<INPUT TYPE='text' NAME='ztEmail' VALUE=\"".stripslashes(htmlspecialchars($Email))."\">&nbsp;</TD></TR>";

	  echo "<TR><TD CLASS='livre'>&nbsp;<B>Commentaire</B>&nbsp;";
	  echo "</TD><TD COLSPAN='3'>&nbsp;<TEXTAREA NAME='ztComment' ROWS='5' COLS='25' SIZE='10'  WRAP='virtual'>".stripslashes(htmlspecialchars($Comment))."</TEXTAREA>&nbsp;</TD></TR>";
	
	  echo "</FORM>\n";
	  echo "</TABLE>";

echo "<BR><CENTER>&nbsp;"."&nbsp;&nbsp;<A HREF='?rub=voir'>Voir le Livre d'Or</A></CENTER>";

    FinTableau();
  FinTableau();
  echo "</CENTER>\n";


}


// ---------------------------------------------------------------------------
// Affichage des Signatures
// ---------------------------------------------------------------------------
if ($connecte && $rub == 'voir') {

  $nbpp = 8;  // Nombre de messages par page
  
  if (!isset($deb)) $deb = 0;

  $sql  = "SELECT DATE_FORMAT(date, \"%d-%m %H:%i\") as jour, nom, email, commentaire ";
  $sql .= "FROM livredor ";
  $sql .= "ORDER BY date DESC, code DESC ";
  $sql .= "LIMIT ".$deb.",".$nbpp;

  $resultat2 = mysql_db_query($cfgBase, $sql);
  $nb_enr = mysql_num_rows($resultat2);

  DebutTableau("#FFFFFF", "1", "0", "100%");
  DebutTableau("#354785", "15", "0", "100%");
  
echo "<CENTER>&nbsp;"."&nbsp;&nbsp;<A HREF='contacts.php'>Signer le Livre d'Or</A></CENTER><BR>";

  if ($nb_enr != 0) {
    
    echo "<TABLE BORDER='0' CELLSPACING='1' CELLPADDING='5' WIDTH='100%'>\n";
	
	while ($livredor = mysql_fetch_array($resultat2)) {
    
      echo "<TR WIDTH='100%' BGCOLOR='#B8C8FE'><TD>\n";

	  echo "<A CLASS='heure'>&nbsp;&nbsp;" . $livredor['jour']  . "&nbsp;&nbsp;</A>&nbsp;&nbsp;\n";
	  echo "<B>" . htmlspecialchars(strip_tags($livredor['nom'])) . "</B> ";
	  echo ($livredor['email'] != "") ? "(<A HREF='mailto:" . htmlspecialchars(urlencode($livredor['email'])) . "' CLASS='email'>" . htmlspecialchars(strip_tags($livredor['email']))."</A>)" : "";
	  br(2);
	  echo nl2br(eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])", "<A HREF=\"\\1://\\2\\3\" CLASS=\"email\" TARGET=\"_blank\">\\1://\\2\\3</A>", strip_tags($livredor['commentaire'])));
      
	  echo "</TD></TR>\n";
    }
  
	echo "</TABLE>\n";
    
    br();

    // Recherche du nombre de messages total
    $sql = "SELECT count(*) FROM livredor";
    $res = mysql_db_query($cfgBase, $sql);
    $nbEnr = mysql_fetch_array($res);
    $nbMessage = $nbEnr[0];
    
    echo "<TABLE CELLSPACING=0 CELLPADDING=0 WIDTH='100%' BORDER=0>\n";
    
	// Retour
	echo "<TR><TD CLASS='livre' WIDTH='50%' ALIGN='left'>\n";
    
	if ($deb >= $nbpp) {
	  // Début
	  echo "&nbsp;";
      echo "<A HREF='?rub=voir&deb=" . (0) . "'>" . image("images/livre-prec.gif") . image("images/livre-prec.gif") . "</A>";
	  echo "&nbsp;";
      echo "<A HREF='?rub=voir&deb=" . (0) . "'>Début</A>";
	  
	  echo " | ";
	 
	  // Précédent
	  echo "<A HREF='?rub=voir&deb=" . ($deb-$nbpp) . "'>" . image("images/livre-prec.gif") . "</A>";
	  echo "&nbsp;";
	  echo "<A HREF='?rub=voir&deb=" . ($deb-$nbpp) . "'>Précédent</A>";
	}

	// Avance
	echo "</TD><TD CLASS='livre' WIDTH='50%' ALIGN='right'>\n";
	if ($deb + $nbpp < $nbMessage) {
	  // Suivant
	  echo "&nbsp;";
      echo "<A HREF='?rub=voir&deb=" . ($deb+$nbpp) . "'>Suivant</A>";
	  echo "&nbsp;";
      echo "<A HREF='?rub=voir&deb=" . ($deb+$nbpp) . "'>" . image("images/livre-suiv.gif") . "</A>";
	  
	  echo " | ";
	  
	  // Fin
	  $pos = ($nbMessage - ($nbMessage % $nbpp));
	  if (($nbMessage % $nbpp) == 0) $pos = $pos - $nbpp;

	  echo "<A HREF='?rub=voir&deb=" . ($pos) . "'>Fin</A>";
	  echo "&nbsp;";
	  echo "<A HREF='?rub=voir&deb=" . ($pos) . "'>" . image("images/livre-suiv.gif") . image("images/livre-suiv.gif") . "</A>";
	}

    echo "</TD></TR>\n";
    echo "</TABLE>";

  } else {
    echo "<CENTER><A CLASS='erreur'>&nbsp;Le Livre d'Or est vide pour le moment !&nbsp;</A></CENTER>";
  }

  FinTableau();
  FinTableau();

}

FinTableau();
FinTableau();

?>