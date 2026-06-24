<?php
require_once __DIR__ . '/../../includes/query_helper.php';

// ──────────────────────────────────────────────
// ANIMALES
// ──────────────────────────────────────────────
function obtenerAnimalesPaginados(PDO $pdo, int $limit, int $offset): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.id, a.tag, a.name, r.name AS breed, a.weight_kg, a.status, a.gender, a.birth_date
         FROM animales a
         LEFT JOIN razas r ON a.breed_id = r.id
         WHERE a.status != 'sacrificado'
         ORDER BY a.id DESC
         LIMIT :limit OFFSET :offset",
        [':limit' => $limit, ':offset' => $offset]
    );
}

function contarAnimales(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM animales");
    return (int)($r[0]['total'] ?? 0);
}

function insertarAnimal(
    PDO $pdo,
    string $tag,
    string $name,
    int $breedId,
    int $animalTypeId,
    float $weight,
    string $status,
    string $gender
): bool {
    $sql = "INSERT INTO animales (
                tag, name, breed_id, animal_type_id, weight_kg, status, gender, birth_date
            ) VALUES (
                :t, :n, :b, :at, :w, :s, :g, CURRENT_DATE
            )";
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':t'  => trim($tag),
            ':n'  => trim($name),
            ':b'  => $breedId,
            ':at' => $animalTypeId,
            ':w'  => $weight,
            ':s'  => $status,
            ':g'  => $gender
        ]);
    } catch (PDOException $e) {
        error_log('Error al insertar animal: ' . $e->getMessage());
        return false;
    }
}

function actualizarAnimal(PDO $pdo, int $id, string $tag, string $name, int $breedId, float $weight, string $status, string $gender): void {
    ejecutarConsulta($pdo,
        "UPDATE animales SET tag=:t, name=:n, breed_id=:b, weight_kg=:w, status=:s, gender=:g WHERE id=:id",
        [':id'=>$id, ':t'=>$tag, ':n'=>$name, ':b'=>$breedId, ':w'=>$weight, ':s'=>$status, ':g'=>$gender]
    );
}

function eliminarAnimal(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM animales WHERE id=:id", [':id'=>$id]);
}

function obtenerRazas(PDO $pdo): array {
    return ejecutarConsulta($pdo, "SELECT id, name FROM razas ORDER BY name");
}

function obtenerTiposAnimales(PDO $pdo): array {
    return ejecutarConsulta($pdo, "SELECT id, name FROM tipos_animal ORDER BY name");
}

function obtenerTodosLosTags(PDO $pdo): array {
    $result = ejecutarConsulta($pdo, "SELECT tag FROM animales");
    return array_column($result, 'tag');
}

// ──────────────────────────────────────────────
// LECHE (produccion_leche)
// ──────────────────────────────────────────────
function obtenerLechePaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT m.id, m.animal_id, a.name AS animal_name, m.production_date, m.quantity_liters
         FROM produccion_leche m
         JOIN animales a ON m.animal_id = a.id
         ORDER BY m.production_date DESC
         LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarLeche(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM produccion_leche");
    return (int)($r[0]['total'] ?? 0);
}

function insertarLeche(PDO $pdo, int $animalId, string $fecha, float $litros): void {
    ejecutarConsulta($pdo,
        "INSERT INTO produccion_leche (animal_id, production_date, quantity_liters) VALUES (:a, :f, :l)",
        [':a'=>$animalId, ':f'=>$fecha, ':l'=>$litros]
    );
}

function actualizarLeche(PDO $pdo, int $id, int $animalId, string $fecha, float $litros): void {
    ejecutarConsulta($pdo,
        "UPDATE produccion_leche SET animal_id=:a, production_date=:f, quantity_liters=:l WHERE id=:id",
        [':id'=>$id, ':a'=>$animalId, ':f'=>$fecha, ':l'=>$litros]
    );
}

function eliminarLeche(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM produccion_leche WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// SACRIFICIOS (registros_sacrificio)
// ──────────────────────────────────────────────
function obtenerSacrificiosRecientes(PDO $pdo, int $limite = 10): array {
    return ejecutarConsulta($pdo,
        "SELECT sr.slaughter_date, ta.name AS animal_type, sr.quantity, a.tag AS animal_tag
         FROM registros_sacrificio sr
         LEFT JOIN tipos_animal ta ON sr.animal_type_id = ta.id
         LEFT JOIN animales a ON sr.animal_id = a.id
         ORDER BY sr.slaughter_date DESC
         LIMIT :limite",
        [':limite' => $limite]
    );
}

function marcarAnimalSacrificado(PDO $pdo, int $animalId): void {
    ejecutarConsulta($pdo, "UPDATE animales SET status = 'sacrificado' WHERE id = :id", [':id' => $animalId]);
}

function registrarSacrificio(PDO $pdo, int $animalId, int $animalTypeId): void {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("INSERT INTO registros_sacrificio (animal_id, animal_type_id, slaughter_date, quantity) VALUES (:a, :t, CURRENT_DATE, 1)");
        $stmt->execute([':a' => $animalId, ':t' => $animalTypeId]);
        $stmt2 = $pdo->prepare("UPDATE animales SET status = 'sacrificado' WHERE id = :id");
        $stmt2->execute([':id' => $animalId]);
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Error al registrar sacrificio: ' . $e->getMessage());
        throw $e;
    }
}

// ──────────────────────────────────────────────
// HUEVOS (produccion_huevos)
// ──────────────────────────────────────────────
function obtenerHuevosPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT id, production_date, quantity FROM produccion_huevos ORDER BY production_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarHuevos(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM produccion_huevos");
    return (int)($r[0]['total'] ?? 0);
}

function insertarHuevos(PDO $pdo, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo,
        "INSERT INTO produccion_huevos (production_date, quantity) VALUES (:f, :c)",
        [':f'=>$fecha, ':c'=>$cantidad]
    );
}

function actualizarHuevos(PDO $pdo, int $id, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo,
        "UPDATE produccion_huevos SET production_date=:f, quantity=:c WHERE id=:id",
        [':id'=>$id, ':f'=>$fecha, ':c'=>$cantidad]
    );
}

function eliminarHuevos(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM produccion_huevos WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// GALLINAS (inventario_pollos)
// ──────────────────────────────────────────────
function obtenerGallinasPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT id, inventory_date, quantity FROM inventario_pollos ORDER BY inventory_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarGallinas(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM inventario_pollos");
    return (int)($r[0]['total'] ?? 0);
}

function insertarGallinas(PDO $pdo, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo,
        "INSERT INTO inventario_pollos (inventory_date, quantity) VALUES (:f, :c)",
        [':f'=>$fecha, ':c'=>$cantidad]
    );
}

function actualizarGallinas(PDO $pdo, int $id, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo,
        "UPDATE inventario_pollos SET inventory_date=:f, quantity=:c WHERE id=:id",
        [':id'=>$id, ':f'=>$fecha, ':c'=>$cantidad]
    );
}

function eliminarGallinas(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM inventario_pollos WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// ANUNCIOS (anuncios_animales)
// ──────────────────────────────────────────────
function obtenerAnunciosPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT aa.id, aa.ad_type, aa.quantity, aa.weight_kg, aa.price_per_unit, aa.status,
                ta.name AS animal_type, r.name AS breed, u.username
         FROM anuncios_animales aa
         JOIN tipos_animal ta ON aa.animal_type_id = ta.id
         LEFT JOIN razas r ON aa.breed_id = r.id
         JOIN usuarios u ON aa.user_id = u.id
         ORDER BY aa.created_at DESC
         LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarAnuncios(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM anuncios_animales");
    return (int)($r[0]['total'] ?? 0);
}

function eliminarAnuncio(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM anuncios_animales WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// TRANSACCIONES
// ──────────────────────────────────────────────
function obtenerTransaccionesPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT t.id, t.transaction_date, t.quantity, t.total_amount,
                ta.name AS animal_type,
                s.username AS seller,
                b.username AS buyer
         FROM transacciones t
         JOIN tipos_animal ta ON t.animal_type_id = ta.id
         JOIN usuarios s ON t.seller_id = s.id
         JOIN usuarios b ON t.buyer_id = b.id
         ORDER BY t.transaction_date DESC
         LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarTransacciones(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM transacciones");
    return (int)($r[0]['total'] ?? 0);
}

function eliminarTransaccion(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM transacciones WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// ÓRDENES DE COMPRA / PROVEEDORES
// ──────────────────────────────────────────────
function obtenerOrdenesPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT po.id, po.order_date, po.expected_delivery, po.status, po.total_amount,
                s.name AS supplier
         FROM ordenes_compra po
         JOIN proveedores s ON po.supplier_id = s.id
         ORDER BY po.order_date DESC
         LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarOrdenes(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM ordenes_compra");
    return (int)($r[0]['total'] ?? 0);
}

function eliminarOrden(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM ordenes_compra WHERE id=:id", [':id'=>$id]);
}

function obtenerProveedores(PDO $pdo): array {
    return ejecutarConsulta($pdo, "SELECT id, name FROM proveedores ORDER BY name");
}

function insertarOrden(PDO $pdo, int $supplierId, string $orderDate, string $expected, float $total, string $status = 'pendiente'): void {
    ejecutarConsulta($pdo,
        "INSERT INTO ordenes_compra (supplier_id, order_date, expected_delivery, total_amount, status) VALUES (:s, :o, :e, :t, :st)",
        [':s'=>$supplierId, ':o'=>$orderDate, ':e'=>$expected, ':t'=>$total, ':st'=>$status]
    );
}

// ──────────────────────────────────────────────
// EMPLEADOS / NÓMINA
// ──────────────────────────────────────────────
function obtenerEmpleados(PDO $pdo): array {
    return ejecutarConsulta($pdo, "SELECT id, name, role, monthly_salary FROM empleados ORDER BY name");
}

function insertarEmpleado(PDO $pdo, string $name, string $role, float $salary): void {
    ejecutarConsulta($pdo,
        "INSERT INTO empleados (name, role, monthly_salary) VALUES (:n, :r, :s)",
        [':n'=>$name, ':r'=>$role, ':s'=>$salary]
    );
}

function actualizarEmpleado(PDO $pdo, int $id, string $name, string $role, float $salary): void {
    ejecutarConsulta($pdo,
        "UPDATE empleados SET name=:n, role=:r, monthly_salary=:s WHERE id=:id",
        [':id'=>$id, ':n'=>$name, ':r'=>$role, ':s'=>$salary]
    );
}

function eliminarEmpleado(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM empleados WHERE id=:id", [':id'=>$id]);
}

function obtenerUltimaNomina(PDO $pdo): array {
    return ejecutarConsulta($pdo,
        "SELECT p.id, e.name, e.role, p.gross_salary, p.deductions, p.net_pay, p.payment_date
         FROM nomina p
         JOIN empleados e ON p.employee_id = e.id
         WHERE p.period = (SELECT MAX(period) FROM nomina)
         ORDER BY e.name"
    );
}

// ──────────────────────────────────────────────
// ALIMENTACIÓN
// ──────────────────────────────────────────────
function obtenerAlimentacionPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT f.id, f.feeding_date, f.quantity_kg,
                a.name AS animal_name, a.tag,
                ca.name AS food_name
         FROM alimentacion f
         JOIN animales a ON f.animal_id = a.id
         JOIN catalogo_alimentos ca ON f.food_id = ca.id
         ORDER BY f.feeding_date DESC, a.name
         LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarAlimentacion(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM alimentacion");
    return (int)($r[0]['total'] ?? 0);
}

function obtenerAlimentos(PDO $pdo): array {
    return ejecutarConsulta($pdo, "SELECT id, name FROM catalogo_alimentos ORDER BY name");
}

function insertarAlimentacion(PDO $pdo, int $animalId, int $foodId, string $fecha, float $kg): void {
    ejecutarConsulta($pdo,
        "INSERT INTO alimentacion (animal_id, food_id, feeding_date, quantity_kg) VALUES (:a, :f, :d, :k)",
        [':a'=>$animalId, ':f'=>$foodId, ':d'=>$fecha, ':k'=>$kg]
    );
}

function actualizarAlimentacion(PDO $pdo, int $id, int $animalId, int $foodId, string $fecha, float $kg): void {
    ejecutarConsulta($pdo,
        "UPDATE alimentacion SET animal_id=:a, food_id=:f, feeding_date=:d, quantity_kg=:k WHERE id=:id",
        [':id'=>$id, ':a'=>$animalId, ':f'=>$foodId, ':d'=>$fecha, ':k'=>$kg]
    );
}

function eliminarAlimentacion(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM alimentacion WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// CATÁLOGO DE ALIMENTOS (catalogo_alimentos)
// ──────────────────────────────────────────────
function obtenerCatalogoAlimentos(PDO $pdo): array {
    return ejecutarConsulta($pdo,
        "SELECT id, name, food_type, cost_per_kg, protein_pct, stock_kg FROM catalogo_alimentos ORDER BY name"
    );
}

function insertarAlimento(PDO $pdo, string $name, string $type, float $cost, float $protein, float $stock): void {
    ejecutarConsulta($pdo,
        "INSERT INTO catalogo_alimentos (name, food_type, cost_per_kg, protein_pct, stock_kg) VALUES (:n, :t, :c, :p, :s)",
        [':n'=>$name, ':t'=>$type, ':c'=>$cost, ':p'=>$protein, ':s'=>$stock]
    );
}

function actualizarAlimento(PDO $pdo, int $id, string $name, string $type, float $cost, float $protein, float $stock): void {
    ejecutarConsulta($pdo,
        "UPDATE catalogo_alimentos SET name=:n, food_type=:t, cost_per_kg=:c, protein_pct=:p, stock_kg=:s WHERE id=:id",
        [':id'=>$id, ':n'=>$name, ':t'=>$type, ':c'=>$cost, ':p'=>$protein, ':s'=>$stock]
    );
}

function eliminarAlimento(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM catalogo_alimentos WHERE id=:id", [':id'=>$id]);
}

// ──────────────────────────────────────────────
// EFICIENCIA NUTRICIONAL
// ──────────────────────────────────────────────
function obtenerEficienciaPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT ne.id, ne.measurement_date, ne.feed_conversion_ratio, ne.weight_gain_kg,
                a.name AS animal_name
         FROM eficiencia_nutricional ne
         JOIN animales a ON ne.animal_id = a.id
         ORDER BY ne.measurement_date DESC
         LIMIT :limit OFFSET :offset",
        [':limit'=>$limit, ':offset'=>$offset]
    );
}

function contarEficiencia(PDO $pdo): int {
    $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM eficiencia_nutricional");
    return (int)($r[0]['total'] ?? 0);
}

function eliminarEficiencia(PDO $pdo, int $id): void {
    ejecutarConsulta($pdo, "DELETE FROM eficiencia_nutricional WHERE id=:id", [':id'=>$id]);
}
/**
 * archivo dentro de la carpeta de 
 * 
 * administrativas/t_seccion_ADMIN
 */