<?php
// herramientas/partos/procesos/editar_evento.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../../includes/query_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?pagina=registrar-evento&error=1');
    exit;
}

$id = (int) ($_POST['id_editar'] ?? 0);
$animalId = (int) ($_POST['animal_id'] ?? 0);
$tipo = $_POST['tipo_evento'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$padreId = !empty($_POST['padre_id']) ? (int) $_POST['padre_id'] : null;
$notas = $_POST['notas'] ?? null;

if ($id <= 0 || $animalId <= 0 || empty($tipo) || empty($fecha)) {
    header('Location: ../index.php?pagina=registrar-evento&error=1');
    exit;
}

actualizarEvento($id, $animalId, $tipo, $fecha, $padreId, $notas);
header('Location: ../index.php?pagina=registrar-evento&editado=1');
exit;