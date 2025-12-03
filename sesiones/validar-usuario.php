<?php
session_start();

//Evitar sesiones duplicadas
if (!empty($_SESSION['usuario_id'])) {
    header('Location: mi-perfil.php');
    exit();
}

require_once 'conexion.php';

$correo_usuario = trim($_POST['correo']);
$contrasena_form = trim($_POST['contrasena']);

$datos_usuario = "&correo=" . urlencode($correo_usuario);

//Verificamos datos enviados
if (empty($correo_usuario) || empty($contrasena_form)) {
    header('Location: index.php?error=campos_vacios' . $datos_usuario);
    exit();
}

if (!filter_var($correo_usuario, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?error=formato_correo_invalido" . $datos_usuario);
    exit();
}

//Preparamos la consulta
try {
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $sentencia_agregar = $conn->prepare($sql);
    
    $sentencia_agregar->execute([$correo_usuario]);
    
    $fila = $sentencia_agregar->fetch(PDO::FETCH_ASSOC);

    // Verificamos credenciales
    if ($fila && password_verify($contrasena_form, $fila['contrasena'])) {
        
        // LOGIN EXITOSO
        // Cambios aquí: 'id' -> 'idUsuario', 'usuario' -> 'nombre'
        $_SESSION['usuario_id'] = $fila['idUsuario'];
        $_SESSION['usuario_nombre'] = $fila['nombre'];
        
        // Regenerar ID de sesión para evitar ataques de "Session Fixation"
        session_regenerate_id(true);

        header('Location: mi-perfil.php');
        exit();

    } else {
        header('Location: index.php?error=credenciales_invalidas' . $datos_usuario);
        exit();
    }

} catch (PDOException $e) {
    // Manejo de error interno (log del servidor, no mostrar al usuario)
    error_log("Error en login: " . $e->getMessage());
    header('Location: index.php?error=falla_sistema ');
    exit();
}

?>
