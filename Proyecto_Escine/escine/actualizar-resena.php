<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Recibir datos del formulario
$idUsuario = $_SESSION['usuario_id'];
$idCont = $_POST['idCont'];
$calificacion = $_POST['rating'];
$comentario = $_POST['content'];
$tieneSpoiler = isset($_POST['spoilers']) ? 1 : 0;

try {
    // ACTUALIZAR
    $sql = "UPDATE reseña SET comentario = ?, calificacion = ?, tieneSpoiler = ? WHERE idUsuario = ? AND idCont = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$comentario, $calificacion, $tieneSpoiler, $idUsuario, $idCont])) {
        // Volver al perfil
        header("Location: mi-perfil.php?mensaje=resena_actualizada");
    } else {
        header("Location: mi-perfil.php?error=error_actualizar");
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>