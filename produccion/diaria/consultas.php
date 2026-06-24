<?php
/**
 * Consultas a la base de datos para el panel de producción diaria.
 * Base de datos: ganaderia2 (PostgreSQL)
 *
 * Todas las funciones reciben PDO y devuelven arrays.
 */

require_once __DIR__ . '/../../includes/query_helper.php';

function obtenerVacasProduciendo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.id, a.tag, a.name, b.name AS breed
         FROM animales a
         JOIN razas b ON a.breed_id = b.id
         WHERE a.status = 'produciendo' AND a.gender = 'F'
         ORDER BY a.name"
    );
}

function obtenerSacrificiosHoy(PDO $pdo, string $hoy): array
{
    return ejecutarConsulta($pdo,
        "SELECT ta.name AS animal_type, sr.quantity
         FROM registros_sacrificio sr
         JOIN tipos_animal ta ON sr.animal_type_id = ta.id
         WHERE sr.slaughter_date = :hoy",
        [':hoy' => $hoy]
    );
}

function obtenerSacrificiosSemana(PDO $pdo, string $inicio, string $fin): array
{
    return ejecutarConsulta($pdo,
        "SELECT ta.name AS animal_type, SUM(sr.quantity) AS total
         FROM registros_sacrificio sr
         JOIN tipos_animal ta ON sr.animal_type_id = ta.id
         WHERE sr.slaughter_date BETWEEN :inicio AND :fin
         GROUP BY ta.name",
        [':inicio' => $inicio, ':fin' => $fin]
    );
}

function obtenerVacasSacrificadasRecientes(PDO $pdo, int $limite = 5): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.tag, a.name, b.name AS breed
         FROM animales a
         JOIN razas b ON a.breed_id = b.id
         WHERE a.status = 'sacrificado'
         ORDER BY a.id DESC
         LIMIT :limite",
        [':limite' => $limite]
    );
}

function obtenerTotalGallinas(PDO $pdo): int
{
    $result = ejecutarConsulta($pdo,
        "SELECT quantity
         FROM inventario_pollos
         ORDER BY inventory_date DESC
         LIMIT 1"
    );
    return empty($result) ? 0 : (int) $result[0]['quantity'];
}

function obtenerTotalLecheSemana(PDO $pdo, string $inicio, string $fin): float
{
    $result = ejecutarConsulta($pdo,
        "SELECT SUM(quantity_liters) AS total
         FROM produccion_leche
         WHERE production_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (float) ($result[0]['total'] ?? 0);
}

function obtenerTotalHuevosSemana(PDO $pdo, string $inicio, string $fin): int
{
    $result = ejecutarConsulta($pdo,
        "SELECT SUM(quantity) AS total
         FROM produccion_huevos
         WHERE production_date BETWEEN :inicio AND :fin",
        [':inicio' => $inicio, ':fin' => $fin]
    );
    return (int) ($result[0]['total'] ?? 0);
}

function obtenerLechePorFecha(PDO $pdo, string $inicio, string $fin): array
{
    return obtenerDatosPorFecha($pdo, 'produccion_leche', 'production_date', 'quantity_liters', $inicio, $fin);
}

function obtenerCarnePorFecha(PDO $pdo, string $inicio, string $fin): array
{
    return obtenerDatosPorFecha($pdo, 'registros_sacrificio', 'slaughter_date', 'quantity', $inicio, $fin);
}

function obtenerHuevosPorFecha(PDO $pdo, string $inicio, string $fin): array
{
    return obtenerDatosPorFecha($pdo, 'produccion_huevos', 'production_date', 'quantity', $inicio, $fin);
}