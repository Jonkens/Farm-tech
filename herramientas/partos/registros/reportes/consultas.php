<?php
// herramientas/partos/registros/reportes/consultas.php

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

/**
 * Obtiene la cantidad de partos por tipo de animal (de la cría).
 */
function obtenerPartosPorTipoAnimal(PDO $pdo): array {
    $sql = "
        SELECT at.name AS tipo, COUNT(p.id) AS cantidad
        FROM partos p
        JOIN animales c ON p.cria_id = c.id
        JOIN tipos_animal at ON c.animal_type_id = at.id
        GROUP BY at.name
        ORDER BY cantidad DESC
    ";
    $stmt = $pdo->query($sql);
    $result = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $result[$row['tipo']] = (int) $row['cantidad'];
    }
    return $result;
}