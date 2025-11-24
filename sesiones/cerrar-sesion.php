<?php
session_start(); // Abrimos sesión para leer datos y luego matarla

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Si entraste por la URL (GET), te devuelvo a tu perfil.
    // No cierro la sesión.
    header('Location: mi-perfil.php?error=accion_invalida');
    exit();
}
?>

<html>
<body>

    <h1>
        <?php
        if(!empty($_SESSION['usuario_nombre'])) {
            // htmlspecialchars es vital aquí también por seguridad
            echo "Adios, " . htmlspecialchars($_SESSION['usuario_nombre']) . '<br>'; 
        }
        ?>
    </h1>

    <hr>
    <p><em>Se ha cerrado la sesión y borrado la cookie.</em></p>

    <?php
    // 2. LIMPIEZA TOTAL
    session_unset();   // Borra las variables del array $_SESSION (vacía la memoria RAM)
    session_destroy(); // Borra el archivo de sesión del servidor (rompe el vínculo)
    ?>

    <a href="index.php">Volver al inicio</a>

</body>
</html>