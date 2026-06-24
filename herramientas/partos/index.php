<?php
// herramientas/partos/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/db.php';                         // mismo directorio
require_once __DIR__ . '/../../includes/query_helper.php'; // 2 niveles: a la raíz

// Determinar la página solicitada
$pagina = $_GET['pagina'] ?? 'dashboard';

// Páginas válidas para el módulo de partos
$paginas_validas = [
    'dashboard',
    'registrar-evento',
    'registrar-parto',
    'genealogia',
    'reportes'
];

if (!in_array($pagina, $paginas_validas)) {
    $pagina = 'dashboard';
}

// Ruta al controlador de la página (index.php dentro de su subcarpeta)
$ruta_controlador = __DIR__ . "/registros/{$pagina}/index.php";

if (file_exists($ruta_controlador)) {
    include $ruta_controlador;
} else {
    echo "<div class='bg-red-100 text-red-700 p-4 rounded'>Vista no encontrada</div>";
}