<?php
/**
 * Consultas para estimación de carne.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

/**
 * Obtiene vacas sacrificadas para el selector.
 * Devuelve: id, name, tag (como code), breed, age (meses), weight_kg (como weight)
 */
function obtenerVacasSacrificadas(PDO $pdo): array
{
    $sql = "SELECT 
                a.id,
                a.name,
                a.tag AS code,
                r.name AS breed,
                EXTRACT(YEAR FROM AGE(CURRENT_DATE, a.birth_date)) * 12 + EXTRACT(MONTH FROM AGE(CURRENT_DATE, a.birth_date)) AS age,
                a.weight_kg AS weight
            FROM animales a
            LEFT JOIN razas r ON a.breed_id = r.id
            WHERE a.status = 'sacrificado'
            ORDER BY a.name NULLS LAST, a.tag";
    
    $rows = ejecutarConsulta($pdo, $sql);
    
    foreach ($rows as &$row) {
        $row['age'] = (int) round($row['age']);
        $row['weight'] = (float) $row['weight'];
    }
    
    return $rows;
}