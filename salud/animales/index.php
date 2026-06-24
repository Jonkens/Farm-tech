<?php
/**
 * Controlador de Salud del Ganado.
 * Maneja peticiones POST, AJAX y carga la vista.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

// ---------- MANEJO DE PETICIONES AJAX ----------
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');

    if ($_GET['ajax'] === 'get_datos_animal' && isset($_GET['id'])) {
        $datos = obtenerDatosAlimentacionAnimal($pdo, (int)$_GET['id']);
        echo json_encode($datos);
        exit;
    }

    if ($_GET['ajax'] === 'get_historial' && isset($_GET['id'])) {
        $historial = obtenerHistorialSalud($pdo, (int)$_GET['id']);
        echo json_encode($historial);
        exit;
    }

    echo json_encode(['error' => 'Solicitud inválida']);
    exit;
}

// ---------- PROCESAR FORMULARIO DE NUEVO CHEQUEO ----------
$mensajeError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_chequeo'])) {
    $animalId = intval($_POST['animal_id']);
    $alimentacion = trim($_POST['alimentacion'] ?? '');
    $consumoAgua = trim($_POST['agua'] ?? '');
    $estadoSalud = trim($_POST['estado'] ?? '');
    $enfermedad = !empty($_POST['enfermedad']) ? trim($_POST['enfermedad']) : null;
    $vacuna = !empty($_POST['vacuna']) ? trim($_POST['vacuna']) : null;
    $tratamiento = !empty($_POST['tratamiento']) ? trim($_POST['tratamiento']) : null;
    $observaciones = !empty($_POST['obs']) ? trim($_POST['obs']) : null;

    if ($animalId <= 0 || empty($estadoSalud)) {
        $mensajeError = "❌ Debe seleccionar un animal y el estado de salud es obligatorio.";
    } else {
        $resultado = guardarChequeo($pdo, $animalId, $alimentacion, $consumoAgua,
                                    $estadoSalud, $enfermedad, $vacuna, $tratamiento, $observaciones);
        if ($resultado['success']) {
            $mensaje = urlencode($resultado['message']);
            header("Location: index.php?mensaje=$mensaje");
            exit;
        } else {
            $mensajeError = $resultado['message'];
        }
    }
}

// ---------- OBTENER DATOS PARA LA VISTA ----------
$animalesLista = obtenerAnimalesActivos($pdo);
$ultimosChequeos = obtenerUltimosChequeos($pdo);

// Manejo de mensaje de éxito por GET
$mensajeExito = null;
if (isset($_GET['mensaje'])) {
    $mensajeExito = htmlspecialchars($_GET['mensaje']);
}

// Pasar variables a la vista
$datosVista = [
    'animalesLista'   => $animalesLista,
    'ultimosChequeos' => $ultimosChequeos,
    'mensajeExito'    => $mensajeExito,
    'mensajeError'    => $mensajeError
];

require __DIR__ . '/vista.php';