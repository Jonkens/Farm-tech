<?php
// herramientas/partos/registros/genealogia/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../../../includes/query_helper.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

$pdo = getDB();

// Obtener lista de todos los animales para el selector
$animales = obtenerAnimales(); // del helper

// Obtener el animal seleccionado (por GET)
$animalId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$animalSeleccionado = null;
$padres = ['madre' => null, 'padre' => null];
$crias = [];

if ($animalId > 0) {
    $animalSeleccionado = obtenerAnimalCompleto($animalId);
    if ($animalSeleccionado) {
        $padres = obtenerPadres($animalId);
        $crias = obtenerCriasDeAnimal($animalId);
    }
}

// Incluir la vista
require __DIR__ . '/vista.php';