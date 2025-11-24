<?php
session_start();
if(empty($_SESSION['usuario_nombre']) || empty($_SESSION['usuario_id'])) {
  header('Location: index.php?error=sesion_incorrecta');
  exit(); 
}

$nombre_usuario = $_SESSION['usuario_nombre'];
$id_usuario = $_SESSION['usuario_id'];
?>

<html>
<body>

    <h1>
        Hola, <?php echo htmlspecialchars($nombre_usuario); ?>
        </h1>

    <form action="cerrar-sesion.php" method="POST">
        <p>
            <input type="submit" name="boton" value="Cerrar la sesion">
        </p>
    </form>

    <hr>

    <a href="formulario2.php">Ir a formulario 2</a>

</body>
</html>
