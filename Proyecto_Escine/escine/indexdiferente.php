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
                <a href="index.html">
                    <img src="img/logo.png" alt="ESCINE">
                </a>
            </h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="#" class="active">Inicio</a></li>
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
                <h2>Últimas Reseñas</h2>
                <div class="review-list">
                    <article class="review-card">
                        <div class="review-card-poster">
                            <img src="https://via.placeholder.com/100x150.png?text=Poster" alt="Poster Pequeño">
                        </div>
                        <div class="review-card-content">
                            <h3>Sobre 'Ejemplo 2'</h3>
                            <div class="review-meta">
                                <span class="review-user">Reseña de <strong>UsuarioAlfa</strong></span>
                                <span class="review-rating">⭐ 4.0/5</span>
                            </div>
                            <p class="review-comment">"Una comedia muy refrescante, me reí mucho aunque el final fue un poco predecible..."</p>
                        </div>
                    </article>
                    <article class="review-card">
                        <div class="review-card-poster">
                            <img src="https://via.placeholder.com/100x150.png?text=Poster" alt="Poster Pequeño">
                        </div>
                        <div class="review-card-content">
                            <h3>Sobre 'Ejemplo 1'</h3>
                            <div class="review-meta">
                                <span class="review-user">Reseña de <strong>CinefilaTotal</strong></span>
                                <span class="review-rating">⭐ 5.0/5</span>
                            </div>
                            <p class="review-comment">"¡Qué peliculón! Los efectos especiales son de otro nivel y la trama te mantiene al borde del asiento."</p>
                        </div>
                    </article>
                    <article class="review-card">
                        <div class="review-card-poster">
                            <img src="https://via.placeholder.com/100x150.png?text=Poster" alt="Poster Pequeño">
                        </div>
                        <div class="review-card-content">
                            <h3>Sobre 'Ejemplo 3'</h3>
                            <div class="review-meta">
                                <span class="review-user">Reseña de <strong>RetroFan</strong></span>
                                <span class="review-rating">⭐ 4.5/5</span>
                            </div>
                            <p class="review-comment">"Un drama intrigante con giros inesperados. La actuación del elenco fue sobresaliente."</p>
                        </div>
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
</body>
</html>