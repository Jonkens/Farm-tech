<?php
// herramientas/partos/procesos/ajax_generar_arete.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../../includes/query_helper.php';

header('Content-Type: application/json');

$sexo = $_GET['sexo'] ?? '';
if (!in_array($sexo, ['Macho', 'Hembra'])) {
    echo json_encode(['success' => false]);
    exit;
}

echo json_encode(['success' => true, 'arete' => generarArete($sexo)]);