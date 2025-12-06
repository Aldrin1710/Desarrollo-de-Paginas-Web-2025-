<?php
require 'conexion.php';


header('Content-Type: application/json');

$idCont = $_GET['id'] ?? null;

if (!$idCont) {
    echo json_encode(['promedio' => null, 'votos' => 0]);
    exit();
}

try {
    
    $sql = "SELECT AVG(calificacion) as promedio, COUNT(*) as votos FROM reseña WHERE idCont = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$idCont]);
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    $promedio = $resultado['promedio'] ? round($resultado['promedio'], 1) : null;
    $votos = $resultado['votos'];

    echo json_encode(['promedio' => $promedio, 'votos' => $votos]);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error de BD']);
}
?>