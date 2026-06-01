<?php
/**
 * Consultas para estimación de carne.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

/**
 * Obtiene vacas sacrificadas para el selector.
 */
function obtenerVacasSacrificadas(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT id, name, code, breed, age, weight
         FROM animals
         WHERE status = 'sacrificada'
         ORDER BY name"
    );
}