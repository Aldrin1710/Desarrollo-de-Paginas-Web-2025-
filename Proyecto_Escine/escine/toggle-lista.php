<?php
session_start();
require 'conexion.php';

// Si no está logueado, mandarlo al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['usuario_id'];
$idCont = $_GET['id'] ?? null;
$tipo = $_GET['tipo'] ?? 'movie'; 

if (!$idCont) {
    header("Location: index.php");
    exit();
}

try {
    
    $sql_check = "SELECT idLista FROM lista WHERE idUsuario = ? AND idCont = ? AND tipo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->execute([$idUsuario, $idCont, $tipo]);
    $existe = $stmt_check->fetch();

    if ($existe) {
        
        $sql_del = "DELETE FROM lista WHERE idLista = ?";
        $stmt_del = $conn->prepare($sql_del);
        $stmt_del->execute([$existe['idLista']]);
        $mensaje = "eliminado";
    } else {
        
        $sql_add = "INSERT INTO lista (idUsuario, idCont, tipo) VALUES (?, ?, ?)";
        $stmt_add = $conn->prepare($sql_add);
        $stmt_add->execute([$idUsuario, $idCont, $tipo]);
        $mensaje = "agregado";
    }


    header("Location: detalle-pelicula.php?id=$idCont&type=$tipo&lista=$mensaje");

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>