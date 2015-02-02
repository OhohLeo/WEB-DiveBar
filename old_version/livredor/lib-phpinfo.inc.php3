<?php

require("conf/cfg-phpinfo.inc.php3");

// ----------------------------------------------------------------------------
function EnteteHTML($page = "phpinfo") {

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
  echo "<HTML>\n"; 
  echo "<HEAD>\n";
  
  echo "  <TITLE> phpInfo.net - Site d'information PHP / PHP3 / MySQL </TITLE>\n";
  
  // Balises META
  // ------------
  echo "  <META NAME=\"Author\" CONTENT=\"Jean-Pierre DEZELUS (jpdezelus@phpinfo.net)\">\n";
  echo "  <META NAME=\"Description\" CONTENT=\"Site d'information PHP3 / MySQL. Exemples, trucs et astuces, docs, liens, programmes PHP complets a telecharger, FAQ, manuels, scripts. Pour tout savoir sur PHP et MySQL.\">\n";
  echo "  <META NAME=\"Identifier-URL\" CONTENT=\"http://www.phpinfo.net\">\n";
  echo "  <META NAME=\"Date-Creation-yyyymmdd\" content=\"".date("Ymd")."\">\n";
  echo "  <META NAME=\"Date-Revision-yyyymmdd\" content=\"".date("Ymd")."\">\n";
  echo "  <META NAME=\"Robots\" CONTENT=\"index,follow,all\">\n";
  echo "  <META NAME=\"revisit-after\" CONTENT=\"7 days\">\n";
  echo "  <META NAME=\"Reply-to\" CONTENT=\"webmaster@phpinfo.net\">\n";
  echo "  <META NAME=\"Category\" CONTENT=\"Internet\">\n";
  echo "  <META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=ISO-8859-1\">\n";

  // Styles CSS2
  // -----------
  echo "  <LINK REL=stylesheet TYPE=\"text/css\" HREF=\"css/".$page.".css\">\n";
  
  echo "</HEAD>\n";
  
  $fond = ($page == 'phpinfo') ? '#9DAEE8' : '#1C2D67';
  echo "<BODY BGCOLOR='$fond' BACKGROUND='images/fond-$page.gif' BGPROPERTIES='fixed'>\n";

  // Barre de Titre
  echo "<TABLE BORDER='0' WIDTH='100%' BGCOLOR='#000000' CELLPADDING='2' CELLSPACING='0'>\n";
  echo "<TR><TD><TABLE BORDER='0' WIDTH='100%' BGCOLOR='#7B8FD6' CELLPADDING='0' CELLSPACING='0'>\n";
/*   echo "<TD WIDTH='180' ALIGN='center'>".Lien("http://www.phpinfo.net/", image("images/logo.gif"))."</TD>\n";
  echo "<TD ALIGN='center'>".image("images/bandeau.gif")."</TD>\n"; */
  echo "</TR></TABLE>\n";
  echo "</TD></TR></TABLE>\n";

}

// ----------------------------------------------------------------------------
function FinHTML() { echo "</BODY>\n</HTML>\n"; }

// ----------------------------------------------------------------------------
function image($nom_img, $alignement = "absmiddle") {
  return "<IMG SRC='" . $nom_img . "' BORDER=0 ALIGN='". $alignement . "'>";
}

// ----------------------------------------------------------------------------
function DebutTableau($fond, $padding, $spacing, $largeur = "") {
  if ($largeur != "") $largeur = " WIDTH='$largeur'";
  if ($fond    != "") $fond    = " BGCOLOR='$fond'";
  echo "<TABLE BORDER='0' $fond CELLSPACING='$spacing' CELLPADDING='$padding' $largeur>\n";
  echo "<TR VALIGN='top'><TD>\n";
}

// ----------------------------------------------------------------------------
function FinTableau() {
  echo "</TD></TR>\n";
  echo "</TABLE>\n";
}

// ----------------------------------------------------------------------------
function NouvelleCellule($options = "") {
  echo "</TD><TD $options>\n";
}

// ----------------------------------------------------------------------------
function NouvelleLigne($options = "") {
  echo "</TD></TR><TR><TD $options>";
}

// ----------------------------------------------------------------------------
function br($nb = 1) {
  for ($i = 1; $i <= $nb; $i++) $str .= "<BR>";
  echo $str . "\n";
}

// ----------------------------------------------------------------------------
function Titre($msg) { return "<A CLASS='titre'>&nbsp;&nbsp;$msg&nbsp;&nbsp;</A><BR>\n"; }

// ----------------------------------------------------------------------------
function Normal($msg) { return "<A CLASS='normal'>$msg</A>\n"; }

// ----------------------------------------------------------------------------
function Lien($url, $msg, $cible = "") {
  if ($cible != "") $cible = " TARGET='$cible'";
  return "<A HREF='$url' $cible CLASS='lien'>$msg</A>";
}

// ----------------------------------------------------------------------------
function erreurServeurMySQL() {
  return "<CENTER><A CLASS='erreur'>&nbsp;&nbsp;Désolé ! Le serveur MySQL est down.&nbsp;&nbsp;</A><CENTER>\n";
}

// ----------------------------------------------------------------------------
function AfficherSource($nom_fic) {
  DebutTableau("#B8C8FE", "0", "5", "100%");
  show_source($nom_fic);
  FinTableau();
}


// ----------------------------------------------------------------------------
function DebutSQL() {
  echo "<TABLE BORDER='0' BGCOLOR='#B8C8FE' CELLSPACING='0' CELLPADDING='5'>\n";
  echo "<TR VALIGN='top'><TD CLASS='sql'>\n";
}

// ----------------------------------------------------------------------------
function FinSQL() {
  echo "</TD></TR>\n";
  echo "</TABLE>\n";
}

?>