<?php
/**
 * Consultas para control de alimentación y nutrición.
 * Base de datos: ganaderia2 (PostgreSQL)
 */

require_once __DIR__ . '/../../includes/query_helper.php';

// ========================
// REGISTROS DE ALIMENTACIÓN (incluye costo)
// ========================
function obtenerAlimentacionPaginado(PDO $pdo, int $limit, int $offset): array
{
    return ejecutarConsulta($pdo,
        "SELECT f.id, f.feeding_date, a.name AS animal_name, a.tag,
                fc.name AS food_name, f.quantity_kg,
                ROUND(f.quantity_kg * fc.cost_per_kg, 2) AS costo
         FROM alimentacion f
         JOIN animales a ON f.animal_id = a.id
         JOIN catalogo_alimentos fc ON f.food_id = fc.id
         ORDER BY f.feeding_date DESC, a.name
         LIMIT :limit OFFSET :offset",
        [':limit' => $limit, ':offset' => $offset]
    );
}

function contarAlimentacion(PDO $pdo): int
{
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM alimentacion");
    return (int)($r[0]['total'] ?? 0);
}

function obtenerAlimentos(PDO $pdo): array
{
    return ejecutarConsulta($pdo, "SELECT id, name FROM catalogo_alimentos ORDER BY name");
}

function obtenerAnimalesParaSelect(PDO $pdo): array
{
    return ejecutarConsulta($pdo, "SELECT id, name FROM animales ORDER BY name");
}

function insertarAlimentacion(PDO $pdo, int $animalId, int $foodId, string $fecha, float $kg): void
{
    ejecutarConsulta($pdo,
        "INSERT INTO alimentacion (animal_id, food_id, feeding_date, quantity_kg) VALUES (:a, :f, :d, :k)",
        [':a' => $animalId, ':f' => $foodId, ':d' => $fecha, ':k' => $kg]
    );
}

function actualizarAlimentacion(PDO $pdo, int $id, int $animalId, int $foodId, string $fecha, float $kg): void
{
    ejecutarConsulta($pdo,
        "UPDATE alimentacion SET animal_id=:a, food_id=:f, feeding_date=:d, quantity_kg=:k WHERE id=:id",
        [':id' => $id, ':a' => $animalId, ':f' => $foodId, ':d' => $fecha, ':k' => $kg]
    );
}

function eliminarAlimentacion(PDO $pdo, int $id): void
{
    ejecutarConsulta($pdo, "DELETE FROM alimentacion WHERE id=:id", [':id' => $id]);
}

// ========================
// CATÁLOGO DE ALIMENTOS
// ========================
function obtenerCatalogoAlimentos(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT id, name, food_type, cost_per_kg, protein_pct, stock_kg
         FROM catalogo_alimentos
         ORDER BY name"
    );
}

function insertarAlimento(PDO $pdo, string $name, string $type, float $cost, float $protein, float $stock): void
{
    ejecutarConsulta($pdo,
        "INSERT INTO catalogo_alimentos (name, food_type, cost_per_kg, protein_pct, stock_kg) VALUES (:n, :t, :c, :p, :s)",
        [':n' => $name, ':t' => $type, ':c' => $cost, ':p' => $protein, ':s' => $stock]
    );
}

function actualizarAlimento(PDO $pdo, int $id, string $name, string $type, float $cost, float $protein, float $stock): void
{
    ejecutarConsulta($pdo,
        "UPDATE catalogo_alimentos SET name=:n, food_type=:t, cost_per_kg=:c, protein_pct=:p, stock_kg=:s WHERE id=:id",
        [':id' => $id, ':n' => $name, ':t' => $type, ':c' => $cost, ':p' => $protein, ':s' => $stock]
    );
}

function eliminarAlimento(PDO $pdo, int $id): void
{
    ejecutarConsulta($pdo, "DELETE FROM catalogo_alimentos WHERE id=:id", [':id' => $id]);
}

// ========================
// EFICIENCIA NUTRICIONAL
// ========================
function obtenerEficienciaPaginado(PDO $pdo, int $limit, int $offset): array
{
    return ejecutarConsulta($pdo,
        "SELECT ne.id, ne.measurement_date, ne.feed_conversion_ratio, ne.weight_gain_kg,
                a.name AS animal_name, a.tag
         FROM eficiencia_nutricional ne
         JOIN animales a ON ne.animal_id = a.id
         ORDER BY ne.measurement_date DESC
         LIMIT :limit OFFSET :offset",
        [':limit' => $limit, ':offset' => $offset]
    );
}

function contarEficiencia(PDO $pdo): int
{
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM eficiencia_nutricional");
    return (int)($r[0]['total'] ?? 0);
}

function eliminarEficiencia(PDO $pdo, int $id): void
{
    ejecutarConsulta($pdo, "DELETE FROM eficiencia_nutricional WHERE id=:id", [':id' => $id]);
}

/**
 * Calcula el FCR (índice de conversión alimenticia) para un animal en un período.
 * @return array Con 'alimento_kg', 'ganancia_kg', 'fcr'
 */
function calcularFCR(PDO $pdo, int $animalId, string $inicio, string $fin, float $pesoInicial, float $pesoFinal): array
{
    $result = ejecutarConsulta($pdo,
        "SELECT COALESCE(SUM(quantity_kg), 0) AS total_kg
         FROM alimentacion
         WHERE animal_id = :aid AND feeding_date BETWEEN :ini AND :fin",
        [':aid' => $animalId, ':ini' => $inicio, ':fin' => $fin]
    );
    $alimentoKg = (float)($result[0]['total_kg'] ?? 0);

    $gananciaKg = $pesoFinal - $pesoInicial;
    $fcr = ($gananciaKg > 0) ? round($alimentoKg / $gananciaKg, 2) : 0;

    return [
        'alimento_kg' => $alimentoKg,
        'ganancia_kg' => $gananciaKg,
        'fcr'         => $fcr
    ];
}

/**
 * Inserta un registro en eficiencia_nutricional a partir de un cálculo de FCR.
 */
function insertarEficiencia(PDO $pdo, int $animalId, string $fecha, float $fcr, float $gananciaKg): void
{
    ejecutarConsulta($pdo,
        "INSERT INTO eficiencia_nutricional (animal_id, measurement_date, feed_conversion_ratio, weight_gain_kg)
         VALUES (:a, :d, :fcr, :wg)",
        [':a' => $animalId, ':d' => $fecha, ':fcr' => $fcr, ':wg' => $gananciaKg]
    );
}