<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "esCine";

try{

    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4",$username, $password);
    
    //echo "Conectado <br>";

} catch(PDOException $e){
    die("Fallo en la conexion: " + $e->getMessage());
}

?>