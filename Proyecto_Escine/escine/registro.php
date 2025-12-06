<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Registrarse</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header class="site-header">
        <div class="container">
            <h1 class="logo">
                <a href="index.php">
                    <img src="img/logo.png" alt="ESCINE">
                </a>
            </h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="peliculas.php">Películas</a></li>
                    <li><a href="series.php">Series</a></li> 
                    <li><a href="login.php" class="btn btn-secondary">Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="btn btn-primary active">Registrarse</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle-btn">🌙</button>
        </div>
    </header>

    <main class="container">

        <div class="form-container">
            
            <form id="register-form" action="agregar-usuario.php" method="POST">
    <h2>Crear Cuenta</h2>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="error-message" style="display:block; text-align:center; color: var(--color-error); margin-bottom: 15px;">
            <?php 
                $error = $_GET['error'];
                if($error == 'campos_vacios') echo "Llena todos los campos.";
                elseif($error == 'formato_correo_invalido') echo "El correo no es válido.";
                elseif($error == 'contrasenas_no_coinciden') echo "Las contraseñas no coinciden.";
                elseif($error == 'usuario_invalido') echo "Ese usuario ya existe.";
                elseif($error == 'correo_invalido') echo "Ese correo ya está registrado.";
                else echo "Ocurrió un error al registrarse.";
            ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="register-name">Nombre de Usuario:</label>
        <input type="text" id="register-name" name="usuario" required>
    </div>

    <div class="form-group">
        <label for="register-email">Correo Electrónico:</label>
        <input type="email" id="register-email" name="correo" required>
    </div>
    
    <div class="form-group">
        <label for="register-password">Contraseña:</label>
        <input type="password" id="register-password" name="contrasena" required>
    </div>
    
    <div class="form-group">
        <label for="register-password-confirm">Confirmar Contraseña:</label>
        <input type="password" id="register-password-confirm" name="confirmar_contrasena" required>
    </div>
    
    <button type="submit" class="btn btn-primary btn-full-width">Crear Cuenta</button>

    <div class="form-helper-link">
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</form>
            
        </div>

    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2025 ESCINE. Proyecto Final de Desarrollo de Aplicaciones Web.</p>
        </div>
    </footer>

    <script src="main.js"></script>
</body>
</html>