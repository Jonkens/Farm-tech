
<?php
session_start();
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/consultas.php';
require_once __DIR__ . '/calculos.php';

function redirectWithMessage(string $message, string $type, string $tab = 'vacas', int $page = 1): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
    header("Location: index.php?tab=$tab&page_$tab=$page");
    exit;
}
function getCurrentPage(string $tab) { $key = "page_$tab"; return isset($_GET[$key]) ? max(1, (int)$_GET[$key]) : 1; }

$tab = $_GET['tab'] ?? 'vacas';
$porPagina = 7;

$pages = [];
$tabsList = ['vacas','leche','sacrificios','huevos','gallinas','anuncios','transacciones','ordenes','empleados','alimentacion','catalogo','eficiencia'];
foreach ($tabsList as $t) { $pages[$t] = getCurrentPage($t); }

// ────────────────────────────────────
// PROCESAR ACCIONES POST / GET
// ────────────────────────────────────

// Animales
if (isset($_POST['agregar_vaca'])) {
    $resultado = insertarAnimal(
        $pdo,
        $_POST['tag'],
        $_POST['name'],
        (int)$_POST['breed_id'],
        (int)$_POST['animal_type_id'],
        (float)$_POST['weight'],
        $_POST['status'],
        $_POST['gender']
    );
    if ($resultado) {
        redirectWithMessage("Animal agregado correctamente", "success", "vacas", $pages['vacas']);
    } else {
        redirectWithMessage("Error al registrar el animal", "error", "vacas", $pages['vacas']);
    }
}

if (isset($_POST['actualizar_vaca'])) {
    actualizarAnimal($pdo, (int)$_POST['id'], $_POST['tag'], $_POST['name'], (int)$_POST['breed_id'], (float)$_POST['weight'], $_POST['status'], $_POST['gender']);
    redirectWithMessage("Animal actualizado", "success", "vacas", $pages['vacas']);
}
if (isset($_GET['eliminar_vaca'])) {
    eliminarAnimal($pdo, (int)$_GET['id']);
    redirectWithMessage("Animal eliminado", "success", "vacas", $pages['vacas']);
}

// Leche
if (isset($_POST['agregar_leche'])) {
    insertarLeche($pdo, (int)$_POST['animal_id'], $_POST['production_date'], (float)$_POST['quantity_liters']);
    redirectWithMessage("Leche agregada", "success", "leche", $pages['leche']);
}
if (isset($_POST['actualizar_leche'])) {
    actualizarLeche($pdo, (int)$_POST['id'], (int)$_POST['animal_id'], $_POST['production_date'], (float)$_POST['quantity_liters']);
    redirectWithMessage("Leche actualizada", "success", "leche", $pages['leche']);
}
if (isset($_GET['eliminar_leche'])) {
    eliminarLeche($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro eliminado", "success", "leche", $pages['leche']);
}

// Sacrificios
if (isset($_POST['registrar_sacrificio'])) {
    registrarSacrificio($pdo, (int)$_POST['animal_id'], (int)$_POST['animal_type_id']);
    redirectWithMessage("Sacrificio registrado", "success", "sacrificios", $pages['sacrificios']);
}

// Huevos
if (isset($_POST['agregar_huevos'])) {
    insertarHuevos($pdo, $_POST['production_date'], (int)$_POST['quantity']);
    redirectWithMessage("Huevos agregados", "success", "huevos", $pages['huevos']);
}
if (isset($_POST['actualizar_huevos'])) {
    actualizarHuevos($pdo, (int)$_POST['id'], $_POST['production_date'], (int)$_POST['quantity']);
    redirectWithMessage("Huevos actualizados", "success", "huevos", $pages['huevos']);
}
if (isset($_GET['eliminar_huevos'])) {
    eliminarHuevos($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro eliminado", "success", "huevos", $pages['huevos']);
}

// Gallinas
if (isset($_POST['agregar_gallinas'])) {
    insertarGallinas($pdo, $_POST['inventory_date'], (int)$_POST['quantity']);
    redirectWithMessage("Inventario agregado", "success", "gallinas", $pages['gallinas']);
}
if (isset($_POST['actualizar_gallinas'])) {
    actualizarGallinas($pdo, (int)$_POST['id'], $_POST['inventory_date'], (int)$_POST['quantity']);
    redirectWithMessage("Inventario actualizado", "success", "gallinas", $pages['gallinas']);
}
if (isset($_GET['eliminar_gallinas'])) {
    eliminarGallinas($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro eliminado", "success", "gallinas", $pages['gallinas']);
}

// Anuncios (solo eliminar)
if (isset($_GET['eliminar_anuncio'])) {
    eliminarAnuncio($pdo, (int)$_GET['id']);
    redirectWithMessage("Anuncio eliminado", "success", "anuncios", $pages['anuncios']);
}

// Transacciones (solo eliminar)
if (isset($_GET['eliminar_transaccion'])) {
    eliminarTransaccion($pdo, (int)$_GET['id']);
    redirectWithMessage("Transacción eliminada", "success", "transacciones", $pages['transacciones']);
}

// Órdenes de compra
if (isset($_POST['agregar_orden'])) {
    insertarOrden($pdo, (int)$_POST['supplier_id'], $_POST['order_date'], $_POST['expected_delivery'], (float)$_POST['total_amount']);
    redirectWithMessage("Orden agregada", "success", "ordenes", $pages['ordenes']);
}
if (isset($_GET['eliminar_orden'])) {
    eliminarOrden($pdo, (int)$_GET['id']);
    redirectWithMessage("Orden eliminada", "success", "ordenes", $pages['ordenes']);
}

// Empleados
if (isset($_POST['agregar_empleado'])) {
    insertarEmpleado($pdo, $_POST['name'], $_POST['role'], (float)$_POST['monthly_salary']);
    redirectWithMessage("Empleado agregado", "success", "empleados", $pages['empleados']);
}
if (isset($_POST['actualizar_empleado'])) {
    actualizarEmpleado($pdo, (int)$_POST['id'], $_POST['name'], $_POST['role'], (float)$_POST['monthly_salary']);
    redirectWithMessage("Empleado actualizado", "success", "empleados", $pages['empleados']);
}
if (isset($_GET['eliminar_empleado'])) {
    eliminarEmpleado($pdo, (int)$_GET['id']);
    redirectWithMessage("Empleado eliminado", "success", "empleados", $pages['empleados']);
}

// Alimentación
if (isset($_POST['agregar_alimentacion'])) {
    insertarAlimentacion($pdo, (int)$_POST['animal_id'], (int)$_POST['food_id'], $_POST['feeding_date'], (float)$_POST['quantity_kg']);
    redirectWithMessage("Registro agregado", "success", "alimentacion", $pages['alimentacion']);
}
if (isset($_POST['actualizar_alimentacion'])) {
    actualizarAlimentacion($pdo, (int)$_POST['id'], (int)$_POST['animal_id'], (int)$_POST['food_id'], $_POST['feeding_date'], (float)$_POST['quantity_kg']);
    redirectWithMessage("Registro actualizado", "success", "alimentacion", $pages['alimentacion']);
}
if (isset($_GET['eliminar_alimentacion'])) {
    eliminarAlimentacion($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro eliminado", "success", "alimentacion", $pages['alimentacion']);
}

// Catálogo de alimentos
if (isset($_POST['agregar_alimento'])) {
    insertarAlimento($pdo, $_POST['name'], $_POST['food_type'], (float)$_POST['cost_per_kg'], (float)$_POST['protein_pct'], (float)$_POST['stock_kg']);
    redirectWithMessage("Alimento agregado", "success", "catalogo", $pages['catalogo']);
}
if (isset($_POST['actualizar_alimento'])) {
    actualizarAlimento($pdo, (int)$_POST['id'], $_POST['name'], $_POST['food_type'], (float)$_POST['cost_per_kg'], (float)$_POST['protein_pct'], (float)$_POST['stock_kg']);
    redirectWithMessage("Alimento actualizado", "success", "catalogo", $pages['catalogo']);
}
if (isset($_GET['eliminar_alimento'])) {
    eliminarAlimento($pdo, (int)$_GET['id']);
    redirectWithMessage("Alimento eliminado", "success", "catalogo", $pages['catalogo']);
}

// Eficiencia nutricional
if (isset($_GET['eliminar_eficiencia'])) {
    eliminarEficiencia($pdo, (int)$_GET['id']);
    redirectWithMessage("Registro eliminado", "success", "eficiencia", $pages['eficiencia']);
}

// ────────────────────────────────────
// OBTENER DATOS PARA CADA PESTAÑA
// ────────────────────────────────────
$data = [];
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
$data['flash'] = $flash;
$data['tab'] = $tab;
$data['pages'] = $pages;

$estadosAnimal = ['activo'=>'Activo', 'produciendo'=>'Produciendo', 'reproduciendo'=>'Reproduciendo', 'enfermo'=>'Enfermo', 'sacrificado'=>'Sacrificado', 'vendido'=>'Vendido', 'muerto'=>'Muerto'];
$generos = ['F'=>'Hembra', 'M'=>'Macho'];  // Nota: género 'F' para hembra según esquema
$data['razas'] = obtenerRazas($pdo);
$tiposAnimales = obtenerTiposAnimales($pdo);
$data['tiposAnimal'] = $tiposAnimales;  // para usar en la vista
$data['proveedores'] = obtenerProveedores($pdo);
$data['animalesSelect'] = ejecutarConsulta($pdo, 
    "SELECT id, name, animal_type_id FROM animales WHERE status != 'sacrificado' ORDER BY name"
);
$data['alimentosSelect'] = obtenerAlimentos($pdo);

switch ($tab) {
    case 'vacas':
        $data['total'] = contarAnimales($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerAnimalesPaginados($pdo, $porPagina, ($pages['vacas']-1)*$porPagina);
        $data['existingTags'] = obtenerTodosLosTags($pdo);
        break;
    case 'leche':
        $data['total'] = contarLeche($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerLechePaginado($pdo, $porPagina, ($pages['leche']-1)*$porPagina);
        break;
    case 'sacrificios':
        $data['historial'] = obtenerSacrificiosRecientes($pdo, 10);
        $data['disponibles'] = ejecutarConsulta($pdo,
            "SELECT a.id, a.tag, a.name, r.name AS breed, a.weight_kg 
             FROM animales a 
             JOIN razas r ON a.breed_id = r.id 
             WHERE a.status = 'sacrificado' 
             ORDER BY a.name"
        );
        break;
    case 'huevos':
        $data['total'] = contarHuevos($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerHuevosPaginado($pdo, $porPagina, ($pages['huevos']-1)*$porPagina);
        break;
    case 'gallinas':
        $data['total'] = contarGallinas($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerGallinasPaginado($pdo, $porPagina, ($pages['gallinas']-1)*$porPagina);
        break;
    case 'anuncios':
        $data['total'] = contarAnuncios($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerAnunciosPaginado($pdo, $porPagina, ($pages['anuncios']-1)*$porPagina);
        break;
    case 'transacciones':
        $data['total'] = contarTransacciones($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerTransaccionesPaginado($pdo, $porPagina, ($pages['transacciones']-1)*$porPagina);
        break;
    case 'ordenes':
        $data['total'] = contarOrdenes($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerOrdenesPaginado($pdo, $porPagina, ($pages['ordenes']-1)*$porPagina);
        break;
    case 'empleados':
        $data['empleados'] = obtenerEmpleados($pdo);
        $data['nomina'] = obtenerUltimaNomina($pdo);
        break;
    case 'alimentacion':
        $data['total'] = contarAlimentacion($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerAlimentacionPaginado($pdo, $porPagina, ($pages['alimentacion']-1)*$porPagina);
        break;
    case 'catalogo':
        $data['registros'] = obtenerCatalogoAlimentos($pdo);
        break;
    case 'eficiencia':
        $data['total'] = contarEficiencia($pdo);
        $data['totalPaginas'] = ceil($data['total'] / $porPagina);
        $data['registros'] = obtenerEficienciaPaginado($pdo, $porPagina, ($pages['eficiencia']-1)*$porPagina);
        break;
}
$data['estadosAnimal'] = $estadosAnimal;
$data['generos'] = $generos;

require __DIR__ . '/vista.php';
/**
 * archivo dentro de la carpeta de 
 * 
 * administrativas/t_seccion_ADMIN
 */