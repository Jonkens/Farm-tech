<?php
/**
 * Consultas para comparativa semanal.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

function obtenerUltimaSemanaConDatos(PDO $pdo): string
{
    $fechas = [];

    $result = ejecutarConsulta($pdo, "SELECT MAX(production_date) as max_fecha FROM produccion_leche");
    if (!empty($result) && $result[0]['max_fecha']) $fechas[] = $result[0]['max_fecha'];

    $result = ejecutarConsulta($pdo, "SELECT MAX(slaughter_date) as max_fecha FROM registros_sacrificio");
    if (!empty($result) && $result[0]['max_fecha']) $fechas[] = $result[0]['max_fecha'];

    $result = ejecutarConsulta($pdo, "SELECT MAX(production_date) as max_fecha FROM produccion_huevos");
    if (!empty($result) && $result[0]['max_fecha']) $fechas[] = $result[0]['max_fecha'];

    $ultimaFecha = empty($fechas) ? date('Y-m-d') : max($fechas);
    $diaSemana = (int)date('w', strtotime($ultimaFecha));
    return date('Y-m-d', strtotime("-$diaSemana days", strtotime($ultimaFecha)));
}

function obtenerTotalesSemanales(PDO $pdo, string $tabla, string $campoFecha, string $campoValor, string $inicioSemana): array
{
    $finSemana = date('Y-m-d', strtotime($inicioSemana . ' +6 days'));
    $sql = "SELECT $campoFecha as fecha, SUM($campoValor) as total
            FROM $tabla
            WHERE $campoFecha BETWEEN :inicio AND :fin
            GROUP BY $campoFecha";
    $rows = ejecutarConsulta($pdo, $sql, [':inicio' => $inicioSemana, ':fin' => $finSemana]);

    $totalesPorDia = array_fill(0, 7, 0);
    $totalSemana = 0;
    foreach ($rows as $row) {
        $diaSemana = (int)date('w', strtotime($row['fecha'])); // 0=Domingo
        $totalesPorDia[$diaSemana] = (float)$row['total'];
        $totalSemana += (float)$row['total'];
    }
    return ['diario' => $totalesPorDia, 'total' => $totalSemana];
}

function obtenerGallinasActivas(PDO $pdo, string $fechaLimite): int
{
    $result = ejecutarConsulta($pdo,
        "SELECT quantity FROM inventario_pollos
         WHERE inventory_date <= :fin
         ORDER BY inventory_date DESC LIMIT 1",
        [':fin' => $fechaLimite]
    );
    return empty($result) ? 0 : (int)$result[0]['quantity'];
}

function obtenerDetalleSacrificios(PDO $pdo, string $inicio, string $fin): array
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