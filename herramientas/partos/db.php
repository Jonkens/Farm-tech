<?php
// herramientas/partos/db.php
require_once __DIR__ . '/../../conexion.php';   // sube dos niveles hasta la raíz del proyecto

function getDB(): PDO {
    global $pdo;
    if (!isset($pdo)) {
        die('Error: No se ha inicializado la conexión PDO en conexion.php');
    }
    return $pdo;
}