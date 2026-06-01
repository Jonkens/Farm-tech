<?php
/**
 * Distribución del Hato – Controlador
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';   // $pdo
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

// Datos de distribución
$razas      = obtenerDistribucionPorRaza($pdo);
$sexos      = obtenerDistribucionPorSexo($pdo);
$actividades = obtenerDistribucionPorActividad($pdo);
$especies   = obtenerDistribucionPorEspecie($pdo);
$establos   = obtenerDistribucionPorEstablo($pdo);

// Animales con peso
$animalesPeso = obtenerAnimalesConPeso($pdo);
$estadisticas = calcularEstadisticasPeso($animalesPeso);

// Todos los animales para detalles
$todosLosAnimales = obtenerTodosLosAnimales($pdo);

// Totales para las tarjetas
$totalRazas      = array_sum(array_column($razas, 'total'));
$totalSexos      = array_sum(array_column($sexos, 'total'));
$totalActividad  = array_sum(array_column($actividades, 'total'));
$totalEspecies   = array_sum(array_column($especies, 'total'));
$totalEstablos   = array_sum(array_column($establos, 'total'));

$datosVista = [
    'razas'            => $razas,
    'sexos'            => $sexos,
    'actividades'      => $actividades,
    'especies'         => $especies,
    'establos'         => $establos,
    'animalesPeso'     => $animalesPeso,
    'estadisticas'     => $estadisticas,
    'todosLosAnimales' => $todosLosAnimales,
    'totalRazas'       => $totalRazas,
    'totalSexos'       => $totalSexos,
    'totalActividad'   => $totalActividad,
    'totalEspecies'    => $totalEspecies,
    'totalEstablos'    => $totalEstablos,
];

require __DIR__ . '/vista.php';