<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Iniciar Sesión</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
</head>
<body class="body-login">

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
                    <li><a href="registro.php" class="btn btn-primary">Registrarse</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle-btn">🌙</button>
        </div>
    </header>

    <main class="container">

        <div class="form-container">
            
            <form id="login-form" action="validar-usuario.php" method="POST">
    <h2>Iniciar Sesión</h2>
    
    <?php if(isset($_GET['error'])): ?>
        <div class="error-message" style="display:block; text-align:center; color: #f44336; margin-bottom: 15px; font-weight: bold;">
            <?php 
                if($_GET['error'] == 'credenciales_invalidas') echo "Correo o contraseña incorrectos.";
                elseif($_GET['error'] == 'campos_vacios') echo "Por favor llena todos los campos.";
                else echo "Ocurrió un error inesperado.";
            ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="login-email">Correo Electrónico:</label>
        <input type="email" id="login-email" name="correo" required>
    </div>
    
    <div class="form-group">
        <label for="login-password">Contraseña:</label>
        <input type="password" id="login-password" name="contrasena" required>
    </div>
    
    <button type="submit" class="btn btn-primary btn-full-width">Entrar</button>

    <div class="form-helper-link">
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
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