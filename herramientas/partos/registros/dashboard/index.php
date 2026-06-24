<?php
// herramientas/partos/registros/dashboard/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';                    // 2 niveles: a partos/
require_once __DIR__ . '/../../../../includes/query_helper.php'; // 4 niveles: a la raíz
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

$pdo = getDB();

// ----- Estadísticas generales (desde helpers) -----
$totalMachos   = contarMachos($pdo);
$totalHembras  = contarHembras($pdo);
$totalCrias    = contarCrias($pdo);
$totalAnimales = contarAnimales($pdo);

$partosMes     = contarPartosMes($pdo);
$eventosMes    = contarEventosMes($pdo);
$prenadas      = contarPrenadas($pdo);

$ultimosPartos = obtenerUltimosPartos($pdo, 5);
$alertas       = obtenerAlertasPreñez($pdo);

// ----- Datos específicos del dashboard (desde consultas.php) -----
$tiposDatos = obtenerDistribucionPorTipo($pdo);
$partosMensuales = obtenerPartosUltimosMeses($pdo, 6);

// Preparar arrays para los gráficos
$tiposNombres     = array_column($tiposDatos, 'tipo');
$tiposCantidades  = array_column($tiposDatos, 'cantidad');
$mesesLabels      = array_column($partosMensuales, 'mes_label');
$partosCantidades = array_column($partosMensuales, 'total');

// Incluir la vista
require __DIR__ . '/vista.php';