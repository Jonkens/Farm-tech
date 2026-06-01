<?php
require_once __DIR__ . '/../../conexion.php';
header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID no proporcionado']);
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT fecha_chequeo, estado_salud, enfermedad_detectada, vacuna_aplicada, tratamiento, observaciones 
        FROM animal_health_history 
        WHERE animal_id = ? 
        ORDER BY fecha_chequeo DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$historial = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($historial);
?>