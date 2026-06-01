<?php
// Compra, Venta y Finanzas
/**
 * Consultas para el módulo de compra/venta y finanzas.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

// ========================
// ANUNCIOS DE ANIMALES
// ========================
function obtenerAnunciosActivos(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT aa.id, aa.ad_type, aa.quantity, aa.weight_kg, aa.price_per_unit,
                at.name AS animal_type, b.name AS breed, u.username AS usuario
         FROM animal_ads aa
         JOIN animal_types at ON aa.animal_type_id = at.id
         LEFT JOIN breeds b ON aa.breed_id = b.id
         JOIN users u ON aa.user_id = u.id
         WHERE aa.status = 'activo'
         ORDER BY aa.created_at DESC"
    );
}

// ========================
// TRANSACCIONES (COMPRA/VENTA)
// ========================
function obtenerTransaccionesRecientes(PDO $pdo, int $limite = 20): array
{
    return ejecutarConsulta($pdo,
        "SELECT t.id, t.transaction_date, t.quantity, t.total_amount,
                at.name AS animal_type, s.username AS seller, b.username AS buyer
         FROM transactions t
         JOIN animal_types at ON t.animal_type_id = at.id
         JOIN users s ON t.seller_id = s.id
         JOIN users b ON t.buyer_id = b.id
         ORDER BY t.transaction_date DESC
         LIMIT :limite",
        [':limite' => $limite]
    );
}

// ========================
// ÓRDENES DE COMPRA DE INSUMOS
// ========================
function obtenerOrdenesCompra(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT po.id, po.order_date, po.status, po.total_amount, s.name AS supplier
         FROM purchase_orders po
         JOIN suppliers s ON po.supplier_id = s.id
         ORDER BY po.order_date DESC"
    );
}

// ========================
// EMPLEADOS Y NÓMINA
// ========================
function obtenerEmpleados(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT id, name, role, monthly_salary FROM employees ORDER BY name"
    );
}

function obtenerUltimaNomina(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT p.id, e.name, e.role, p.gross_salary, p.deductions, p.net_pay, p.payment_date
         FROM payroll p
         JOIN employees e ON p.employee_id = e.id
         WHERE p.period = (SELECT MAX(period) FROM payroll)
         ORDER BY e.name"
    );
}

// ========================
// FINANZAS (RESUMEN)
// ========================
function obtenerIngresosPeriodo(PDO $pdo, string $inicio, string $fin): float
{
    $result = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(amount), 0) AS total
         FROM financial_entries
         WHERE type = 'I' AND entry_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float)($result[0]['total'] ?? 0);
}

function obtenerGastosPeriodo(PDO $pdo, string $inicio, string $fin): float
{
    $result = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(amount), 0) AS total
         FROM financial_entries
         WHERE type = 'G' AND entry_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float)($result[0]['total'] ?? 0);
}