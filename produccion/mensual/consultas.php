<?php
/**
 * Consultas para comparativa mensual.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

function obtenerUltimoAnioConDatos(PDO $pdo): int
{
    $anios = [];
    $result = ejecutarConsulta($pdo, "SELECT MAX(EXTRACT(YEAR FROM production_date)) as max_anio FROM produccion_leche");
    if (!empty($result) && $result[0]['max_anio']) $anios[] = (int)$result[0]['max_anio'];

    $result = ejecutarConsulta($pdo, "SELECT MAX(EXTRACT(YEAR FROM slaughter_date)) as max_anio FROM registros_sacrificio");
    if (!empty($result) && $result[0]['max_anio']) $anios[] = (int)$result[0]['max_anio'];

    $result = ejecutarConsulta($pdo, "SELECT MAX(EXTRACT(YEAR FROM production_date)) as max_anio FROM produccion_huevos");
    if (!empty($result) && $result[0]['max_anio']) $anios[] = (int)$result[0]['max_anio'];

    return empty($anios) ? (int)date('Y') : max($anios);
}

function obtenerAniosDisponibles(PDO $pdo, int $anioFijo): array
{
    $anios = [];
    $tablas = [
        'produccion_leche' => 'production_date',
        'registros_sacrificio' => 'slaughter_date',
        'produccion_huevos' => 'production_date'
    ];
    foreach ($tablas as $tabla => $campo) {
        $sql = "SELECT DISTINCT EXTRACT(YEAR FROM $campo) as anio FROM $tabla WHERE EXTRACT(YEAR FROM $campo) <= :anioFijo ORDER BY anio DESC";
        $rows = ejecutarConsulta($pdo, $sql, [':anioFijo' => $anioFijo]);
        foreach ($rows as $row) {
            $anios[] = (int)$row['anio'];
        }
    }
    $anios = array_unique($anios);
    rsort($anios);
    return $anios;
}

function obtenerTotalesMensuales(PDO $pdo, string $tabla, string $campoFecha, string $campoValor, int $anio): array
{
    $meses = array_fill(0, 12, 0);
    $sql = "SELECT EXTRACT(MONTH FROM $campoFecha) as mes, SUM($campoValor) as total
            FROM $tabla
            WHERE EXTRACT(YEAR FROM $campoFecha) = :anio
            GROUP BY mes";
    $rows = ejecutarConsulta($pdo, $sql, [':anio' => $anio]);
    $totalAnual = 0;
    foreach ($rows as $row) {
        $mes = (int)$row['mes'] - 1; // enero = 0
        $meses[$mes] = (float)$row['total'];
        $totalAnual += (float)$row['total'];
    }
    return ['mensual' => $meses, 'total' => $totalAnual];
}

function obtenerDesgloseSacrificios(PDO $pdo, int $anio): array
{
    $sql = "SELECT ta.name AS animal_type, SUM(sr.quantity) as total_cabezas
            FROM registros_sacrificio sr
            JOIN tipos_animal ta ON sr.animal_type_id = ta.id
            WHERE EXTRACT(YEAR FROM sr.slaughter_date) = :anio
            GROUP BY ta.name";
    $rows = ejecutarConsulta($pdo, $sql, [':anio' => $anio]);
    $desglose = [];
    $totalKg = 0;
    $pesos = ['Bovino' => 250, 'Porcino' => 80, 'Ovino' => 25, 'Caprino' => 20];
    foreach ($rows as $row) {
        $tipo = $row['animal_type'];
        $cabezas = (int)$row['total_cabezas'];
        $kg = $cabezas * ($pesos[$tipo] ?? 0);
        $desglose[] = ['tipo' => $tipo, 'cabezas' => $cabezas, 'kg' => $kg];
        $totalKg += $kg;
    }
    return ['desglose' => $desglose, 'total_kg' => $totalKg];
}

function obtenerPromedioGallinas(PDO $pdo, int $anio): int
{
    $sql = "SELECT ROUND(AVG(quantity)) as promedio
            FROM inventario_pollos
            WHERE EXTRACT(YEAR FROM inventory_date) = :anio";
    $rows = ejecutarConsulta($pdo, $sql, [':anio' => $anio]);
    return !empty($rows) ? (int)$rows[0]['promedio'] : 0;
}