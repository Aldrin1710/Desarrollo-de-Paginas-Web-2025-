<?php
    session_start();
    // Si ya hay sesión, mandarlo al perfil
    if (!empty($_SESSION['usuario_id'])) {
        header('Location: mi-perfil.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div>
        <h2 style="text-align: center;">Ingresa a EsCine 🍿</h2>
        <?php
        // DICCIONARIO DE ERRORES
        $mensajes_error = [
            'campos_vacios'          => ' Por favor, llena los campos vacios',
            'credenciales_invalidas' => 'El correo o la contraseña son incorrectos.',
            'formato_correo_invalido' => 'La dirección de correo electrónico no es válida',
            'sesion_incorrecta'      => 'Debes iniciar sesión para acceder a tu perfil.',
            'falla_sistema'          => 'Error del sistema. Intenta más tarde.',
        ];

        // 2. VERIFICAR SI HAY ERROR EN LA URL
        // Usamos el operador ternario: Si existe $_GET['error'], lo guardamos. Si no, es null.
        $error_code = $_GET['error'] ?? null;
        ?>

        <?php if ($error_code && isset($mensajes_error[$error_code])): ?>
            
            <div class="alerta-error">
                <?php echo $mensajes_error[$error_code]; ?>
            </div>

        <?php endif; ?>
        <form action="validar-usuario.php" method="POST" novalidate>
                <label>Correo:</label>
                <input type="text" name="correo" placeholder="ejemplo@cine.com" value="<?php echo htmlspecialchars($_GET['correo'] ?? ''); ?>">

                <label>Contraseña:</label>
                <input type="password" name="contrasena">

                <input type="submit" value="Ingresar" style="background: #3b82f6; color: white; border: none; cursor: pointer;">
                <p style="text-align: center; margin-top: 15px;">
                    ¿No tienes cuenta? <a href="registro.php">Registrate aquí</a>
                </p>
        </form>
    </div>
</body>
</html>

