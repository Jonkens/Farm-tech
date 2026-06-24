<?php
/**
 * Funciones auxiliares para ejecución segura de consultas PDO.
 * Adaptado para la base de datos ganaderia2 (PostgreSQL).
 * Uso: require_once __DIR__ . '/../../includes/query_helper.php';
 */

function ejecutarConsulta(PDO $pdo, string $sql, array $params = []): array {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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

function obtenerProduccionLeche(PDO $pdo, string $inicio, string $fin, ?int $animal_id = null): array {
    $sql = "SELECT animal_id, production_date, quantity_liters 
            FROM produccion_leche 
            WHERE production_date BETWEEN :inicio AND :fin";
    $params = [':inicio' => $inicio, ':fin' => $fin];
    if ($animal_id !== null) {
        $sql .= " AND animal_id = :animal_id";
        $params[':animal_id'] = $animal_id;
    }
    $rows = ejecutarConsulta($pdo, $sql, $params);
    
    if ($animal_id !== null) {
        $datos = [];
        foreach ($rows as $row) {
            $datos[$row['production_date']] = (float) $row['quantity_liters'];
        }
        return $datos;
    }
    
    $result = [];
    foreach ($rows as $row) {
        $result[$row['animal_id']][$row['production_date']] = (float) $row['quantity_liters'];
    }
    return $result;
}

function obtenerProduccionHuevos(PDO $pdo, string $inicio, string $fin): array {
    $sql = "SELECT inventory_date as fecha, quantity as total
            FROM inventario_pollos
            WHERE inventory_date BETWEEN :inicio AND :fin
            ORDER BY inventory_date";
    $rows = ejecutarConsulta($pdo, $sql, [':inicio' => $inicio, ':fin' => $fin]);
    $datos = [];
    foreach ($rows as $row) {
        $datos[$row['fecha']] = (int) $row['total'];
    }
    return $datos;
}

function obtenerResumenFinanciero(PDO $pdo, string $inicio, string $fin): array {
    $sql = "SELECT type, SUM(amount) as total
            FROM entradas_financieras
            WHERE entry_date BETWEEN :inicio AND :fin
            GROUP BY type";
    $rows = ejecutarConsulta($pdo, $sql, [':inicio' => $inicio, ':fin' => $fin]);
    $ingresos = 0;
    $gastos = 0;
    foreach ($rows as $row) {
        if ($row['type'] === 'I') {
            $ingresos = (float) $row['total'];
        } elseif ($row['type'] === 'G') {
            $gastos = (float) $row['total'];
        }
    }
    return [
        'ingresos' => $ingresos,
        'gastos'   => $gastos,
        'balance'  => $ingresos - $gastos,
    ];
}

function obtenerAnimalesPorTipoYEstado(PDO $pdo): array {
    $sql = "SELECT ta.name as tipo_animal, a.status, COUNT(*) as cantidad
            FROM animales a
            JOIN tipos_animal ta ON a.animal_type_id = ta.id
            GROUP BY ta.name, a.status
            ORDER BY ta.name, a.status";
    $rows = ejecutarConsulta($pdo, $sql);
    $result = [];
    foreach ($rows as $row) {
        $result[$row['tipo_animal']][$row['status']] = (int) $row['cantidad'];
    }
    return $result;
}

function obtenerEventosSaludRecientes(PDO $pdo, int $limit = 20): array {
    $sql = "SELECT a.tag as animal_tag, es.event_type, es.event_date, es.product_used, es.notes
            FROM eventos_salud es
            LEFT JOIN animales a ON es.animal_id = a.id
            WHERE es.animal_id IS NOT NULL
            ORDER BY es.event_date DESC
            LIMIT :limit";
    return ejecutarConsulta($pdo, $sql, [':limit' => $limit]);
}

function obtenerPreciosMercadoActuales(PDO $pdo): array {
    $sql = "SELECT DISTINCT ON (product_type) product_type, price_per_unit, unit, price_date
            FROM precios_mercado
            ORDER BY product_type, price_date DESC";
    $rows = ejecutarConsulta($pdo, $sql);
    $result = [];
    foreach ($rows as $row) {
        $result[$row['product_type']] = [
            'price' => (float) $row['price_per_unit'],
            'unit'  => $row['unit'],
            'date'  => $row['price_date']
        ];
    }
    return $result;
}

function obtenerTopProductoresLeche(PDO $pdo, string $inicio, string $fin, int $top = 10): array {
    $sql = "SELECT animal_id, SUM(quantity_liters) as total
            FROM produccion_leche
            WHERE production_date BETWEEN :inicio AND :fin
            GROUP BY animal_id
            ORDER BY total DESC
            LIMIT :top";
    $rows = ejecutarConsulta($pdo, $sql, [':inicio' => $inicio, ':fin' => $fin, ':top' => $top]);
    $result = [];
    foreach ($rows as $row) {
        $result[$row['animal_id']] = (float) $row['total'];
    }
    return $result;
}

// ==================== FUNCIONES PARA EL MÓDULO DE PARTOS ====================
// Nota: todas estas funciones usan getDB() internamente, que debe estar definida en db.php
// No definir getDB() aquí para evitar duplicación.

// ----- ANIMALES -----
function obtenerAnimales(): array {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT a.*, at.name as tipo_nombre, r.name as raza_nombre 
        FROM animales a
        LEFT JOIN tipos_animal at ON a.animal_type_id = at.id
        LEFT JOIN razas r ON a.breed_id = r.id
        ORDER BY a.id
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarAnimalPorId(int $id): ?array {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM animales WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function obtenerMachos(): array {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT * FROM animales 
        WHERE gender = 'M' 
        ORDER BY name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerHembras(): array {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT * FROM animales 
        WHERE gender = 'F' 
        ORDER BY name
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerCrias(): array {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT a.*, at.name as tipo_nombre 
        FROM animales a
        JOIN partos p ON a.id = p.cria_id
        LEFT JOIN tipos_animal at ON a.animal_type_id = at.id
        ORDER BY a.birth_date DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerTiposAnimales(): array {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT id, name, dias_gestacion FROM tipos_animal ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerRazasPorTipo(int $tipoId): array {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id, name FROM razas WHERE animal_type_id = ? ORDER BY name");
    $stmt->execute([$tipoId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function generarArete(string $sexo): string {
    $prefijo = ($sexo === 'Macho') ? 'M' : 'F';
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT tag FROM animales WHERE tag LIKE ? ORDER BY LENGTH(tag) DESC, tag DESC LIMIT 1");
    $stmt->execute([$prefijo . '%']);
    $ultimo = $stmt->fetchColumn();
    if ($ultimo) {
        $num = (int) substr($ultimo, 1);
        $nuevo_num = $num + 1;
    } else {
        $nuevo_num = 1;
    }
    return $prefijo . str_pad($nuevo_num, 3, '0', STR_PAD_LEFT);
}

function guardarAnimal(string $tag, string $nombre, ?int $breedId, int $tipoId, ?string $fechaNac, ?float $peso, string $sexo, ?int $padreId = null, ?int $madreId = null): int {
    $pdo = getDB();
    $gender = ($sexo === 'Macho') ? 'M' : 'F';
    $sql = "INSERT INTO animales (tag, name, breed_id, animal_type_id, birth_date, weight_kg, gender, father_id, mother_id, status, entry_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo', CURRENT_DATE) RETURNING id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tag, $nombre, $breedId, $tipoId, $fechaNac, $peso, $gender, $padreId, $madreId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['id'];
}

function actualizarAnimal(int $id, string $nombre, int $tipoId, ?int $breedId, ?string $fechaNac, string $sexo): void {
    $pdo = getDB();
    $gender = ($sexo === 'Macho') ? 'M' : 'F';
    $sql = "UPDATE animales SET name = ?, animal_type_id = ?, breed_id = ?, birth_date = ?, gender = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $tipoId, $breedId, $fechaNac, $gender, $id]);
}

function eliminarAnimal(int $id): void {
    $pdo = getDB();
    $pdo->prepare("DELETE FROM partos WHERE cria_id = ? OR madre_id = ? OR padre_id = ?")->execute([$id, $id, $id]);
    $pdo->prepare("DELETE FROM eventos_reproductivos WHERE animal_id = ? OR padre_id = ?")->execute([$id, $id]);
    $pdo->prepare("DELETE FROM animales WHERE id = ?")->execute([$id]);
}

// ----- EVENTOS REPRODUCTIVOS -----
function obtenerEventos(): array {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT e.*, a.name as animal_name, a.tag as animal_tag
        FROM eventos_reproductivos e
        JOIN animales a ON e.animal_id = a.id
        ORDER BY e.fecha DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function guardarEvento(int $animalId, string $tipoEvento, string $fecha, ?int $padreId = null, ?string $notas = null): int {
    $pdo = getDB();
    $sql = "INSERT INTO eventos_reproductivos (animal_id, tipo_evento, fecha, padre_id, notas) VALUES (?, ?, ?, ?, ?) RETURNING id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$animalId, $tipoEvento, $fecha, $padreId, $notas]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['id'];
}

function actualizarEvento(int $id, int $animalId, string $tipoEvento, string $fecha, ?int $padreId, ?string $notas): void {
    $pdo = getDB();
    $sql = "UPDATE eventos_reproductivos SET animal_id = ?, tipo_evento = ?, fecha = ?, padre_id = ?, notas = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$animalId, $tipoEvento, $fecha, $padreId, $notas, $id]);
}

function eliminarEvento(int $id): void {
    $pdo = getDB();
    $pdo->prepare("DELETE FROM eventos_reproductivos WHERE id = ?")->execute([$id]);
}

// ----- PARTOS -----
function obtenerPartos(): array {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT p.*, 
               m.name as madre_nombre, m.tag as madre_tag,
               f.name as padre_nombre, f.tag as padre_tag,
               c.name as cria_nombre, c.tag as cria_tag
        FROM partos p
        LEFT JOIN animales m ON p.madre_id = m.id
        LEFT JOIN animales f ON p.padre_id = f.id
        LEFT JOIN animales c ON p.cria_id = c.id
        ORDER BY p.fecha_parto DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function guardarParto(int $madreId, ?int $padreId, int $criaId, string $fechaParto, ?float $peso, ?string $notas): int {
    $pdo = getDB();
    $sql = "INSERT INTO partos (madre_id, padre_id, cria_id, fecha_parto, peso_kg, notas) VALUES (?, ?, ?, ?, ?, ?) RETURNING id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$madreId, $padreId, $criaId, $fechaParto, $peso, $notas]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['id'];
}

// ----- FICHA ANIMAL -----
function obtenerAnimalCompleto(int $id): ?array {
    $pdo = getDB();
    $stmt = $pdo->prepare("
        SELECT a.*, at.name as tipo_nombre, r.name as raza_nombre
        FROM animales a
        LEFT JOIN tipos_animal at ON a.animal_type_id = at.id
        LEFT JOIN razas r ON a.breed_id = r.id
        WHERE a.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function obtenerPadres(int $animalId): array {
    $pdo = getDB();
    $stmt = $pdo->prepare("
        SELECT 
            (SELECT row_to_json(m) FROM (SELECT id, name, tag FROM animales WHERE id = a.mother_id) m) as mother,
            (SELECT row_to_json(f) FROM (SELECT id, name, tag FROM animales WHERE id = a.father_id) f) as father
        FROM animales a WHERE a.id = ?
    ");
    $stmt->execute([$animalId]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return [
        'madre' => $res['mother'] ? json_decode($res['mother'], true) : null,
        'padre' => $res['father'] ? json_decode($res['father'], true) : null
    ];
}

function obtenerCriasDeAnimal(int $animalId): array {
    $pdo = getDB();
    $animal = obtenerAnimalCompleto($animalId);
    if (!$animal) return [];
    if ($animal['gender'] == 'F') {
        $stmt = $pdo->prepare("
            SELECT a.*, p.fecha_parto as birth_date
            FROM animales a
            JOIN partos p ON a.id = p.cria_id
            WHERE p.madre_id = ?
            ORDER BY p.fecha_parto DESC
        ");
    } else {
        $stmt = $pdo->prepare("
            SELECT a.*, p.fecha_parto as birth_date
            FROM animales a
            JOIN partos p ON a.id = p.cria_id
            WHERE p.padre_id = ?
            ORDER BY p.fecha_parto DESC
        ");
    }
    $stmt->execute([$animalId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerEventosDeAnimal(int $animalId): array {
    $pdo = getDB();
    $stmt = $pdo->prepare("
        SELECT * FROM eventos_reproductivos 
        WHERE animal_id = ? 
        ORDER BY fecha DESC
    ");
    $stmt->execute([$animalId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ----- UTILIDADES -----
function calcularEdad(?string $fechaNac): string {
    if (!$fechaNac) return '-';
    $nac = new DateTime($fechaNac);
    $hoy = new DateTime();
    $diff = $hoy->diff($nac);
    if ($diff->y == 0 && $diff->m == 0) return $diff->d . ' días';
    if ($diff->y == 0) return $diff->m . ' meses';
    return $diff->y . ' años ' . $diff->m . ' meses';
}

function diasGestacion(int $animalTypeId): int {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT dias_gestacion FROM tipos_animal WHERE id = ?");
    $stmt->execute([$animalTypeId]);
    return (int) ($stmt->fetchColumn() ?: 280);
}

// ==================== ESTADÍSTICAS PARA DASHBOARD (con PDO) ====================
function contarAnimales(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM animales");
    return (int) $stmt->fetchColumn();
}

function contarMachos(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM animales WHERE gender = 'M'");
    return (int) $stmt->fetchColumn();
}

function contarHembras(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM animales WHERE gender = 'F'");
    return (int) $stmt->fetchColumn();
}

function contarCrias(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM partos");
    return (int) $stmt->fetchColumn();
}

function contarPartosMes(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM partos WHERE TO_CHAR(fecha_parto, 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')");
    return (int) $stmt->fetchColumn();
}

function contarEventosMes(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM eventos_reproductivos WHERE TO_CHAR(fecha, 'YYYY-MM') = TO_CHAR(CURRENT_DATE, 'YYYY-MM')");
    return (int) $stmt->fetchColumn();
}

function contarPrenadas(PDO $pdo): int {
    $stmt = $pdo->query("
        SELECT COUNT(DISTINCT animal_id) 
        FROM eventos_reproductivos 
        WHERE tipo_evento = 'Confirmación de preñez' 
        AND fecha >= CURRENT_DATE - INTERVAL '280 days'
    ");
    return (int) $stmt->fetchColumn();
}

function obtenerUltimosPartos(PDO $pdo, int $limite = 5): array {
    $stmt = $pdo->prepare("
        SELECT p.*, 
               m.name as madre_nombre, m.tag as madre_tag,
               c.name as cria_nombre, c.tag as cria_tag
        FROM partos p
        LEFT JOIN animales m ON p.madre_id = m.id
        LEFT JOIN animales c ON p.cria_id = c.id
        ORDER BY p.fecha_parto DESC
        LIMIT ?
    ");
    $stmt->execute([$limite]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerAlertasPreñez(PDO $pdo): array {
    $sql = "
        SELECT 
            a.id, a.name, a.tag, at.name as tipo, at.dias_gestacion,
            e.fecha as fecha_prenez,
            (e.fecha + (at.dias_gestacion || ' days')::interval)::date as fecha_parto_estimada,
            EXTRACT(DAY FROM ((e.fecha + (at.dias_gestacion || ' days')::interval) - CURRENT_DATE)) as dias_restantes
        FROM eventos_reproductivos e
        JOIN animales a ON e.animal_id = a.id
        JOIN tipos_animal at ON a.animal_type_id = at.id
        WHERE e.tipo_evento = 'Confirmación de preñez'
        AND (e.fecha + (at.dias_gestacion || ' days')::interval)::date >= CURRENT_DATE
        AND NOT EXISTS (
            SELECT 1 FROM partos p 
            WHERE p.madre_id = a.id 
            AND p.fecha_parto >= e.fecha
        )
        ORDER BY fecha_parto_estimada ASC
    ";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}