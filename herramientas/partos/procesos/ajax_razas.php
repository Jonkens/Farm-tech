<?php
// herramientas/partos/procesos/ajax_razas.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../../includes/query_helper.php';

header('Content-Type: application/json');

$tipoId = (int) ($_GET['tipo_id'] ?? 0);
if ($tipoId <= 0) {
    echo json_encode([]);
    exit;
}

echo json_encode(obtenerRazasPorTipo($tipoId));