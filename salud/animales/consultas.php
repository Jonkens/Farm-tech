<?php
/**
 * Consultas para el módulo de salud del ganado.
 * Base de datos: ganaderia2 (PostgreSQL)
 */

require_once __DIR__ . '/../../includes/query_helper.php';

/**
 * Obtiene lista de animales activos (no vendidos/muertos) para el buscador.
 */
function obtenerAnimalesActivos(PDO $pdo): array
{
    $sql = "SELECT a.id, a.tag, a.name, a.breed_id, r.name AS raza
            FROM animales a
            LEFT JOIN razas r ON a.breed_id = r.id
            WHERE a.status NOT IN ('vendido', 'muerto')
            ORDER BY a.tag";
    return ejecutarConsulta($pdo, $sql);
}

/**
 * Obtiene los datos de alimentación y agua de un animal específico.
 */
function obtenerDatosAlimentacionAnimal(PDO $pdo, int $animalId): array
{
    $sql = "SELECT alimentacion, consumo_agua FROM animales WHERE id = :id";
    $result = ejecutarConsulta($pdo, $sql, [':id' => $animalId]);
    return $result[0] ?? ['alimentacion' => '', 'consumo_agua' => ''];
}

/**
 * Obtiene el historial de salud completo de un animal.
 */
function obtenerHistorialSalud(PDO $pdo, int $animalId): array
{
    $sql = "SELECT fecha_chequeo, estado_salud, enfermedad_detectada,
                   vacuna_aplicada, tratamiento, observaciones
            FROM historial_salud_animal
            WHERE animal_id = :id
            ORDER BY fecha_chequeo DESC";
    return ejecutarConsulta($pdo, $sql, [':id' => $animalId]);
}

/**
 * Obtiene los últimos chequeos de cada animal (uno por animal).
 */
function obtenerUltimosChequeos(PDO $pdo): array
{
    $sql = "SELECT DISTINCT ON (a.id)
                a.id AS animal_id,
                a.tag,
                a.name AS nombre,
                r.name AS raza,
                a.alimentacion,
                a.consumo_agua,
                h.estado_salud,
                h.vacuna_aplicada,
                h.fecha_chequeo
            FROM animales a
            INNER JOIN historial_salud_animal h ON a.id = h.animal_id
            LEFT JOIN razas r ON a.breed_id = r.id
            WHERE a.status NOT IN ('vendido', 'muerto')
            ORDER BY a.id, h.fecha_chequeo DESC";
    return ejecutarConsulta($pdo, $sql);
}

/**
 * Guarda un nuevo chequeo de salud y actualiza los datos del animal.
 * @return array ['success' => bool, 'message' => string]
 */
function guardarChequeo(PDO $pdo, int $animalId, string $alimentacion, string $consumoAgua,
                        string $estadoSalud, ?string $enfermedad, ?string $vacuna,
                        ?string $tratamiento, ?string $observaciones): array
{
    // Validación de estado de salud permitido
    $estadosPermitidos = ['Excelente', 'Bueno', 'Regular', 'Enfermo', 'En observación'];
    if (!in_array($estadoSalud, $estadosPermitidos)) {
        return ['success' => false, 'message' => 'Estado de salud no válido.'];
    }

    try {
        $pdo->beginTransaction();

        // 1. Actualizar alimentación y consumo de agua
        $sqlUpdate = "UPDATE animales SET alimentacion = :alim, consumo_agua = :agua WHERE id = :id";
        $stmt = $pdo->prepare($sqlUpdate);
        $stmt->execute([':alim' => $alimentacion, ':agua' => $consumoAgua, ':id' => $animalId]);

        // 2. Insertar historial de salud
        $sqlInsert = "INSERT INTO historial_salud_animal
                      (animal_id, estado_salud, enfermedad_detectada, vacuna_aplicada,
                       tratamiento, observaciones)
                      VALUES (:aid, :estado, :enf, :vac, :trat, :obs)";
        $stmt2 = $pdo->prepare($sqlInsert);
        $stmt2->execute([
            ':aid' => $animalId,
            ':estado' => $estadoSalud,
            ':enf' => $enfermedad,
            ':vac' => $vacuna,
            ':trat' => $tratamiento,
            ':obs' => $observaciones
        ]);

        // 3. Actualizar estado general del animal según el estado de salud
        $nuevoStatus = match ($estadoSalud) {
            'Enfermo' => 'enfermo',
            'En observación' => 'observacion',
            default => 'activo'
        };
        $sqlStatus = "UPDATE animales SET status = :status WHERE id = :id";
        $stmt3 = $pdo->prepare($sqlStatus);
        $stmt3->execute([':status' => $nuevoStatus, ':id' => $animalId]);

        $pdo->commit();
        return ['success' => true, 'message' => 'Chequeo guardado correctamente.'];
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()];
    }
}