<?php
/**
 * Consultas para el módulo de compra/venta y finanzas.
 * Base de datos: ganaderia2 (PostgreSQL)
 */

require_once __DIR__ . '/../../includes/query_helper.php';

// ========================
// ANUNCIOS DE ANIMALES (anuncios_animales)
// ========================
function obtenerAnunciosActivos(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT aa.id, aa.ad_type, aa.quantity, aa.weight_kg, aa.price_per_unit,
                ta.name AS animal_type, r.name AS breed, u.username AS usuario
         FROM anuncios_animales aa
         JOIN tipos_animal ta ON aa.animal_type_id = ta.id
         LEFT JOIN razas r ON aa.breed_id = r.id
         JOIN usuarios u ON aa.user_id = u.id
         WHERE aa.status = 'activo'
         ORDER BY aa.created_at DESC"
    );
}

// ========================
// TRANSACCIONES (transacciones)
// ========================
function obtenerTransaccionesRecientes(PDO $pdo, int $limite = 20): array
{
    return ejecutarConsulta($pdo,
        "SELECT t.id, t.transaction_date, t.quantity, t.total_amount,
                ta.name AS animal_type, s.username AS seller, b.username AS buyer
         FROM transacciones t
         JOIN tipos_animal ta ON t.animal_type_id = ta.id
         JOIN usuarios s ON t.seller_id = s.id
         JOIN usuarios b ON t.buyer_id = b.id
         ORDER BY t.transaction_date DESC
         LIMIT :limite",
        [':limite' => $limite]
    );
}

// ========================
// ÓRDENES DE COMPRA DE INSUMOS (ordenes_compra)
// ========================
function obtenerOrdenesCompra(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT po.id, po.order_date, po.status, po.total_amount, s.name AS supplier
         FROM ordenes_compra po
         JOIN proveedores s ON po.supplier_id = s.id
         ORDER BY po.order_date DESC"
    );
}

// ========================
// EMPLEADOS Y NÓMINA (empleados, nomina)
// ========================
function obtenerEmpleados(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT id, name, role, monthly_salary FROM empleados ORDER BY name"
    );
}

function obtenerUltimaNomina(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT p.id, e.name, e.role, p.gross_salary, p.deductions, p.net_pay, p.payment_date
         FROM nomina p
         JOIN empleados e ON p.employee_id = e.id
         WHERE p.period = (SELECT MAX(period) FROM nomina)
         ORDER BY e.name"
    );
}

// ========================
// FINANZAS (entradas_financieras)
// ========================
function obtenerIngresosPeriodo(PDO $pdo, string $inicio, string $fin): float
{
    $result = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(amount), 0) AS total
         FROM entradas_financieras
         WHERE type = 'I' AND entry_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float)($result[0]['total'] ?? 0);
}

function obtenerGastosPeriodo(PDO $pdo, string $inicio, string $fin): float
{
    $result = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(amount), 0) AS total
         FROM entradas_financieras
         WHERE type = 'G' AND entry_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float)($result[0]['total'] ?? 0);
}