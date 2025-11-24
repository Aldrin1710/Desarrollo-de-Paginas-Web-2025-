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
    <title>Document</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div>
        <h2 style="text-align: center;">Únete a esCine 🍿</h2>

        <?php 
            $errores_registro = [
                'campos_vacios' => 'Todos los campos son obligatorios.',
                'formato_correo_invalido' => 'La dirección de correo electrónico no es válida',
                'contrasenas_no_coinciden' => 'Las contraseñas no son iguales.',
                'usuario_invalido' => 'Ese nombre de usuario ya existe.',
                'correo_invalido' => 'Ese correo ya está registrado.',
                'falla_bd' => 'Error al hacer la insercion a la BD.',
                'falla_sistema' => 'Error técnico. Intenta más tarde.'
            ];

            $codigo = $_GET['error'] ?? null;
            
            if ($codigo && isset($errores_registro[$codigo])): 
        ?>
            <div class="alerta-error">
                <?php echo $errores_registro[$codigo]; ?>
            </div>
        <?php endif; ?>
        
        <form action="agregar-usuario.php" method="POST" novalidate>
            
            <label>Nombre de Usuario:</label>
            <input type="text" name="usuario" required maxlength="50" value="<?php echo htmlspecialchars($_GET['usuario'] ?? ''); ?>">

            <label>Correo Electrónico:</label>
            <input type="email" name="correo" required maxlength="100" value="<?php echo htmlspecialchars($_GET['correo'] ?? ''); ?>">

            <label>Contraseña:</label>
            <input type="password" name="contrasena" required minlength="4">

            <label>Confirmar Contraseña:</label>
            <input type="password" name="confirmar_contrasena" required>

            <input type="submit" value="Registrarse" style="background: #10b981; color: white; border: none; cursor: pointer; font-size: 16px;">
            
            <p style="text-align: center; margin-top: 15px;">
                ¿Ya tienes cuenta? <a href="index.php">Inicia sesión aquí</a>
            </p>
        </form>
    </div>
</body>
</html>