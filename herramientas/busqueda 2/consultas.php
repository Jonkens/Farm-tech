<?php
/**
 * Consultas a la base de datos para el registro de animales.
 * Todas las funciones reciben PDO y devuelven arrays o valores escalares.
 */

// ----------------------------------------------
// Funciones de obtención de datos
// ----------------------------------------------

function obtenerTiposAnimal(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id, name FROM animal_types ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerRazasPorTipo(PDO $pdo, int $tipoId): array
{
    $stmt = $pdo->prepare("SELECT id, name FROM breeds WHERE animal_type_id = ? ORDER BY name");
    $stmt->execute([$tipoId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerAnimalPorId(PDO $pdo, int $id): ?array
{
    $sql = "SELECT a.*, b.name as breed_name, t.name as animal_type_name, f.name as facility_name
            FROM animals a
            LEFT JOIN breeds b ON a.breed_id = b.id
            LEFT JOIN animal_types t ON a.animal_type_id = t.id
            LEFT JOIN facilities f ON a.facility_id = f.id
            WHERE a.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

function obtenerAnimalSimplePorId(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM animals WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

function obtenerAnimalesPaginados(PDO $pdo, int $limit, int $offset): array
{
    $sql = "SELECT a.*, b.name as breed_name, t.name as animal_type_name, f.name as facility_name
            FROM animals a
            LEFT JOIN breeds b ON a.breed_id = b.id
            LEFT JOIN animal_types t ON a.animal_type_id = t.id
            LEFT JOIN facilities f ON a.facility_id = f.id
            ORDER BY a.id DESC
            LIMIT $limit OFFSET $offset";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function contarAnimales(PDO $pdo): int
{
    return (int) $pdo->query("SELECT COUNT(*) FROM animals")->fetchColumn();
}

function obtenerEstadisticasPorTipo(PDO $pdo, array $tiposAnimal): array
{
    $stats = [];
    foreach ($tiposAnimal as $t) {
        $stats[$t['id']] = ['name' => $t['name'], 'count' => 0];
    }
    $all = $pdo->query("SELECT animal_type_id FROM animals")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($all as $a) {
        $tid = $a['animal_type_id'];
        if (isset($stats[$tid])) {
            $stats[$tid]['count']++;
        }
    }
    return $stats;
}

function obtenerUltimoRegistro(PDO $pdo): string
{
    $stmt = $pdo->query("SELECT id, name FROM animals ORDER BY id DESC LIMIT 1");
    $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($ultimo) {
        return 'ID: ' . $ultimo['id'] . ' - ' . htmlspecialchars($ultimo['name']);
    }
    return 'Sin registros';
}

// ----------------------------------------------
// Funciones de escritura (CRUD)
// ----------------------------------------------

function crearAnimal(PDO $pdo, array $datos): void
{
    $sql = "INSERT INTO animals (
                tag, name, breed_id, animal_type_id, birth_date,
                entry_date, weight_kg, gender, status, notes, facility_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $datos['tag'], $datos['name'], $datos['breed_id'], $datos['animal_type_id'],
        $datos['birth_date'], $datos['entry_date'], $datos['weight_kg'],
        $datos['gender'], $datos['status'], $datos['notes'],
        $datos['facility_id'] ?? null
    ]);
}

function actualizarAnimal(PDO $pdo, array $datos): void
{
    $sql = "UPDATE animals SET 
                tag=?, name=?, breed_id=?, animal_type_id=?, birth_date=?,
                weight_kg=?, gender=?, status=?, notes=?, facility_id=?
            WHERE id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $datos['tag'], $datos['name'], $datos['breed_id'], $datos['animal_type_id'],
        $datos['birth_date'], $datos['weight_kg'], $datos['gender'],
        $datos['status'], $datos['notes'], $datos['facility_id'] ?? null,
        $datos['id']
    ]);
}

function eliminarAnimal(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare("DELETE FROM animals WHERE id = ?");
    $stmt->execute([$id]);
}

// ----------------------------------------------
// Funciones auxiliares con lógica de negocio que requieren DB
// ----------------------------------------------

function getNextTag(PDO $pdo, string $gender): string
{
    $prefix = $gender;
    $stmt = $pdo->prepare("SELECT tag FROM animals WHERE tag LIKE ? ORDER BY LENGTH(tag) DESC, tag DESC LIMIT 1");
    $stmt->execute([$prefix . '%']);
    $last = $stmt->fetchColumn();
    if ($last) {
        $num = (int) substr($last, 1);
        $next = $num + 1;
    } else {
        $next = 1;
    }
    return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
}

function tagExiste(PDO $pdo, string $tag, ?int $excludeId = null): bool
{
    if ($excludeId) {
        $stmt = $pdo->prepare("SELECT id FROM animals WHERE tag = ? AND id != ?");
        $stmt->execute([$tag, $excludeId]);
    } else {
        $stmt = $pdo->prepare("SELECT id FROM animals WHERE tag = ?");
        $stmt->execute([$tag]);
    }
    return (bool) $stmt->fetch();
}

// ----------------------------------------------
// Nuevas funciones para agregar tipos y razas
// ----------------------------------------------

function crearTipoAnimal(PDO $pdo, string $nombre): int
{
    $stmt = $pdo->prepare("INSERT INTO animal_types (name) VALUES (?)");
    $stmt->execute([$nombre]);
    return (int) $pdo->lastInsertId();
}

function crearRaza(PDO $pdo, string $nombre, int $animalTypeId): int
{
    $stmt = $pdo->prepare("INSERT INTO breeds (name, animal_type_id) VALUES (?, ?)");
    $stmt->execute([$nombre, $animalTypeId]);
    return (int) $pdo->lastInsertId();
}

// ----------------------------------------------
// Funciones para instalaciones (facilities)
// ----------------------------------------------

function obtenerFacilidades(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT id, name, facility_type, capacity, location FROM facilities ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function crearFacilidad(PDO $pdo, string $nombre, string $tipo, ?int $capacidad, ?string $ubicacion): int
{
    $sql = "INSERT INTO facilities (name, facility_type, capacity, location) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $tipo, $capacidad, $ubicacion]);
    return (int) $pdo->lastInsertId();
}