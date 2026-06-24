<?php
// herramientas/partos/registros/registrar-parto/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../../../includes/query_helper.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

$pdo = getDB();

// Obtener datos necesarios para la vista
$partos       = obtenerPartos();           // del helper (sin $pdo)
$hembras      = obtenerHembras();          // del helper
$machos       = obtenerMachos();           // del helper
$tipos        = obtenerTiposAnimales();    // del helper
$razas        = $pdo->query("SELECT id, name FROM razas ORDER BY name")->fetchAll(PDO::FETCH_ASSOC); // consulta directa

// Mensajes de retroalimentación
$mensajeToast = '';
if (isset($_GET['exito'])) {
    $mensajeToast = '<div class="fixed top-5 right-5 z-50 animate-slide-in-right bg-green-100 border-l-4 border-green-600 text-green-800 rounded-xl shadow-lg p-4 max-w-sm backdrop-blur-sm" id="toast">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600 text-xl"></i>
            <span class="font-medium">✅ Parto registrado correctamente.</span>
            <button onclick="this.closest(\'#toast\').remove()" class="ml-auto text-green-600"><i class="fas fa-times"></i></button>
        </div>
    </div>';
}
if (isset($_GET['editado'])) {
    $mensajeToast = '<div class="fixed top-5 right-5 z-50 animate-slide-in-right bg-blue-100 border-l-4 border-blue-600 text-blue-800 rounded-xl shadow-lg p-4 max-w-sm backdrop-blur-sm" id="toast">
        <div class="flex items-center gap-3">
            <i class="fas fa-edit text-blue-600 text-xl"></i>
            <span class="font-medium">✏️ Parto editado correctamente.</span>
            <button onclick="this.closest(\'#toast\').remove()" class="ml-auto text-blue-600"><i class="fas fa-times"></i></button>
        </div>
    </div>';
}
if (isset($_GET['eliminado'])) {
    $mensajeToast = '<div class="fixed top-5 right-5 z-50 animate-slide-in-right bg-red-100 border-l-4 border-red-600 text-red-800 rounded-xl shadow-lg p-4 max-w-sm backdrop-blur-sm" id="toast">
        <div class="flex items-center gap-3">
            <i class="fas fa-trash-alt text-red-600 text-xl"></i>
            <span class="font-medium">🗑️ Parto eliminado correctamente.</span>
            <button onclick="this.closest(\'#toast\').remove()" class="ml-auto text-red-600"><i class="fas fa-times"></i></button>
        </div>
    </div>';
}
if (isset($_GET['error'])) {
    $mensajeToast = '<div class="fixed top-5 right-5 z-50 animate-slide-in-right bg-yellow-100 border-l-4 border-yellow-600 text-yellow-800 rounded-xl shadow-lg p-4 max-w-sm backdrop-blur-sm" id="toast">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
            <span class="font-medium">⚠️ Error al procesar la solicitud.</span>
            <button onclick="this.closest(\'#toast\').remove()" class="ml-auto text-yellow-600"><i class="fas fa-times"></i></button>
        </div>
    </div>';
}

// Incluir la vista
require __DIR__ . '/vista.php';