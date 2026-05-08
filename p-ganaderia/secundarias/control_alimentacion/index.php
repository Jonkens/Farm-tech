<?php
/**
 * Control de Alimentación y Nutrición – Controlador
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

$alimentacion  = obtenerAlimentacionReciente($pdo, 50);
$catalogo      = obtenerCatalogoAlimentos($pdo);
$eficiencia    = obtenerEficienciaNutricional($pdo);
// Si en un futuro se implementa inserción, aquí se capturaría $_POST

$datosVista = [
    'alimentacion' => $alimentacion,
    'catalogo'     => $catalogo,
    'eficiencia'   => $eficiencia,
];

require __DIR__ . '/vista.php';