<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexion.php';
require_once 'includes/query_helper.php';

$vacas = ejecutarConsulta($pdo, "SELECT * FROM cows WHERE status = 'produciendo' LIMIT 2");
if ($vacas) {
    print_r($vacas);
} else {
    echo "No hay resultados";
}