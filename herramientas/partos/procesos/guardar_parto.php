<?php
// herramientas/partos/procesos/guardar_parto.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../../includes/query_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?pagina=registrar-parto&error=1');
    exit;
}

$madreId = (int) ($_POST['madre_id'] ?? 0);
$padreId = !empty($_POST['padre_id']) ? (int) $_POST['padre_id'] : null;
$fechaParto = $_POST['fecha_parto'] ?? '';
$peso = !empty($_POST['peso_kg']) ? (float) $_POST['peso_kg'] : null;
$notas = $_POST['notas'] ?? null;

$nombreCria = trim($_POST['nombre_cria'] ?? '');
$areteCria = trim($_POST['arete_cria'] ?? '');
$tipoIdCria = (int) ($_POST['tipo_id_cria'] ?? 0);
$sexoCria = $_POST['sexo_cria'] ?? '';
$razaIdCria = !empty($_POST['raza_id_cria']) ? (int) $_POST['raza_id_cria'] : null;

if ($madreId <= 0 || empty($fechaParto) || empty($nombreCria) || empty($areteCria) || $tipoIdCria <= 0 || empty($sexoCria)) {
    header('Location: ../index.php?pagina=registrar-parto&error=1');
    exit;
}

// 1. Guardar la cría como nuevo animal
$criaId = guardarAnimal($areteCria, $nombreCria, $razaIdCria, $tipoIdCria, $fechaParto, $peso, $sexoCria, $padreId, $madreId);

// 2. Registrar el parto
guardarParto($madreId, $padreId, $criaId, $fechaParto, $peso, $notas);

header('Location: ../index.php?pagina=registrar-parto&exito=1');
exit;