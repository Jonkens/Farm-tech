<?php
/**
 * Consultas para control de alimentación y nutrición.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

// ========================
// REGISTROS DE ALIMENTACIÓN
// ========================
function obtenerAlimentacionReciente(PDO $pdo, int $limite = 50): array
{
    return ejecutarConsulta($pdo,
        "SELECT f.id, f.feeding_date, f.quantity_kg,
                a.name AS animal_name, a.tag,
                b.name AS breed,
                fc.name AS food_name
         FROM feeding f
         JOIN animals a ON f.animal_id = a.id
         LEFT JOIN breeds b ON a.breed_id = b.id
         JOIN food_catalog fc ON f.food_id = fc.id
         ORDER BY f.feeding_date DESC, a.name
         LIMIT :limite",
        [':limite' => $limite]
    );
}

// ========================
// CATÁLOGO DE ALIMENTOS
// ========================
function obtenerCatalogoAlimentos(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT id, name, food_type, cost_per_kg, protein_pct, stock_kg
         FROM food_catalog
         ORDER BY name"
    );
}

// ========================
// EFICIENCIA NUTRICIONAL (histórico)
// ========================
function obtenerEficienciaNutricional(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT ne.id, ne.measurement_date, ne.feed_conversion_ratio, ne.weight_gain_kg,
                a.name AS animal_name, a.tag
         FROM nutritional_efficiency ne
         JOIN animals a ON ne.animal_id = a.id
         ORDER BY ne.measurement_date DESC"
    );
}

// ========================
// ANIMALES (para formularios futuros)
// ========================
function obtenerAnimalesParaSelect(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT id, name FROM animals ORDER BY name"
    );
}