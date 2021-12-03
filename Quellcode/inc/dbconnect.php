<?php
try{
    require_once 'db.php';
    $db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
}
catch(PDOException $pe)
{
    die("Fehlgeschlagen: ". $pe->getMessage());
}
?>