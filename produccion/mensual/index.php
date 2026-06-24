<?php
/**
 * Comparativa Mensual – Controlador
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';   // $pdo
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

date_default_timezone_set('America/Mexico_City');

// Año fijo (último con datos)
$anioFijo = obtenerUltimoAnioConDatos($pdo);
$aniosDisponibles = obtenerAniosDisponibles($pdo, $anioFijo);

// Año a comparar
$selectedYear = $_GET['year'] ?? null;
if ($selectedYear && in_array((int)$selectedYear, $aniosDisponibles)) {
    $anioComparar = (int)$selectedYear;
} else {
    $anioComparar = $anioFijo - 1;
    if (!in_array($anioComparar, $aniosDisponibles)) {
        $anioComparar = $anioFijo;
    }
}

// Totales mensuales
$lecheFijo     = obtenerTotalesMensuales($pdo, 'produccion_leche', 'production_date', 'quantity_liters', $anioFijo);
$lecheComparar = obtenerTotalesMensuales($pdo, 'produccion_leche', 'production_date', 'quantity_liters', $anioComparar);

$carneFijo     = obtenerTotalesMensuales($pdo, 'registros_sacrificio', 'slaughter_date', 'quantity', $anioFijo);
$carneComparar = obtenerTotalesMensuales($pdo, 'registros_sacrificio', 'slaughter_date', 'quantity', $anioComparar);
$desgloseCarneFijo = obtenerDesgloseSacrificios($pdo, $anioFijo);

$huevosFijo     = obtenerTotalesMensuales($pdo, 'produccion_huevos', 'production_date', 'quantity', $anioFijo);
$huevosComparar = obtenerTotalesMensuales($pdo, 'produccion_huevos', 'production_date', 'quantity', $anioComparar);

// Gallinas y eficiencia
$promedioGallinasFijo = obtenerPromedioGallinas($pdo, $anioFijo);
$eficiencia = calcularEficienciaHuevos($huevosFijo['total'], $promedioGallinasFijo);

// Cambios porcentuales
$cambioLeche  = calcularCambioPorcentual($lecheFijo['total'], $lecheComparar['total']);
$cambioCarne  = calcularCambioPorcentual($carneFijo['total'], $carneComparar['total']);
$cambioHuevos = calcularCambioPorcentual($huevosFijo['total'], $huevosComparar['total']);

// Estadísticas adicionales
$lecheMesPico   = !empty($lecheFijo['mensual']) ? max($lecheFijo['mensual']) : 0;
$lechePromedio  = $lecheFijo['total'] > 0 ? round($lecheFijo['total'] / 12) : 0;
$huevosMesPico  = !empty($huevosFijo['mensual']) ? max($huevosFijo['mensual']) : 0;
$huevosPromedio = $huevosFijo['total'] > 0 ? round($huevosFijo['total'] / 12) : 0;

// Meses para etiquetas
$mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
$mesesCompletos = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

// Datos para la vista
$datosVista = [
    'anioFijo'              => $anioFijo,
    'anioComparar'          => $anioComparar,
    'aniosDisponibles'      => $aniosDisponibles,
    'lecheFijo'             => $lecheFijo,
    'lecheComparar'         => $lecheComparar,
    'carneFijo'             => $carneFijo,
    'carneComparar'         => $carneComparar,
    'huevosFijo'            => $huevosFijo,
    'huevosComparar'        => $huevosComparar,
    'desgloseCarneFijo'     => $desgloseCarneFijo,
    'promedioGallinasFijo'  => $promedioGallinasFijo,
    'eficiencia'            => $eficiencia,
    'cambioLeche'           => $cambioLeche,
    'cambioCarne'           => $cambioCarne,
    'cambioHuevos'          => $cambioHuevos,
    'lecheMesPico'          => $lecheMesPico,
    'lechePromedio'         => $lechePromedio,
    'huevosMesPico'         => $huevosMesPico,
    'huevosPromedio'        => $huevosPromedio,
    'mesesNombres'          => $mesesNombres,
    'mesesCompletos'        => $mesesCompletos,
    'pesosPromedio'         => ['Bovino' => 250, 'Porcino' => 80, 'Ovino' => 25, 'Caprino' => 20],
];

require __DIR__ . '/vista.php';