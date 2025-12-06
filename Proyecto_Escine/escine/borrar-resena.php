<?php
session_start();
require 'conexion.php';

// Verificación
if (!isset($_SESSION['usuario_id']) || !isset($_GET['idCont'])) {
    header("Location: mi-perfil.php");
    exit();
}

$idUsuario = $_SESSION['usuario_id'];
$idCont = $_GET['idCont'];

try {
    // BORRAR LA RESEÑA
    $sql = "DELETE FROM reseña WHERE idUsuario = ? AND idCont = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$idUsuario, $idCont]);
    
    header("Location: mi-perfil.php?mensaje=resena_eliminada");
    exit();

} catch (PDOException $e) {
    echo "Error al borrar: " . $e->getMessage();
}
?>