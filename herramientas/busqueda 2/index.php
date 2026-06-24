<?php
/**
 * Controlador para el registro de animales.
 * Orquesta las acciones, APIs, y carga la vista adecuada.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';  // $pdo
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

date_default_timezone_set('America/Mexico_City');

// ----------------------------------------------
// APIs (respuestas JSON)
// ----------------------------------------------

if (isset($_GET['get_razas']) && isset($_GET['tipo'])) {
    $tipoId = (int) $_GET['tipo'];
    $razas = obtenerRazasPorTipo($pdo, $tipoId);
    header('Content-Type: application/json');
    echo json_encode($razas);
    exit;
}

if (isset($_GET['get_next_tag']) && isset($_GET['gender'])) {
    $gender = $_GET['gender'];
    if ($gender !== 'M' && $gender !== 'F') {
        http_response_code(400);
        echo json_encode(['error' => 'Género inválido']);
        exit;
    }
    $tag = getNextTag($pdo, $gender);
    header('Content-Type: application/json');
    echo json_encode(['tag' => $tag]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_tipo') {
    $nombre = trim($_POST['nombre'] ?? '');
    if (empty($nombre)) {
        echo json_encode(['error' => 'El nombre es obligatorio']);
        exit;
    }
    $id = crearTipoAnimal($pdo, $nombre);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id, 'name' => $nombre]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_raza') {
    $nombre = trim($_POST['nombre'] ?? '');
    $tipoId = (int) ($_POST['animal_type_id'] ?? 0);
    if (empty($nombre) || $tipoId <= 0) {
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }
    $id = crearRaza($pdo, $nombre, $tipoId);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id, 'name' => $nombre]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'agregar_facilidad') {
    $nombre = trim($_POST['nombre'] ?? '');
    $tipo = trim($_POST['facility_type'] ?? '');
    $capacidad = isset($_POST['capacity']) && $_POST['capacity'] !== '' ? (int) $_POST['capacity'] : null;
    $ubicacion = trim($_POST['location'] ?? '');
    if (empty($nombre) || empty($tipo)) {
        echo json_encode(['error' => 'El nombre y el tipo son obligatorios']);
        exit;
    }
    $id = crearFacilidad($pdo, $nombre, $tipo, $capacidad, $ubicacion);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id, 'name' => $nombre, 'facility_type' => $tipo]);
    exit;
}

// ----------------------------------------------
// Acciones POST (crear, actualizar)
// ----------------------------------------------

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $entry_date   = $_POST['entry_date'] ?? null;
    $name         = trim($_POST['name'] ?? '');
    $animal_type_id = (int) ($_POST['animal_type_id'] ?? 0);
    $breed_id       = (int) ($_POST['breed_id'] ?? 0);
    $birth_date     = $_POST['birth_date'] ?? '';
    $weight_kg      = (float) ($_POST['weight_kg'] ?? 0);
    $gender         = $_POST['gender'] ?? '';
    $status         = $_POST['status'] ?? 'activo';
    $notes          = trim($_POST['notes'] ?? '');
    $tag            = trim($_POST['tag'] ?? '');
    $facility_id    = isset($_POST['facility_id']) && $_POST['facility_id'] !== '' ? (int) $_POST['facility_id'] : null;

    if ($name && $animal_type_id && $breed_id && $birth_date && $weight_kg && $gender && $tag && $entry_date) {
        if (tagExiste($pdo, $tag)) {
            $tag = getNextTag($pdo, $gender);
        }
        crearAnimal($pdo, [
            'tag' => $tag,
            'name' => $name,
            'breed_id' => $breed_id,
            'animal_type_id' => $animal_type_id,
            'birth_date' => $birth_date,
            'entry_date' => $entry_date,
            'weight_kg' => $weight_kg,
            'gender' => $gender,
            'status' => $status,
            'notes' => $notes,
            'facility_id' => $facility_id
        ]);
    }
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id             = (int) ($_POST['id'] ?? 0);
    $name           = trim($_POST['name'] ?? '');
    $animal_type_id = (int) ($_POST['animal_type_id'] ?? 0);
    $breed_id       = (int) ($_POST['breed_id'] ?? 0);
    $birth_date     = $_POST['birth_date'] ?? '';
    $weight_kg      = (float) ($_POST['weight_kg'] ?? 0);
    $gender         = $_POST['gender'] ?? '';
    $status         = $_POST['status'] ?? 'activo';
    $notes          = trim($_POST['notes'] ?? '');
    $tag            = trim($_POST['tag'] ?? '');
    $facility_id    = isset($_POST['facility_id']) && $_POST['facility_id'] !== '' ? (int) $_POST['facility_id'] : null;

    if ($id && $name && $animal_type_id && $breed_id && $birth_date && $weight_kg && $gender && $tag) {
        if (tagExiste($pdo, $tag, $id)) {
            header("Location: index.php?error=tag_duplicate");
            exit;
        }
        actualizarAnimal($pdo, [
            'id' => $id,
            'tag' => $tag,
            'name' => $name,
            'breed_id' => $breed_id,
            'animal_type_id' => $animal_type_id,
            'birth_date' => $birth_date,
            'weight_kg' => $weight_kg,
            'gender' => $gender,
            'status' => $status,
            'notes' => $notes,
            'facility_id' => $facility_id
        ]);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    eliminarAnimal($pdo, $id);
    header("Location: index.php");
    exit;
}

// ----------------------------------------------
// Determinar qué vista mostrar
// ----------------------------------------------
$view   = $_GET['view'] ?? null;
$editId = $_GET['edit'] ?? null;

$tiposAnimal = obtenerTiposAnimal($pdo);
$facilidades = obtenerFacilidades($pdo);

if ($view) {
    $detalle = obtenerAnimalPorId($pdo, (int)$view);
    if (!$detalle) {
        die("Registro no encontrado");
    }
    $edadDetalle = calcularEdad($detalle['birth_date'] ?? null);
    require __DIR__ . '/vista.php';
    exit;
}

if ($editId) {
    $animalEdit = obtenerAnimalSimplePorId($pdo, (int)$editId);
    if (!$animalEdit) {
        die("Animal no encontrado");
    }
    $razasEdit = obtenerRazasPorTipo($pdo, $animalEdit['animal_type_id']);
    require __DIR__ . '/vista.php';
    exit;
}

// Listado principal
$limit = 14;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$totalAnimals = contarAnimales($pdo);
$totalPages = ceil($totalAnimals / $limit);
$animales = obtenerAnimalesPaginados($pdo, $limit, $offset);

$statsPorTipo = obtenerEstadisticasPorTipo($pdo, $tiposAnimal);
$total = $totalAnimals;
$ultimoRegistro = obtenerUltimoRegistro($pdo);

$tiposAnimalJson = $tiposAnimal;

require __DIR__ . '/vista.php';