<?php
// herramientas/partos/registros/reportes/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../../../includes/query_helper.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

$pdo = getDB();

// ----- Estadísticas generales (desde helper) -----
$totalAnimales = contarAnimales($pdo);
$totalPartos   = (int) $pdo->query("SELECT COUNT(*) FROM partos")->fetchColumn();
$totalPrenadas = contarPrenadas($pdo);

// ----- Datos específicos para reportes (desde consultas.php) -----
$distribucionTipos = obtenerDistribucionPorTipo($pdo);
$partosMensuales   = obtenerPartosUltimosMeses($pdo, 6);
$partosPorTipo     = obtenerPartosPorTipoAnimal($pdo);
$ultimosPartos     = obtenerUltimosPartos($pdo, 5);

// Preparar arrays para gráficos
$tiposNombres     = array_column($distribucionTipos, 'tipo');
$tiposCantidades  = array_column($distribucionTipos, 'cantidad');
$mesesLabels      = array_column($partosMensuales, 'mes_label');
$partosCantidades = array_column($partosMensuales, 'total');

// Para gráfico de partos por tipo
$tipoPartoLabels = array_keys($partosPorTipo);
$tipoPartoData   = array_values($partosPorTipo);

// Incluir la vista
require __DIR__ . '/vista.php';