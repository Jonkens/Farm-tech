<?php
/**
 * Módulo Compra, Venta y Finanzas – Controlador
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

date_default_timezone_set('America/Mexico_City');

// Mes actual para resumen financiero
$inicioMes = date('Y-m-01');
$finMes    = date('Y-m-t');

// Obtener datos
$anuncios      = obtenerAnunciosActivos($pdo);
$transacciones = obtenerTransaccionesRecientes($pdo, 15);
$ordenesCompra = obtenerOrdenesCompra($pdo);
$empleados     = obtenerEmpleados($pdo);
$nominaUltima  = obtenerUltimaNomina($pdo);
$ingresosMes   = obtenerIngresosPeriodo($pdo, $inicioMes, $finMes);
$gastosMes     = obtenerGastosPeriodo($pdo, $inicioMes, $finMes);
$gananciaMes   = calcularGanancia($ingresosMes, $gastosMes);

// Calcular total planilla desde la última nómina
$totalPlanilla = array_sum(array_column($nominaUltima, 'net_pay'));

$datosVista = [
    'anuncios'        => $anuncios,
    'transacciones'   => $transacciones,
    'ordenesCompra'   => $ordenesCompra,
    'empleados'       => $empleados,
    'nominaUltima'    => $nominaUltima,
    'totalPlanilla'   => $totalPlanilla,
    'ingresosMes'     => $ingresosMes,
    'gastosMes'       => $gastosMes,
    'gananciaMes'     => $gananciaMes,
    'inicioMes'       => $inicioMes,
    'finMes'          => $finMes,
];

require __DIR__ . '/vista.php';