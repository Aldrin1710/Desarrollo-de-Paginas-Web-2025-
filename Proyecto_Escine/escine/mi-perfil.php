<?php
session_start();
require 'conexion.php'; 


if (empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$idUsuario = $_SESSION['usuario_id'];
$nombre = $_SESSION['usuario_nombre'];

// LÓGICA DE AVATAR
try {
    $stmt = $conn->prepare("SELECT avatar FROM usuarios WHERE idUsuario = ?");
    $stmt->execute([$idUsuario]);
    $ruta_subida = $stmt->fetchColumn();

    if ($ruta_subida && file_exists($ruta_subida)) {
        
        $avatar_final = $ruta_subida . "?t=" . time();
        $tiene_foto_custom = true;
    } else {
        
        $avatar_final = "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($nombre);
        $tiene_foto_custom = false;
    }
} catch (Exception $e) {
    $avatar_final = "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($nombre);
    $tiene_foto_custom = false;
}

//Consultar las reseñas de este usuario
try {

    $sql_resenas = "SELECT * FROM `reseña` WHERE idUsuario = ? ORDER BY fechaC DESC"; 

    $stmt_resenas = $conn->prepare($sql_resenas);
    $stmt_resenas->execute([$idUsuario]);
    $mis_resenas = $stmt_resenas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mis_resenas = []; 
}

//Su Lista
try {
    $sql_lista = "SELECT * FROM lista WHERE idUsuario = ?ORDER BY fecha DESC";  
    
    $stmt_lista = $conn->prepare($sql_lista);
    $stmt_lista->execute([$idUsuario]);
    $mi_lista = $stmt_lista->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mi_lista = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESCINE - Mi Perfil</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .drop-zone {
            border: 2px dashed #555;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: rgba(255,255,255,0.05);
        }
        .drop-zone:hover, .drop-zone.dragover {
            border-color: #E50914;
            background: rgba(229, 9, 20, 0.1);
        }
        .drop-zone img {
            width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;
        }
    </style>
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
                    <li><a href="mi-perfil.php" class="active"><i class="fas fa-user-circle"></i> Mi Perfil</a></li>
                    <li><a href="cerrar-sesion.php" style="color: #E50914;">Cerrar Sesión</a></li>
                </ul>
            </nav>
            <button id="theme-toggle" class="theme-toggle-btn">🌙</button>
        </div>
    </header>

    <main class="container">

        <section class="profile-header-card">
            <div class="profile-avatar">
                <img id="profile-avatar-main" src="<?php echo $avatar_final; ?>" alt="Mi Avatar">
            </div>
            <div class="profile-info">
                <h1 id="profile-username-main"><?php echo htmlspecialchars($nombre); ?></h1>
                
                <p class="profile-join-date">Miembro de ESCINE</p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <strong id="stat-reviews"><?php echo count($mis_resenas); ?></strong>
                        <span>Reseñas</span>
                    </div>
                    <div class="stat-item">
                        <strong id="stat-watchlist"><?php echo count($mi_lista); ?></strong>
                        <span>En Lista</span>
                    </div>                                     
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn btn-secondary btn-small" onclick="openTab(event, 'tab-settings'); document.querySelector('.tab-btn:nth-child(3)').classList.add('active');">
                    <i class="fas fa-cog"></i> Editar Perfil
                </button>
            </div>
        </section>

        <div class="profile-tabs">
            <button class="tab-btn active" onclick="openTab(event, 'tab-watchlist')">Mi Lista de Seguimiento</button>
            <button class="tab-btn" onclick="openTab(event, 'tab-reviews')">Mis Reseñas</button>
            <button class="tab-btn" onclick="openTab(event, 'tab-settings')">Configuración</button>
        </div>

        <div id="tab-watchlist" class="tab-content active">
    <div class="movie-grid" id="watchlist-grid">
        
        <?php if (count($mi_lista) > 0): ?>
            <?php foreach ($mi_lista as $item): ?>
                
                <article class="movie-card watchlist-dynamic-item" 
                         data-tmdb-id="<?php echo $item['idCont']; ?>" 
                         data-type="<?php echo $item['tipo']; ?>">
                    
                    <a href="detalle-pelicula.php?id=<?php echo $item['idCont']; ?>&type=<?php echo $item['tipo']; ?>" style="text-decoration: none; color: inherit;">
                        <img class="poster-watchlist" src="img/no-poster.jpg" alt="Poster" style="width: 100%; height: 300px; object-fit: cover;">
                        <div class="movie-info">
                            <h3 class="title-watchlist" style="font-size: 16px;">Cargando...</h3>
                            <div class="rating-watchlist" style="color: #f0c14b; font-weight: bold;">⭐ --</div>
                        </div>
                    </a>

                </article>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-message">No has añadido nada a tu lista aún.</p>
        <?php endif; ?>

    </div>
</div>

        <div id="tab-reviews" class="tab-content">
            <div class="review-list">
                <?php if (count($mis_resenas) > 0): ?>
                    <?php foreach ($mis_resenas as $fila): ?>
                        <article class="review-card-full review-dynamic-item" data-tmdb-id="<?php echo $fila['idCont']; ?>">
                            <div class="review-user-info">
                                <img class="poster-placeholder" src="img/no-poster.jpg" alt="Poster" style="border-radius: 4px; width: 60px; height: 90px; object-fit: cover;">
                                <div style="width: 100%;">
                                    <h3 class="movie-title-placeholder" style="margin: 0; font-size: 18px;">Cargando película...</h3>
                                    <div class="review-meta" style="margin-top: 5px;">
                                        <span class="review-rating" style="color: #f0c14b; font-weight: bold;">
                                            ⭐ <?php echo $fila['calificacion']; ?>/10
                                        </span>
                                        <?php if($fila['tieneSpoiler'] == 1): ?>
                                            <span style="background: #E50914; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 10px;">SPOILER</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="review-comment-full" style="color: #ccc; font-style: italic;">
                                "<?php echo htmlspecialchars($fila['comentario']); ?>"
                            </p>
                            <div class="review-actions-footer" style="display: flex; align-items: center; gap: 10px;">
    
    <a href="editar-resena.php?idCont=<?php echo $fila['idCont']; ?>" 
       style="color: #aaa; text-decoration: none; font-size: 14px; cursor: pointer; margin-right: 10px;"
       onmouseover="this.style.color='#E50914'" 
       onmouseout="this.style.color='#aaa'">
        <i class="fas fa-edit"></i> Editar
    </a>

    <a href="borrar-resena.php?idCont=<?php echo $fila['idCont']; ?>" 
       onclick="return confirm('¿Estás seguro de borrar esta reseña?');"
       style="color: #aaa; text-decoration: none; font-size: 14px; cursor: pointer;"
       onmouseover="this.style.color='var(--color-error)'" 
       onmouseout="this.style.color='#aaa'">
        <i class="fas fa-trash"></i> Borrar
    </a>

</div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-message">No has escrito ninguna reseña todavía.</p>
                <?php endif; ?>
            </div>
        </div>

        <div id="tab-settings" class="tab-content">
            <div class="form-container" style="margin: 0 auto; max-width: 600px;">
                <h2>Personaliza tu Perfil</h2>
                
                <div class="avatar-upload-area" style="margin-bottom: 30px;">
                    <p style="margin-bottom: 10px; font-weight: bold;">Foto de Perfil (Opcional)</p>
                    <div id="drop-zone" class="drop-zone">
                        <img id="preview-avatar" src="<?php echo $avatar_final; ?>" alt="Vista Previa">
                        <p style="margin: 0; font-size: 14px; color: #aaa;">Arrastra tu foto aquí o haz clic para subir</p>
                        <input type="file" id="avatar-input" accept="image/*" style="display: none;">
                    </div>
                    <p id="upload-status" style="font-size: 13px; margin-top: 5px;"></p>
                </div>

                <hr style="border-color: #333; margin: 20px 0;">

                <h2>Datos de la Cuenta</h2>
                <form action="actualizar-perfil.php" method="POST">
                    <div class="form-group">
                        <h3>Cambiar Nombre</h3>
                        <input type="text" name="usuario" value="<?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <h3>Cambiar Correo</h3>
                        <input type="email" name="correo" placeholder="Nuevo correo" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
    <script src="api.js"></script>
    
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const apiKey = '';
            const reviewCards = document.querySelectorAll('.review-dynamic-item');

            reviewCards.forEach(card => {
                const movieId = card.getAttribute('data-tmdb-id');
                const titleEl = card.querySelector('.movie-title-placeholder');
                const imgEl = card.querySelector('.poster-placeholder');

                if (!movieId) return;

                fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=es-MX`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.title) {
                            titleEl.textContent = data.title + (data.release_date ? ` (${data.release_date.split('-')[0]})` : '');
                            titleEl.style.cursor = 'pointer';
                            titleEl.onclick = () => window.location.href = `detalle-pelicula.php?id=${movieId}&type=movie`;
                        }
                        if (data.poster_path) {
                            imgEl.src = `https://image.tmdb.org/t/p/w200${data.poster_path}`;
                        }
                    })
                    .catch(err => {
                        console.error("Error cargando peli:", err);
                        titleEl.textContent = "Película no disponible";
                    });
            });
        });
    </script>
    <script>
    // Cargar datos de la Lista (Watchlist)
    document.addEventListener('DOMContentLoaded', () => {
        const apiKey = '';
        const listCards = document.querySelectorAll('.watchlist-dynamic-item');

        listCards.forEach(card => {
            const id = card.getAttribute('data-tmdb-id');
            const type = card.getAttribute('data-type'); 
            const imgEl = card.querySelector('.poster-watchlist');
            const titleEl = card.querySelector('.title-watchlist');
            const ratingEl = card.querySelector('.rating-watchlist');

            fetch(`https://api.themoviedb.org/3/${type}/${id}?api_key=${apiKey}&language=es-MX`)
                .then(res => res.json())
                .then(data => {
                    const title = data.title || data.name;
                    const date = data.release_date || data.first_air_date;
                    const year = date ? date.split('-')[0] : '';
                    
                    if(titleEl) titleEl.textContent = `${title} (${year})`;
                    if(ratingEl) ratingEl.textContent = `⭐ ${data.vote_average ? data.vote_average.toFixed(1) : '--'}`;
                    
                    if (data.poster_path && imgEl) {
                        imgEl.src = `https://image.tmdb.org/t/p/w300${data.poster_path}`;
                    }
                });
        });
    });
    </script>
    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('avatar-input');
        const previewImg = document.getElementById('preview-avatar');
        const statusText = document.getElementById('upload-status');

        
        dropZone.addEventListener('click', () => fileInput.click());

        
        fileInput.addEventListener('change', (e) => handleFile(e.target.files[0]));

        
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            handleFile(e.dataTransfer.files[0]);
        });

        function handleFile(file) {
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                statusText.textContent = " Solo se permiten imágenes.";
                statusText.style.color = "red";
                return;
            }

            
            const reader = new FileReader();
            reader.onload = (e) => previewImg.src = e.target.result;
            reader.readAsDataURL(file);

            
            uploadAvatar(file);
        }

        function uploadAvatar(file) {
            statusText.textContent = "Subiendo...";
            statusText.style.color = "#aaa";

            const formData = new FormData();
            formData.append('avatarFile', file);

            fetch('subir-avatar.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    statusText.textContent = "¡Foto actualizada!";
                    statusText.style.color = "#4CAF50";
                    
                    
                    let userProfile = JSON.parse(localStorage.getItem('escine_profile')) || {};
                    userProfile.avatarCustom = data.path; 
                    localStorage.setItem('escine_profile', JSON.stringify(userProfile));

                    
                    setTimeout(() => location.reload(), 1000);
                } else {
                    statusText.textContent = " X " + data.message;
                    statusText.style.color = "red";
                }
            })
            .catch(err => {
                statusText.textContent = " Error de conexión.";
                console.error(err);
            });
        }
    </script>

</body>
</html>