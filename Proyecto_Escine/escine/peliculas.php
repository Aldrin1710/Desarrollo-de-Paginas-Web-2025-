<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Películas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <header class="site-header">
        <div class="container">
            <h1 class="logo">
                <a href="index.php"><img src="img/logo.png" alt="ESCINE"></a>
            </h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="peliculas.php" class="active">Películas</a></li>
                    <li><a href="series.php">Series</a></li> 
                    
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <li>
                            <a href="mi-perfil.php" style="color: #E50914; font-weight: bold;">
                                <i class="fas fa-user"></i> Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                            </a>
                        </li>
                        <li><a href="cerrar-sesion.php" style="font-size: 0.9em;">Salir</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="btn btn-secondary">Iniciar Sesión</a></li>
                        <li><a href="registro.php" class="btn btn-primary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle-btn">🌙</button>
        </div>
    </header>

    <main class="container">
        <section class="page-header">
            <h2>Todas las Películas</h2>
            <section id="search-section-peliculas" class="search-box">
                <form id="search-form-peliculas">
                    <input type="search" id="search-input-peliculas" placeholder="Buscar en películas...">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>
            </section>
        </section>

        <section class="filter-bar">
    <h4>Filtrar por:</h4>
    <form id="filter-form" class="filter-controls">
        
        <div class="form-group-filter">
            <label for="filter-genre">Género</label>
            <select id="filter-genre" name="genre">
                <option value="">Todos los géneros</option>
                <option value="accion">Acción</option>
                <option value="comedia">Comedia</option>
                <option value="drama">Drama</option>
                <option value="sci-fi">Sci-Fi</option>
                <option value="terror">Terror</option>
                <option value="documental">Documental</option>
            </select>
        </div>

        <div class="form-group-filter">
            <label for="filter-year">Año</label>
            <select id="filter-year" name="year">
                <option value="">Cualquier año</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2020-2022">2020-2022</option>
                <option value="2010s">2010s</option>
                <option value="2000s">2000s</option>
            </select>
        </div>
        
        <div class="form-group-filter">
            <label for="filter-platform">Plataforma</label>
            <select id="filter-platform" name="platform">
                <option value="">Todas</option>
                <option value="netflix">Netflix</option>
                <option value="hbo">HBO Max</option>
                <option value="disney">Disney+</option>
                <option value="prime">Prime Video</option>
            </select>
        </div>
        
        <div class="form-group-filter">
            <label for="filter-sort">Ordenar por</label>
            <select id="filter-sort" name="sort">
                <option value="popularidad">Popularidad</option>
                <option value="fecha_desc">Más Recientes</option>
                <option value="rating_desc">Mejor Calificadas</option>
                <option value="titulo_asc">A-Z</option>
            </select>
        </div>
    </form>
</section>

        <section id="catalog-section">
            <div class="movie-grid"></div> </section>

        <div class="pagination-container">
            <button id="prev-page" class="btn btn-secondary" disabled>Anterior</button>
            <span id="page-info">Página 1</span>
            <button id="next-page" class="btn btn-secondary">Siguiente</button>
        </div>
    </main>

    <footer class="site-footer"><div class="container"><p>&copy; 2025 ESCINE.</p></div></footer>
    <script src="main.js"></script>
    <script src="api.js"></script>
</body>
</html>