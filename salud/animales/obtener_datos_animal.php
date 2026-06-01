<?php
require_once __DIR__ . '/../../conexion.php';
header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT alimentacion, consumo_agua FROM animals WHERE id = ?");
$stmt->execute([$id]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

if ($resultado) {
    echo json_encode($resultado);
} else {
    echo json_encode(['alimentacion' => '', 'consumo_agua' => '']);
}
?>