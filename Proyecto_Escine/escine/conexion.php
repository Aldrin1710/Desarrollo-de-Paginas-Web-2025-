<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "escine";

try{

    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8mb4",$username, $password);
    

} catch(PDOException $e){
    die("Fallo en la conexion: " + $e->getMessage());
}

?>