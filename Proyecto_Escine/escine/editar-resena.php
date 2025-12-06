<?php
session_start();
require 'conexion.php';

//  Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['usuario_id'];
$idCont = $_GET['idCont'] ?? null; 

if (!$idCont) {
    header("Location: mi-perfil.php");
    exit();
}

// RECUPERAR DATOS VIEJOS DE LA BD
$sql = "SELECT * FROM reseña WHERE idUsuario = ? AND idCont = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$idUsuario, $idCont]);
$resena = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resena) {
    echo "Error: No se encontró la reseña.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reseña - ESCINE</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header class="site-header">
        <div class="container">
            <h1 class="logo"><a href="index.php"><img src="img/logo.png" alt="ESCINE"></a></h1>
            <nav class="main-nav">
                <ul>
                    <li><a href="mi-perfil.php" class="btn btn-secondary">Volver al Perfil</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="review-page-container">
            
            <div class="review-movie-sidebar">
                <img id="review-poster" src="img/no-poster.jpg" alt="Poster">
                <h2 id="review-title">Cargando...</h2>
            </div>

            <div class="review-form-container">
                <h1>Editar tu reseña</h1>
                <p class="subtitle">Corrige o actualiza tu opinión.</p>

                <form action="actualizar-resena.php" method="POST">
                    <input type="hidden" name="idCont" value="<?php echo $idCont; ?>">

                    <div class="form-group">
                        <label>Tu Calificación:</label>
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
                            
                            <input type="hidden" id="rating-input" name="rating" value="<?php echo $resena['calificacion']; ?>" required>
                        </div>
                        <div id="rating-text" style="font-weight:bold; color:#E50914; margin-top:5px;">
                            <?php echo $resena['calificacion']; ?>/10
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="review-content">Tu Reseña:</label>
                        <textarea id="review-content" name="content" rows="8" required><?php echo htmlspecialchars($resena['comentario']); ?></textarea>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="review-spoilers" name="spoilers" <?php if($resena['tieneSpoiler']) echo 'checked'; ?>>
                        <label for="review-spoilers">Contiene Spoilers</label>
                    </div>

                    <div class="form-actions">
                        <a href="mi-perfil.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const apiKey = ' ';
            const movieId = "<?php echo $idCont; ?>";
            
            // CARGAR DATOS DE TMDB
            fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=es-MX`)
            .then(res => res.json())
            .then(data => {
                if(data.title) document.getElementById('review-title').textContent = data.title;
                if(data.poster_path) document.getElementById('review-poster').src = `https://image.tmdb.org/t/p/w300${data.poster_path}`;
            });

            // LÓGICA DE ESTRELLAS
            const stars = document.querySelectorAll('.star-rating-selector i');
            const ratingInput = document.getElementById('rating-input');
            const ratingText = document.getElementById('rating-text');
            const initialRating = parseInt(ratingInput.value);

            function highlightStars(count) {
                stars.forEach(s => {
                    const val = parseInt(s.getAttribute('data-value'));
                    if (val <= count) s.classList.add('active');
                    else s.classList.remove('active');
                });
                ratingText.textContent = count + "/10";
            }

            
            highlightStars(initialRating);

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const val = parseInt(star.getAttribute('data-value'));
                    ratingInput.value = val;
                    highlightStars(val);
                });
            });
        });
    </script>
</body>
</html>