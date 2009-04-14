<?php
/**
 * include needed files
 */
require("mysqlupgrade.php");
//
  if (isset($_POST['export'])) {
    MySQLUpgrade::CreateStructorDump();

  } elseif (isset($_POST['submit'])) {
    if(!empty($_FILES['import']) and !empty($_FILES['import']['name']) and !empty($_FILES['import']['tmp_name'])){

      $file = $_FILES['import']['tmp_name'];
      $lines = file_get_contents($file);
      echo MySQLUpgrade::DatabaseUpgrade($lines);
      echo "<hr> Upgrade complete<br> see the database.log file for the changes to the database.";
     }
  }else{
    echo "<form method='POST' action='{$_SERVER['PHP_SELF']}' enctype='multipart/form-data'>\n";

		echo "<input type='file'accept='text/*' name='import'> <br>";

		echo "<input type='submit' name='submit' value='compare'>  <input type='submit' name='export' value='export'></form>";
  }
?>