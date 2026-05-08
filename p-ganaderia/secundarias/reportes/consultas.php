<?php
require_once __DIR__ . '/../../includes/query_helper.php';
// Reportes generales del sistema
// --- Producción de Leche ---
function obtenerProduccionLeche(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT SUM(quantity_liters) AS total FROM milk_production WHERE production_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

function obtenerProduccionLecheDetalle(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.name AS animal, SUM(m.quantity_liters) AS litros
         FROM milk_production m JOIN animals a ON m.animal_id = a.id
         WHERE m.production_date BETWEEN :inicio AND :fin
         GROUP BY a.name ORDER BY litros DESC",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

// --- Sacrificios ---
function obtenerSacrificios(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT at.name AS tipo, SUM(sr.quantity) AS cantidad,
                SUM(sr.quantity) * CASE at.name
                    WHEN 'Bovino' THEN 250 WHEN 'Porcino' THEN 80
                    WHEN 'Ovino' THEN 25 WHEN 'Caprino' THEN 20 ELSE 0 END AS kg_estimados
         FROM slaughter_records sr
         JOIN animal_types at ON sr.animal_type_id = at.id
         WHERE sr.slaughter_date BETWEEN :inicio AND :fin
         GROUP BY at.name",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

// --- Huevos ---
function obtenerProduccionHuevos(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT SUM(quantity) AS total FROM egg_production WHERE production_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

// --- Distribución del Hato ---
function obtenerDistribucionRaza(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT b.name AS breed, COUNT(*) AS total
         FROM animals a JOIN breeds b ON a.breed_id = b.id
         GROUP BY b.name ORDER BY total DESC"
    );
}

function obtenerDistribucionSexo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT gender, COUNT(*) AS total FROM animals GROUP BY gender"
    );
}

function obtenerDistribucionEspecie(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT at.name AS tipo, COUNT(*) AS total
         FROM animals a JOIN animal_types at ON a.animal_type_id = at.id
         GROUP BY at.name ORDER BY total DESC"
    );
}

function obtenerDistribucionEstado(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT status, COUNT(*) AS total FROM animals GROUP BY status ORDER BY total DESC"
    );
}

function obtenerDistribucionEstablo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT f.name AS facility, COUNT(*) AS total
         FROM animals a JOIN facilities f ON a.facility_id = f.id
         GROUP BY f.name ORDER BY total DESC"
    );
}

function obtenerCrecimiento(PDO $pdo, int $limite = 20): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.name, a.weight_kg, b.name AS breed
         FROM animals a JOIN breeds b ON a.breed_id = b.id
         WHERE a.weight_kg IS NOT NULL
         ORDER BY a.weight_kg DESC
         LIMIT :limite",
        [':limite' => $limite]
    );
}

// --- Resumen Financiero ---
function obtenerIngresosPeriodo(PDO $pdo, string $inicio, string $fin): float
{
    $res = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(amount),0) AS total FROM financial_entries WHERE type='I' AND entry_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float)$res[0]['total'];
}

function obtenerGastosPeriodo(PDO $pdo, string $inicio, string $fin): float
{
    $res = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(amount),0) AS total FROM financial_entries WHERE type='G' AND entry_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float)$res[0]['total'];
}

function obtenerTransacciones(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT t.transaction_date, t.quantity, t.total_amount,
                at.name AS tipo, s.username AS seller, b.username AS buyer
         FROM transactions t
         JOIN animal_types at ON t.animal_type_id = at.id
         JOIN users s ON t.seller_id = s.id
         JOIN users b ON t.buyer_id = b.id
         WHERE t.transaction_date BETWEEN :inicio AND :fin
         ORDER BY t.transaction_date DESC LIMIT 20",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

function obtenerNomina(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT e.name, e.role, p.net_pay, p.payment_date
         FROM payroll p JOIN employees e ON p.employee_id = e.id
         WHERE p.period = (SELECT MAX(period) FROM payroll)"
    );
}

function obtenerAnunciosActivos(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT aa.ad_type, aa.quantity, aa.price_per_unit, at.name AS tipo, u.username
         FROM animal_ads aa
         JOIN animal_types at ON aa.animal_type_id = at.id
         JOIN users u ON aa.user_id = u.id
         WHERE aa.status = 'activo' LIMIT 20"
    );
}

// --- Control de Alimentación ---
function obtenerAlimentacion(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT f.feeding_date, a.name AS animal, fc.name AS alimento, f.quantity_kg
         FROM feeding f
         JOIN animals a ON f.animal_id = a.id
         JOIN food_catalog fc ON f.food_id = fc.id
         WHERE f.feeding_date BETWEEN :inicio AND :fin
         ORDER BY f.feeding_date DESC LIMIT 30",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

function obtenerCatalogoAlimentos(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT name, food_type, cost_per_kg, protein_pct, stock_kg FROM food_catalog"
    );
}

function obtenerEficienciaNutricional(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT ne.measurement_date, a.name AS animal, ne.feed_conversion_ratio, ne.weight_gain_kg
         FROM nutritional_efficiency ne JOIN animals a ON ne.animal_id = a.id
         ORDER BY ne.measurement_date DESC LIMIT 20"
    );
}

// --- NUEVO: Historial de Salud ---
function obtenerHistorialSalud(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT h.event_date, h.event_type, h.product_used, h.dosage, a.name AS animal_name
         FROM health_events h
         LEFT JOIN animals a ON h.animal_id = a.id
         ORDER BY h.event_date DESC LIMIT 20"
    );
}

// --- NUEVO: Ventas de Productos (resumen por tipo) ---
function obtenerResumenVentas(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT product_type, SUM(quantity) AS cantidad, SUM(total_amount) AS total
         FROM sales
         WHERE sale_date BETWEEN :inicio AND :fin
         GROUP BY product_type",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

// --- NUEVO: Órdenes de Compra ---
function obtenerOrdenesCompra(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT po.order_date, po.total_amount, po.status, s.name AS supplier
         FROM purchase_orders po
         JOIN suppliers s ON po.supplier_id = s.id
         ORDER BY po.order_date DESC LIMIT 10"
    );
}