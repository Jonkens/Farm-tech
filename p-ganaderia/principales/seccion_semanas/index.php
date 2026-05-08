<?php
/**
 * Comparativa Semanal – Controlador
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';   // $pdo
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

date_default_timezone_set('America/Mexico_City');

// ---- Semana fija (última con datos) ----
$semanaFijaStart = obtenerUltimaSemanaConDatos($pdo);
$semanaFijaEnd   = date('Y-m-d', strtotime($semanaFijaStart . ' +6 days'));

// ---- Semana a comparar ----
$selectedStart = $_GET['week_start'] ?? null;
if ($selectedStart && preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedStart)) {
    $semanaCompararStart = date('Y-m-d', strtotime($selectedStart));
} else {
    $semanaCompararStart = date('Y-m-d', strtotime($semanaFijaStart . ' -7 days'));
}
$semanaCompararEnd = date('Y-m-d', strtotime($semanaCompararStart . ' +6 days'));

if (strtotime($semanaCompararStart) > strtotime($semanaFijaStart)) {
    $semanaCompararStart = date('Y-m-d', strtotime($semanaFijaStart . ' -7 days'));
    $semanaCompararEnd   = date('Y-m-d', strtotime($semanaCompararStart . ' +6 days'));
}

// ---- Obtener datos de producción ----
$lecheFija      = obtenerTotalesSemanales($pdo, 'milk_production', 'production_date', 'quantity_liters', $semanaFijaStart);
$lecheComparar  = obtenerTotalesSemanales($pdo, 'milk_production', 'production_date', 'quantity_liters', $semanaCompararStart);

$carneFija      = obtenerTotalesSemanales($pdo, 'slaughter_records', 'slaughter_date', 'quantity', $semanaFijaStart);
$carneComparar  = obtenerTotalesSemanales($pdo, 'slaughter_records', 'slaughter_date', 'quantity', $semanaCompararStart);

$huevosFija     = obtenerTotalesSemanales($pdo, 'egg_production', 'production_date', 'quantity', $semanaFijaStart);
$huevosComparar = obtenerTotalesSemanales($pdo, 'egg_production', 'production_date', 'quantity', $semanaCompararStart);

// ---- Cálculos adicionales ----
$cambioLeche  = calcularCambioPorcentual($lecheFija['total'], $lecheComparar['total']);
$cambioCarne  = calcularCambioPorcentual($carneFija['total'], $carneComparar['total']);
$cambioHuevos = calcularCambioPorcentual($huevosFija['total'], $huevosComparar['total']);

$detalleSacrificiosFija = obtenerDetalleSacrificios($pdo, $semanaFijaStart, $semanaFijaEnd);
$kgCarneFija            = calcularKgCarne($detalleSacrificiosFija);

$totalGallinas    = obtenerGallinasActivas($pdo, $semanaFijaEnd);
$eficienciaHuevos = calcularEficienciaHuevos($huevosFija['total'], $totalGallinas);

$promedioLecheFija  = promedioDiario($lecheFija['total']);
$promedioHuevosFija = promedioDiario($huevosFija['total']);

// ---- Preparar datos para la vista ----
$diasEtiquetas = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

$datosVista = [
    'semanaFijaStart'      => $semanaFijaStart,
    'semanaFijaEnd'        => $semanaFijaEnd,
    'semanaCompararStart'  => $semanaCompararStart,
    'semanaCompararEnd'    => $semanaCompararEnd,
    'lecheFija'            => $lecheFija,
    'lecheComparar'        => $lecheComparar,
    'carneFija'            => $carneFija,
    'carneComparar'        => $carneComparar,
    'huevosFija'           => $huevosFija,
    'huevosComparar'       => $huevosComparar,
    'cambioLeche'          => $cambioLeche,
    'cambioCarne'          => $cambioCarne,
    'cambioHuevos'         => $cambioHuevos,
    'detalleSacrificiosFija' => $detalleSacrificiosFija,
    'kgCarneFija'          => $kgCarneFija,
    'totalGallinas'        => $totalGallinas,
    'eficienciaHuevos'     => $eficienciaHuevos,
    'promedioLecheFija'    => $promedioLecheFija,
    'promedioHuevosFija'   => $promedioHuevosFija,
    'diasEtiquetas'        => $diasEtiquetas,
    'pesosPromedio'        => PESOS_CARNE,
];

// ---- Cargar vista ----
require __DIR__ . '/vista.php';