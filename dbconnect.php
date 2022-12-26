<?php
$servername = "localhost:3306";
$username = "root";
$password = "";
$dbname = "stepan";

//připojení k db
$conn = mysqli_connect($servername, $username, $password, $dbname);

//kontrola připojení k db
if (!$conn) {
  die("Chyba připojení k databázi: " . mysqli_connect_error());
}

mysqli_query($conn, "SET CHARACTER SET utf8");
mysqli_query($conn, "SET NAMES 'UTF8'");

//nastavení časové zóny je potřeba pro použití funkce date()
date_default_timezone_set("Europe/Prague");

?>