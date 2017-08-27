<?php
function getDB() {
$dbhost="localhost";
$dbuser="tawazun7_muneera";
$dbpass="1234qwer";
$dbname="tawazun7_vote";
$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass); 
$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
return $dbConnection;
}
?>