<?php
    // abrimos la conexión a la BD 
    include 'conexion.php';

    $nombre_usuario = trim($_POST['usuario']);
    $correo_usuario = trim($_POST['correo']);
    $contrasena = trim($_POST["contrasena"]);
    $contrasena_confirmar = trim($_POST["confirmar_contrasena"]);

    // Para evitar que el usuario tenga que escribir todos los datos de nuevo (no le pasamos la contraseña por seguridad)
    $datos_usuario = "&usuario=" . urlencode($nombre_usuario) . "&correo=" . urlencode($correo_usuario);

    //Verificamos datos enviados
    if (empty($nombre_usuario) || empty($correo_usuario) || empty($contrasena) || empty($contrasena_confirmar)) {
        header('Location: registro.php?error=campos_vacios' . $datos_usuario);
        exit();
    }

    if (!filter_var($correo_usuario, FILTER_VALIDATE_EMAIL)) {
        header("Location: registro.php?error=formato_correo_invalido" . $datos_usuario);
        exit();
    }

    if ($contrasena !== $contrasena_confirmar) {
        header('Location: registro.php?error=contrasenas_no_coinciden'  . $datos_usuario);
        exit();
    }

    try{
        //Verificar si el correo o usuario ya existe en la BD
        $sql_verificar_credenciales = "SELECT usuario, correo FROM usuarios WHERE usuario = ? OR correo = ?";
        $sentencia_verificar = $conn->prepare($sql_verificar_credenciales);
        $sentencia_verificar->execute([$nombre_usuario, $correo_usuario]);

        $fila = $sentencia_verificar->fetch(PDO::FETCH_ASSOC);

        if($fila){
            if ($fila['usuario'] === $nombre_usuario) {
                header('Location: registro.php?error=usuario_invalido' . $datos_usuario);
            } elseif ($fila['correo'] === $correo_usuario) {
                header('Location: registro.php?error=correo_invalido' . $datos_usuario);
            }
            exit();
        }

        //Hacemos el hashing de la primera contraseña
        $contrasena_hasheada = password_hash($contrasena, PASSWORD_DEFAULT);

        //Preparamos la consulta...
        $sql = "INSERT INTO usuarios (usuario, correo, contrasena) VALUES (?,?,?)";

        $sentencia_agregar = $conn->prepare($sql); 

        if($sentencia_agregar->execute([$nombre_usuario,$correo_usuario,  $contrasena_hasheada])){
            header('Location: index.php?mensaje=registro_exitoso');
            exit();
        } else {
            header('Location: registro.php?error=falla_bd' . $datos_usuario);
            exit();
        }
    } catch(PDOException $e){
        error_log("Error en registro: " . $e->getMessage());
        header('Location: registro.php?error=falla_sistema');
        exit();
    }
?>