<?php
/**
 * Control de Alimentación y Nutrición – Controlador
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

session_start();
function redirectWithMessage($msg, $type, $tab = 'alimentacion')
{
    $_SESSION['flash'] = ['message' => $msg, 'type' => $type];
    header("Location: index.php?tab=$tab");
    exit;
}

$tab = $_GET['tab'] ?? 'alimentacion';
$porPagina = 7;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

// ---- PROCESAR ACCIONES ----
if (isset($_POST['agregar_alimentacion'])) {
    insertarAlimentacion($pdo, (int)$_POST['animal_id'], (int)$_POST['food_id'], $_POST['feeding_date'], (float)$_POST['quantity_kg']);
    redirectWithMessage("Alimentación registrada", "success", "alimentacion");
}
if (isset($_POST['actualizar_alimentacion'])) {
    actualizarAlimentacion($pdo, (int)$_POST['id'], (int)$_POST['animal_id'], (int)$_POST['food_id'], $_POST['feeding_date'], (float)$_POST['quantity_kg']);
    redirectWithMessage("Registro actualizado", "success", "alimentacion");
}
if (isset($_GET['eliminar_alimentacion'])) {
    eliminarAlimentacion($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro eliminado", "success", "alimentacion");
}

if (isset($_POST['agregar_alimento'])) {
    insertarAlimento($pdo, $_POST['name'], $_POST['food_type'], (float)$_POST['cost_per_kg'], (float)$_POST['protein_pct'], (float)$_POST['stock_kg']);
    redirectWithMessage("Alimento agregado", "success", "catalogo");
}
if (isset($_POST['actualizar_alimento'])) {
    actualizarAlimento($pdo, (int)$_POST['id'], $_POST['name'], $_POST['food_type'], (float)$_POST['cost_per_kg'], (float)$_POST['protein_pct'], (float)$_POST['stock_kg']);
    redirectWithMessage("Alimento actualizado", "success", "catalogo");
}
if (isset($_GET['eliminar_alimento'])) {
    eliminarAlimento($pdo, (int)$_GET['id']);
    redirectWithMessage("Alimento eliminado", "success", "catalogo");
}

if (isset($_GET['eliminar_eficiencia'])) {
    eliminarEficiencia($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro de eficiencia eliminado", "success", "eficiencia");
}

// ---- CÁLCULO FCR ----
$fcrResultado = null;
if (isset($_POST['calcular_fcr'])) {
    $animalId    = (int)$_POST['animal_id'];
    $inicio      = $_POST['fecha_inicio'];
    $fin         = $_POST['fecha_fin'];
    $pesoInicial = (float)$_POST['peso_inicial'];
    $pesoFinal   = (float)$_POST['peso_final'];
    $fcrResultado = calcularFCR($pdo, $animalId, $inicio, $fin, $pesoInicial, $pesoFinal);
}

if (isset($_POST['guardar_fcr'])) {
    insertarEficiencia($pdo, (int)$_POST['animal_id'], $_POST['measurement_date'], (float)$_POST['fcr'], (float)$_POST['ganancia_kg']);
    redirectWithMessage("Eficiencia registrada", "success", "eficiencia");
}

// ---- OBTENER DATOS PARA VISTA ----
$alimentacion  = ($tab === 'alimentacion') ? obtenerAlimentacionPaginado($pdo, $porPagina, ($page - 1) * $porPagina) : [];
$totalAliment  = ($tab === 'alimentacion') ? contarAlimentacion($pdo) : 0;
$totalPaginas  = ceil($totalAliment / $porPagina);

$catalogo      = obtenerCatalogoAlimentos($pdo);
$eficiencia    = ($tab === 'eficiencia') ? obtenerEficienciaPaginado($pdo, $porPagina, ($page - 1) * $porPagina) : [];
$totalEfi      = ($tab === 'eficiencia') ? contarEficiencia($pdo) : 0;
$totalPagEfi   = ceil($totalEfi / $porPagina);

$animales      = obtenerAnimalesParaSelect($pdo);
$alimentos     = obtenerAlimentos($pdo);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$stockMinimo = 50;

$datosVista = [
    'tab'            => $tab,
    'page'           => $page,
    'flash'          => $flash,
    'alimentacion'   => $alimentacion,
    'totalPaginas'   => $totalPaginas,
    'catalogo'       => $catalogo,
    'eficiencia'     => $eficiencia,
    'totalPagEfi'    => $totalPagEfi,
    'animales'       => $animales,
    'alimentos'      => $alimentos,
    'fcrResultado'   => $fcrResultado,
    'stockMinimo'    => $stockMinimo,
    'post'           => $_POST,
];

require __DIR__ . '/vista.php';