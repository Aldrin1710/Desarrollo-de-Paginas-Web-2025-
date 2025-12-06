<?php
session_start();
require 'conexion.php'; 

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?error=debes_iniciar_sesion");
    exit();
}

$idUsuario = $_SESSION['usuario_id'];
$idCont = $_POST['movie_id']; 
$calificacion = $_POST['rating'];
$comentario = $_POST['content'];
$tieneSpoiler = isset($_POST['spoilers']) ? 1 : 0;


if(empty($idCont) || empty($calificacion)) {
    header("Location: peliculas.php?error=datos_incompletos");
    exit();
}

try {

    //PROCEDURE
    $sql = "CALL publicar_reseña(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$idUsuario, $idCont, $comentario, $calificacion, $tieneSpoiler])) {

        header("Location: mi-perfil.php?mensaje=reseña_publicada");
        exit();
    } else {
        echo "Error al guardar la reseña.";
    }

} catch (PDOException $e) {
    echo "Error de Base de Datos: " . $e->getMessage();
}
?>
