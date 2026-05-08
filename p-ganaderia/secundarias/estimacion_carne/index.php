<?php
/**
 * Estimación de Carne – Controlador
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';   // $pdo
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

// Obtener vacas sacrificadas para el selector
$vacasSacrificadas = obtenerVacasSacrificadas($pdo);

// Pasar datos a la vista
$datosVista = [
    'vacasSacrificadas' => $vacasSacrificadas
];

require __DIR__ . '/vista.php';