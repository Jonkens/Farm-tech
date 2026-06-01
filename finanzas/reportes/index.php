<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Reportes generales del sistema
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

date_default_timezone_set('America/Mexico_City');

$periodo = $_GET['periodo'] ?? 'dia';
$hoy = date('Y-m-d');

switch ($periodo) {
    case 'semana':
        $inicio = date('Y-m-d', strtotime('monday this week'));
        $fin    = date('Y-m-d', strtotime('sunday this week'));
        break;
    case 'mes':
        $inicio = date('Y-m-01');
        $fin    = date('Y-m-t');
        break;
    default:
        $inicio = $hoy;
        $fin    = $hoy;
}

$lecheTotal    = obtenerProduccionLeche($pdo, $inicio, $fin);
$lecheDetalle  = obtenerProduccionLecheDetalle($pdo, $inicio, $fin);
$sacrificios   = obtenerSacrificios($pdo, $inicio, $fin);
$huevosTotal   = obtenerProduccionHuevos($pdo, $inicio, $fin);

$razas         = obtenerDistribucionRaza($pdo);
$sexos         = obtenerDistribucionSexo($pdo);
$especies      = obtenerDistribucionEspecie($pdo);
$estados       = obtenerDistribucionEstado($pdo);
$establos      = obtenerDistribucionEstablo($pdo);
$crecimiento   = obtenerCrecimiento($pdo, 20);

$ingresos      = obtenerIngresosPeriodo($pdo, $inicio, $fin);
$gastos        = obtenerGastosPeriodo($pdo, $inicio, $fin);
$transacciones = obtenerTransacciones($pdo, $inicio, $fin);
$nomina        = obtenerNomina($pdo);
$anuncios      = obtenerAnunciosActivos($pdo);

$alimentacion  = obtenerAlimentacion($pdo, $inicio, $fin);
$catalogo      = obtenerCatalogoAlimentos($pdo);
$eficiencia    = obtenerEficienciaNutricional($pdo);

// Nuevas consultas
$historialSalud   = obtenerHistorialSalud($pdo);
$resumenVentas    = obtenerResumenVentas($pdo, $inicio, $fin);
$ordenesCompra    = obtenerOrdenesCompra($pdo);

$datosVista = [
    'periodo'       => $periodo,
    'inicio'        => $inicio,
    'fin'           => $fin,
    'lecheTotal'    => $lecheTotal[0]['total'] ?? 0,
    'lecheDetalle'  => $lecheDetalle,
    'sacrificios'   => $sacrificios,
    'huevosTotal'   => $huevosTotal[0]['total'] ?? 0,
    'razas'         => $razas,
    'sexos'         => $sexos,
    'especies'      => $especies,
    'estados'       => $estados,
    'establos'      => $establos,
    'crecimiento'   => $crecimiento,
    'ingresos'      => $ingresos,
    'gastos'        => $gastos,
    'ganancia'      => $ingresos - $gastos,
    'transacciones' => $transacciones,
    'nomina'        => $nomina,
    'anuncios'      => $anuncios,
    'alimentacion'  => $alimentacion,
    'catalogo'      => $catalogo,
    'eficiencia'    => $eficiencia,
    'historialSalud'=> $historialSalud,
    'resumenVentas' => $resumenVentas,
    'ordenesCompra' => $ordenesCompra,
];

require __DIR__ . '/vista.php';