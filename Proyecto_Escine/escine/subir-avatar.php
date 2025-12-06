<?php
session_start();
require 'conexion.php';

header('Content-Type: application/json');


if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$idUsuario = $_SESSION['usuario_id'];


if (!isset($_FILES['avatarFile']) || $_FILES['avatarFile']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Error al subir archivo.']);
    exit;
}

$file = $_FILES['avatarFile'];


$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Formato no permitido (solo JPG, PNG, GIF, WEBP).']);
    exit;
}

if ($file['size'] > 5 * 1024 * 1024) { 
    echo json_encode(['success' => false, 'message' => 'Imagen muy pesada (Máx 5MB).']);
    exit;
}


$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = "user_" . $idUsuario . "_" . time() . "." . $ext;
$targetDir = "uploads/avatars/";
$targetFile = $targetDir . $fileName;


if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    
    
    try {
        
        $sqlOld = "SELECT avatar FROM usuarios WHERE idUsuario = ?";
        $stmtOld = $conn->prepare($sqlOld);
        $stmtOld->execute([$idUsuario]);
        $oldAvatar = $stmtOld->fetchColumn();
        
        if ($oldAvatar && file_exists($oldAvatar)) {
            unlink($oldAvatar);
        }

        
        $sql = "UPDATE usuarios SET avatar = ? WHERE idUsuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$targetFile, $idUsuario]);

        
        echo json_encode(['success' => true, 'path' => $targetFile]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error de BD']);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar archivo.']);
}
?>