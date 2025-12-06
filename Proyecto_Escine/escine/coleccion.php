<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Colección</title>

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
                    <li><a href="peliculas.php" class="active">Películas</a></li>
                    <li><a href="series.php">Series</a></li> 
                    <li><a href="login.php" class="btn btn-secondary">Iniciar Sesión</a></li>
                    <li><a href="registro.php" class="btn btn-primary">Registrarse</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle-btn">🌙</button>
        </div>
    </header>

    <main class="container">

        <section class="page-header">
            <h2 id="collection-title">Cargando Colección...</h2>
            <p id="collection-overview"></p>
        
        
        </section>
        <section id="catalog-section">
            <div class="movie-grid">
                </div>
        </section>
            
            
        <div class="pagination-container">
            <button id="prev-page" class="btn btn-secondary" disabled>Anterior</button>
            <span id="page-info">Página 1</span>
            <button id="next-page" class="btn btn-secondary">Siguiente</button>
        </div>

    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2025 ESCINE. Proyecto Final de Desarrollo de Aplicaciones Web.</p>
        </div>
    </footer>

    <script src="main.js"></script>
    <script src="api.js"></script>
</body>
</html>