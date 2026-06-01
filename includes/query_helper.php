<?php
/**
 * Funciones auxiliares para ejecución segura de consultas PDO.
 * Uso: require_once __DIR__ . '/../../includes/query_helper.php';
 */

function ejecutarConsulta(PDO $pdo, string $sql, array $params = []): array {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/**
 * Obtiene datos agrupados por fecha para gráficas.
 * @return array [fecha => total]
 */
function obtenerDatosPorFecha(PDO $pdo, string $tabla, string $campoFecha, string $campoValor, string $inicio, string $fin): array
{
    $sql = "SELECT $campoFecha as fecha, SUM($campoValor) as total
            FROM $tabla
            WHERE $campoFecha BETWEEN :inicio AND :fin
            GROUP BY $campoFecha";
    $rows = ejecutarConsulta($pdo, $sql, [':inicio' => $inicio, ':fin' => $fin]);
    $datos = [];
    foreach ($rows as $row) {
        $datos[$row['fecha']] = (float) $row['total'];
    }
    return $datos;
}