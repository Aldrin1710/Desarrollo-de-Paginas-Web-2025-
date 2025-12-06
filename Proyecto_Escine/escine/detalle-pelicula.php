<?php
session_start();
require 'conexion.php'; 


$idPeli = $_GET['id'] ?? null;
$tipo = $_GET['type'] ?? 'movie';
$enLista = false;

if (isset($_SESSION['usuario_id']) && $idPeli) {
    $sql_check = "SELECT * FROM lista WHERE idUsuario = ? AND idCont = ? AND tipo = ?";
    $stmt = $conn->prepare($sql_check);
    $stmt->execute([$_SESSION['usuario_id'], $idPeli, $tipo]);
    if ($stmt->fetch()) {
        $enLista = true;
    }
}


$yaReseno = false;

if (isset($_SESSION['usuario_id']) && $idPeli) {
    $sql_ya = "SELECT 1 FROM reseña WHERE idUsuario = ? AND idCont = ? LIMIT 1";
    $stmt_ya = $conn->prepare($sql_ya);
    $stmt_ya->execute([$_SESSION['usuario_id'], $idPeli]);
    $yaReseno = (bool)$stmt_ya->fetchColumn();
}

//  CONSULTAR LAS RESEÑAS DE ESTA PELÍCULA
try {
    $sql_reviews = "SELECT r.*, u.nombre, u.avatar 
                    FROM reseña r 
                    JOIN usuarios u ON r.idUsuario = u.idUsuario 
                    WHERE r.idCont = ? 
                    ORDER BY r.calificacion DESC"; 
    
    $stmt_reviews = $conn->prepare($sql_reviews);
    $stmt_reviews->execute([$idPeli]);
    $resenas_peli = $stmt_reviews->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $resenas_peli = [];
}

$promedio_local = null;
$votos_local = 0;

if ($idPeli) {
    try {
        $sql_avg = "SELECT AVG(calificacion) as prom, COUNT(*) as total FROM reseña WHERE idCont = ?";
        $stmt_avg = $conn->prepare($sql_avg);
        $stmt_avg->execute([$idPeli]);
        $data_avg = $stmt_avg->fetch(PDO::FETCH_ASSOC);
        
        if ($data_avg && $data_avg['prom']) {
            $promedio_local = round($data_avg['prom'], 1);
            $votos_local = $data_avg['total'];
        }
    } catch (PDOException $e) {
        
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Detalles</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <main>
        <section class="movie-hero">
            <div class="container">
                <div class="movie-hero-content">
                <div class="movie-hero-poster">
                    <img src="img/no-poster.jpg" alt="Portada de la película">
                </div>
                <div class="movie-hero-info">
                    <h1 class="movie-title">Cargando...</h1>
                    <div class="movie-meta-info">
                        <span class="movie-year">----</span>
                        <span class="movie-director">...</span>
                        <span class="movie-runtime">...</span>
                    </div>
                    
                    <div class="movie-actions">
                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            <a href="toggle-lista.php?id=<?php echo $idPeli; ?>&type=<?php echo $tipo; ?>" 
                               class="btn btn-secondary" 
                               style="text-decoration: none; display: inline-flex; align-items: center; gap: 5px; <?php if($enLista) echo 'background-color: #4CAF50; color: white; border: none;'; ?>">
                                
                                <?php if ($enLista): ?>
                                    <i class="fas fa-check"></i> En tu lista
                                <?php else: ?>
                                    <i class="fas fa-plus"></i> Añadir a lista
                                <?php endif; ?>
                                
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-secondary" style="text-decoration: none;">Iniciar sesión para añadir</a>
                        <?php endif; ?>


                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            
                            <?php if ($yaReseno): ?>
                                
                                <button class="btn btn-primary btn-lg"
                                        disabled
                                        style="opacity: 0.6; cursor: not-allowed; margin-left: 10px;">
                                    Reseñado
                                </button>
                            <?php else: ?>
                                
                                <button class="btn btn-primary btn-lg" style="margin-left: 10px;">
                                    Reseñar
                                </button>
                            <?php endif; ?>

                        <?php else: ?>
                            
                            <a href="login.php" class="btn btn-primary btn-lg" style="margin-left: 10px;">
                                Inicia sesión para reseñar
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="movie-ratings" style="display: flex; align-items: center; gap: 20px; margin-top: 15px;">
    
                        <div title="Calificación Mundial (TMDB)">
                            <span class="rating-value" style="font-size: 24px; color: #f0c14b;">⭐ --/10</span>
                            <span class="fan-count" style="font-size: 14px; color: #aaa; display: block;">... Votos</span>
                        </div>

                        <div title="Calificación de la Comunidad ESCINE" style="border-left: 1px solid #555; padding-left: 20px;">
                            <?php if ($promedio_local): ?>
                                <span style="font-size: 24px; color: #4CAF50; font-weight: bold;">💚 <?php echo $promedio_local; ?>/10</span>
                                <span style="font-size: 14px; color: #aaa; display: block;"><?php echo $votos_local; ?> Reseñas</span>
                            <?php else: ?>
                                <span style="font-size: 16px; color: #888;">Sin reseñas aún</span>
                                <span style="font-size: 12px; color: #666; display: block;">Sé el primero</span>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
                </div>
            </div>
        </section>

        <section class="container movie-details-section">
            <div class="movie-detail-grid">
                <div class="movie-main-info">
                    <p class="movie-tagline"></p>
                    <p class="movie-overview">Cargando sinopsis...</p>
                    
                    <h3>Reparto Principal</h3>
                    <div class="movie-cast-list" id="cast-container"></div>

                    <h3>Géneros</h3>
                    <div class="movie-tags" id="genres-container"></div>

                    <h3>Tráiler</h3>
                    <div class="trailer-container"></div>

                    <h3>¿Dónde ver?</h3>
                    <div class="where-to-watch" id="watch-providers-container">
                        <p style="color: #888; font-size: 14px;">Cargando disponibilidad...</p>
                    </div>
                </div>

                <aside class="movie-sidebar">
                    <h3>Otros Datos</h3>
                    <ul id="sidebar-details-list"></ul>
                    <div id="keywords-widget" style="margin-top: 30px; display: none;">
                        <h3>Palabras Clave</h3>
                        <div class="keywords-list" id="keywords-container" style="display: flex; flex-wrap: wrap; gap: 8px;"></div>
                    </div>
                    <div id="collection-widget" style="margin-top: 30px; display: none;">
                        <h3>Franquicia</h3>
                        <div id="collection-container" class="collection-card"></div>
                    </div>
                    <div id="social-links-widget" style="margin-top: 30px; display: none;">
                        <h3>Enlaces Externos</h3>
                        <div id="social-links-container" class="social-icons-row"></div>
                    </div>
                </aside>
            </div>
        </section>

       <section class="container reviews-section">
    <h2>Comentarios de la Comunidad (<?php echo count($resenas_peli); ?>)</h2>
    
    <div class="review-list">
    
    <?php if (count($resenas_peli) > 0): ?>
        <?php foreach ($resenas_peli as $rev): ?>
            
            <?php 
                // LÓGICA DE AVATAR
                $avatarReview = "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($rev['nombre']);
                
                
                if (!empty($rev['avatar']) && file_exists($rev['avatar'])) {
                    
                    $avatarReview = $rev['avatar'] . "?t=" . time();
                }
            ?>

            <article class="review-card-full" style="margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 20px;">
                <div class="review-user-info">
                    
                    <img src="<?php echo $avatarReview; ?>" 
                         alt="Avatar" 
                         style="width: 50px; height: 50px; border-radius: 50%; border: 2px solid #E50914; object-fit: cover;">
                    
                    <div>
                        <strong style="font-size: 16px; color: #fff;"><?php echo htmlspecialchars($rev['nombre']); ?></strong>
                        <div class="review-meta" style="margin-top: 5px;">
                            <span class="review-rating" style="color: #f0c14b; font-weight: bold;">
                                ⭐ <?php echo $rev['calificacion']; ?>/10
                            </span>
                            
                            <?php if($rev['tieneSpoiler']): ?>
                                <span style="background: #E50914; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 10px;">SPOILER</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <p class="review-comment-full" style="margin-top: 15px; color: #ccc; font-style: italic; line-height: 1.6;">
                    "<?php echo htmlspecialchars($rev['comentario']); ?>"
                </p>
            </article>

        <?php endforeach; ?>
    <?php else: ?>
        
        <div style="text-align: center; padding: 40px; background: #222; border-radius: 8px;">
            <p style="color: #aaa; font-size: 18px; margin-bottom: 20px;">Aún no hay opiniones sobre este título.</p>
            <a href="#hero-actions" onclick="document.querySelector('.movie-actions .btn-primary').click();" style="color: #E50914; font-weight: bold; cursor: pointer;">
                ¡Sé el primero en escribir una reseña!
            </a>
        </div>

    <?php endif; ?>

</div>
</section>

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
