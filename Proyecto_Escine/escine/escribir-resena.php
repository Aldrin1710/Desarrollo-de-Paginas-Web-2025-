<?php
session_start();


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Escribir Reseña</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        
        <div class="review-page-container">
            
            <div class="review-movie-sidebar">
                <img id="review-poster" src="img/no-poster.jpg" alt="Poster">
                <h2 id="review-title">Cargando título...</h2>
                <p id="review-year" style="color: #aaa;">----</p>
            </div>

            <div class="review-form-container">
                <h1>¿Qué te pareció?</h1>
                <p class="subtitle">Comparte tu opinión con la comunidad de ESCINE.</p>

                <form id="create-review-form" action="guardar-resena.php" method="POST">
                    
                    <input type="hidden" id="movie-id" name="movie_id">
                    
                    <div class="form-group">
                        <label>Tu Calificación (1-10):</label>
                        <div class="star-rating-selector">
                            <i class="fas fa-star" data-value="1"></i>
                            <i class="fas fa-star" data-value="2"></i>
                            <i class="fas fa-star" data-value="3"></i>
                            <i class="fas fa-star" data-value="4"></i>
                            <i class="fas fa-star" data-value="5"></i>
                            <i class="fas fa-star" data-value="6"></i>
                            <i class="fas fa-star" data-value="7"></i>
                            <i class="fas fa-star" data-value="8"></i>
                            <i class="fas fa-star" data-value="9"></i>
                            <i class="fas fa-star" data-value="10"></i>
                            <input type="hidden" id="rating-input" name="rating" value="0" required>
                        </div>
                        <div id="rating-text" style="margin-top: 5px; font-weight: bold; color: var(--color-primary); min-height: 24px;"></div>
                    </div>

                    <div class="form-group">
                        <label for="review-content">Tu Reseña:</label>
                        <textarea id="review-content" name="content" rows="8" placeholder="Escribe aquí tu análisis, opiniones y críticas..." required></textarea>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="review-spoilers" name="spoilers">
                        <label for="review-spoilers">Esta reseña contiene Spoilers</label>
                    </div>

                    <div class="form-actions">
                        <a href="javascript:history.back()" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Publicar Reseña</button>
                    </div>
                </form>
            </div>

        </div>

    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2025 ESCINE. Proyecto Final de Desarrollo de Aplicaciones Web.</p>
        </div>
    </footer>

    <script src="main.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Recuperar datos de la URL
            const params = new URLSearchParams(window.location.search);
            const id = params.get('id');
            const title = params.get('title');
            const poster = params.get('poster');
            const year = params.get('year');

            if(title) document.getElementById('review-title').textContent = title;
            if(year) document.getElementById('review-year').textContent = year;
            if(id) document.getElementById('movie-id').value = id; 
            if(poster) {
                document.getElementById('review-poster').src = decodeURIComponent(poster);
            }

            //  Estrellas
            const stars = document.querySelectorAll('.star-rating-selector i');
            const ratingInput = document.getElementById('rating-input');
            const ratingText = document.getElementById('rating-text');
            const texts = [
                "1 - Abismal", "2 - Terrible", "3 - Mala", "4 - Pobre", "5 - Regular", 
                "6 - Pasable", "7 - Buena", "8 - Muy Buena", "9 - Fantástica", "10 - Obra Maestra"
            ];

            stars.forEach(star => {
                star.addEventListener('mouseover', () => {
                    const val = parseInt(star.getAttribute('data-value'));
                    highlightStars(val);
                });

                star.addEventListener('mouseout', () => {
                    const currentVal = parseInt(ratingInput.value);
                    highlightStars(currentVal);
                });

                star.addEventListener('click', () => {
                    const val = parseInt(star.getAttribute('data-value'));
                    ratingInput.value = val;
                    ratingText.textContent = texts[val - 1];
                    highlightStars(val);
                });
            });

            function highlightStars(count) {
                stars.forEach(s => {
                    const val = parseInt(s.getAttribute('data-value'));
                    if (val <= count) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            }
            
            // Validación 
            const form = document.getElementById('create-review-form');
            form.addEventListener('submit', (e) => {
                
                if(ratingInput.value == "0") {
                    e.preventDefault(); 
                    alert("Por favor selecciona una calificación.");
                }
               
            });
        });
    </script>
</body>
</html>