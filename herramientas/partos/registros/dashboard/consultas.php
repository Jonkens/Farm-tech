<?php
// herramientas/partos/registros/dashboard/consultas.php

/**
 * Obtiene la distribución de animales por tipo.
 */
function obtenerDistribucionPorTipo(PDO $pdo): array {
    $stmt = $pdo->query("
        SELECT at.name AS tipo, COUNT(a.id) AS cantidad
        FROM animales a
        JOIN tipos_animal at ON a.animal_type_id = at.id
        GROUP BY at.name
        ORDER BY at.name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtiene el número de partos agrupados por mes (últimos N meses).
 * Retorna un array con 'mes_label' (ej. '01/2025') y 'total'.
 */
function obtenerPartosUltimosMeses(PDO $pdo, int $meses = 6): array {
    $sql = "
        SELECT 
            TO_CHAR(fecha_parto, 'MM/YYYY') AS mes_label,
            COUNT(*) AS total
        FROM partos
        WHERE fecha_parto >= (CURRENT_DATE - INTERVAL '$meses months')
        GROUP BY mes_label
        ORDER BY MIN(fecha_parto) ASC
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}