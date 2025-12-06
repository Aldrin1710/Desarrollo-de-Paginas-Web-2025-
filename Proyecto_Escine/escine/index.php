<?php 
session_start(); 
require 'conexion.php'; 

try {
    $sql_recent = "SELECT r.*, u.nombre, u.avatar 
                   FROM reseña r 
                   JOIN usuarios u ON r.idUsuario = u.idUsuario 
                   ORDER BY r.fechaC DESC 
                   LIMIT 3";
                   
    $stmt_recent = $conn->prepare($sql_recent);
    $stmt_recent->execute();
    $ultimas_resenas = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);

}catch (PDOException $e) {
    die('Error SQL: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Tu mundo de películas y series</title>

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
        <li><a href="#" class="active">Inicio</a></li>
        <li><a href="peliculas.php">Películas</a></li>
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

        <section id="search-section" class="search-box">
            <h2>Busca tu próxima película o serie favorita</h2>
            <form id="search-form">
                <input type="search" id="search-input" placeholder="Buscar por título, director o género...">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <div id="search-results"></div>
        </section>

        <section id="popular-section">
            <h2>Estrenos Populares</h2>
            <div class="movie-grid">
                <article class="movie-card">
                    <img src=" " alt="Nombre Película">
                    <div class="movie-info">
                        <h3>Ejemplo 1 (2023)</h3>
                        <p>Género: Acción, Sci-Fi</p>
                        <div class="rating">⭐ 4.5/5</div>
                    </div>
                </article>
                <article class="movie-card">
                    <img src=" " alt="Nombre Película">
                    <div class="movie-info">
                        <h3>Ejemplo 2 (2025)</h3>
                        <p>Género: Comedia</p>
                        <div class="rating">⭐ 3.8/5</div>
                    </div>
                </article>
                <article class="movie-card">
                    <img src=" " alt="Nombre Película">
                    <div class="movie-info">
                        <h3>Ejemplo 3 (2025)</h3>
                        <p>Género: Drama, Misterio</p>
                        <div class="rating">⭐ 4.9/5</div>
                    </div>
                </article>
                <article class="movie-card">
                    <img src=" " alt="Nombre Película">
                    <div class="movie-info">
                        <h3>Ejemplo 4 (2025)</h3>
                        <p>Género: Drama, Misterio</p>
                        <div class="rating">⭐ 4.9/5</div>
                    </div>
                </article>
            </div>
        </section>

        <section id="popular-series-section">
            <h2>Series Populares</h2>
            <div class="movie-grid">
                </div>
        </section>

        <section id="top-rated-section">
            <h2>Mejores Valoradas</h2>
            <div class="movie-grid">
                </div>
        </section>
        
        <div class="reviews-with-sidebar">

            <section id="latest-reviews-section">
    <h2>Últimas Reseñas de la Comunidad</h2>
    <div class="review-list">
        
        <?php if (count($ultimas_resenas) > 0): ?>
            <?php foreach ($ultimas_resenas as $rev): ?>
                
                <article class="review-card index-review-item" data-tmdb-id="<?php echo $rev['idCont']; ?>">
                    
                    <div class="review-card-poster">
                        <a href="detalle-pelicula.php?id=<?php echo $rev['idCont']; ?>&type=movie">
                            <img class="poster-img" src="assets/img/no-poster.jpg" alt="Poster" style="width: 100px; height: 150px; object-fit: cover;">
                        </a>
                    </div>
                    
                    <div class="review-card-content">
                        <h3 class="movie-title">Cargando...</h3>
                        
                        <div class="review-meta">
                            <span class="review-user">Reseña de <strong><?php echo htmlspecialchars($rev['nombre']); ?></strong></span>
                            <span class="review-rating" style="color: #f0c14b;">⭐ <?php echo $rev['calificacion']; ?>/10</span>
                        </div>
                        
                        <p class="review-comment" style="font-style: italic; color: #ccc;">
                            "<?php echo htmlspecialchars(substr($rev['comentario'], 0, 150)) . (strlen($rev['comentario']) > 150 ? '...' : ''); ?>"
                        </p>
                        
                        <?php if($rev['tieneSpoiler']): ?>
                            <small style="color: #E50914; font-weight: bold;">(Contiene Spoilers)</small>
                        <?php endif; ?>
                    </div>
                </article>

            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #888;">Aún no hay reseñas. ¡Sé el primero en escribir una!</p>
        <?php endif; ?>

    </div>
</section>

            <aside class="sidebar-right">
                
                <div class="sidebar-widget">
                    <h3>Próximos Estrenos</h3>
                    <ul class="popular-lists" id="upcoming-list">
                        </ul>
                </div>
            </aside>

        </div> 
        <section id="news-section">
            <h2>Noticias y Estrenos</h2>
            <div class="news-grid" id="news-grid-container">
                </div>
        </section>

    </main> <footer class="site-footer">
        <div class="container">
            <p>&copy; 2025 ESCINE. Proyecto Final de Desarrollo de Aplicaciones Web.</p>
        </div>
    </footer>

    <script src="main.js"></script>
    <script src="api.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const apiKey = '';
        const cards = document.querySelectorAll('.index-review-item');

        cards.forEach(card => {
            const id = card.getAttribute('data-tmdb-id');
            const imgEl = card.querySelector('.poster-img');
            const titleEl = card.querySelector('.movie-title');

            
            fetch(`https://api.themoviedb.org/3/movie/${id}?api_key=${apiKey}&language=es-MX`)
                .then(res => res.json())
                .then(data => {
                    if (data.title) {
                        titleEl.textContent = `Sobre '${data.title}'`;
                    }
                    if (data.poster_path) {
                        imgEl.src = `https://image.tmdb.org/t/p/w200${data.poster_path}`;
                    }
                })
                .catch(err => {
                    titleEl.textContent = "Película no disponible";
                });
        });
    });
</script>
</body>
</html>