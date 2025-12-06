<?php
session_start();
require 'conexion.php';


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

//RECIBIR DATOS
$idUsuario = $_SESSION['usuario_id'];
$nuevo_nombre = trim($_POST['usuario']);
$nuevo_correo = trim($_POST['correo']);

// Validaciones
if (empty($nuevo_nombre) || empty($nuevo_correo)) {
    header("Location: mi-perfil.php?error=campos_vacios");
    exit();
}
if (!filter_var($nuevo_correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: mi-perfil.php?error=correo_invalido");
    exit();
}

try {
    //ACTUALIZAR EN BASE DE DATOS
    $sql = "UPDATE usuarios SET nombre = ?, correo = ? WHERE idUsuario = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$nuevo_nombre, $nuevo_correo, $idUsuario])) {
        
        // Actualizamos la sesión con el nombre nuevo
        $_SESSION['usuario_nombre'] = $nuevo_nombre;
        
        // Redirigimos al perfil
        header("Location: mi-perfil.php?mensaje=perfil_actualizado");
        exit();

    } else {
        header("Location: mi-perfil.php?error=error_actualizacion");
        exit();
    }

} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Código de error para datos duplicados
        header("Location: mi-perfil.php?error=correo_duplicado");
    } else {
        error_log("Error perfil: " . $e->getMessage());
        header("Location: mi-perfil.php?error=falla_sistema");
    }
    exit();
}
?>