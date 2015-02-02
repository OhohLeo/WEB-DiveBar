<?php

require("lib-phpinfo.inc.php3");

EnTeteHTML();
  
  echo "<CENTER>";
  DebutTableau("", 20, 0, "80%");
    require("livre-dor.php3");
  FinTableau();
  echo "</CENTER>";

FinHTML();

?>