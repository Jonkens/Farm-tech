<?php
/**
 * Panel de Producción Diaria – Controlador
 * 
 * Obtiene datos, los procesa y los pasa a la vista.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';                // $pdo
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

date_default_timezone_set('America/Mexico_City');

// ---- Fechas de la semana actual (domingo a sábado) ----
$hoy = date('Y-m-d');
$diaSemana = (int) date('w', strtotime($hoy)); // 0 = domingo
$domingo = date('Y-m-d', strtotime("-{$diaSemana} days", strtotime($hoy)));
$sabado  = date('Y-m-d', strtotime('+6 days', strtotime($domingo)));

// ---- Datos principales ----
$vacas = obtenerVacasProduciendo($pdo);
$totalVacas = count($vacas);

$sacrificiosHoy = obtenerSacrificiosHoy($pdo, $hoy);
$totalSacrificiosHoy = array_sum(array_column($sacrificiosHoy, 'quantity'));

$sacrificiosSemana = obtenerSacrificiosSemana($pdo, $domingo, $sabado);
$totalAnimalesSemana = calcularTotalAnimalesSemana($sacrificiosSemana);
$totalKgCarneSemana = calcularTotalKgCarne($sacrificiosSemana);

$vacasSacrificadas = obtenerVacasSacrificadasRecientes($pdo, 5);

$totalGallinas = obtenerTotalGallinas($pdo);

$totalLecheSemana = obtenerTotalLecheSemana($pdo, $domingo, $sabado);
$promedioLecheDiario = calcularPromedioDiario($totalLecheSemana);

$totalHuevosSemana = obtenerTotalHuevosSemana($pdo, $domingo, $sabado);
$promedioHuevosDiario = calcularPromedioDiario($totalHuevosSemana);
$eficienciaGallinas = calcularEficienciaGallinas($totalHuevosSemana, $totalGallinas);

// ---- Datos para gráficas (7 días) ----
$fechas = [];
for ($i = 0; $i < 7; $i++) {
    $fechas[] = date('Y-m-d', strtotime("+$i days", strtotime($domingo)));
}
$diasEtiquetas = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

$lecheCrudo = obtenerLechePorFecha($pdo, $domingo, $sabado);
$carneCrudo = obtenerCarnePorFecha($pdo, $domingo, $sabado);
$huevosCrudo = obtenerHuevosPorFecha($pdo, $domingo, $sabado);

$lecheData = llenarDatos($fechas, $lecheCrudo);
$carneData = llenarDatos($fechas, $carneCrudo);
$huevosData = llenarDatos($fechas, $huevosCrudo);

// ---- Empaquetar datos para la vista ----
$datosVista = [
    'hoy'                   => $hoy,
    'domingo'               => $domingo,
    'sabado'                => $sabado,
    'totalVacas'            => $totalVacas,
    'vacas'                 => $vacas,
    'sacrificiosHoy'        => $sacrificiosHoy,
    'totalSacrificiosHoy'   => $totalSacrificiosHoy,
    'sacrificiosSemana'     => $sacrificiosSemana,
    'totalAnimalesSemana'   => $totalAnimalesSemana,
    'totalKgCarneSemana'    => $totalKgCarneSemana,
    'vacasSacrificadas'     => $vacasSacrificadas,
    'totalGallinas'         => $totalGallinas,
    'totalLecheSemana'      => $totalLecheSemana,
    'promedioLecheDiario'   => $promedioLecheDiario,
    'totalHuevosSemana'     => $totalHuevosSemana,
    'promedioHuevosDiario'  => $promedioHuevosDiario,
    'eficienciaGallinas'    => $eficienciaGallinas,
    'diasEtiquetas'         => $diasEtiquetas,
    'lecheData'             => $lecheData,
    'carneData'             => $carneData,
    'huevosData'            => $huevosData,
];

// ---- Cargar vista ----
require __DIR__ . '/vista.php';