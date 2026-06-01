<?php
require_once __DIR__ . '/../../includes/query_helper.php';

// ──────────────────────────────────────────────
// ANIMALES
// ──────────────────────────────────────────────
function obtenerAnimalesPaginados(PDO $pdo, int $limit, int $offset): array
{
        return ejecutarConsulta($pdo,
        "SELECT a.id, a.tag, a.name, b.name AS breed, a.weight_kg, a.status, a.gender, a.birth_date
         FROM animals a LEFT JOIN breeds b ON a.breed_id = b.id
         WHERE a.status != 'sacrificado'
         ORDER BY a.id DESC LIMIT :limit OFFSET :offset",
        [':limit' => $limit, ':offset' => $offset]
    );
}

function contarAnimales(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM animals"); return (int)($r[0]['total']??0); }

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

    $sql = "INSERT INTO animals (
                tag,
                name,
                breed_id,
                animal_type_id,
                weight_kg,
                status,
                gender
            )
            VALUES (
                :t,
                :n,
                :b,
                :at,
                :w,
                :s,
                :g
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
    ejecutarConsulta($pdo, "UPDATE animals SET tag=:t,name=:n,breed_id=:b,weight_kg=:w,status=:s,gender=:g WHERE id=:id",
        [':id'=>$id,':t'=>$tag,':n'=>$name,':b'=>$breedId,':w'=>$weight,':s'=>$status,':g'=>$gender]);
}
function eliminarAnimal(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM animals WHERE id=:id",[':id'=>$id]); }
function obtenerRazas(PDO $pdo): array { return ejecutarConsulta($pdo, "SELECT id, name FROM breeds ORDER BY name"); }
//_________------------------------------------
function obtenerTiposAnimales(PDO $pdo): array
{
    return ejecutarConsulta(
        $pdo,
        "SELECT id, name FROM animal_types ORDER BY name"
    );
}
// ──────────────────────────────────────────────
// LECHE
// ──────────────────────────────────────────────
function obtenerLechePaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT m.id, m.animal_id, a.name AS animal_name, m.production_date, m.quantity_liters
         FROM milk_production m JOIN animals a ON m.animal_id = a.id
         ORDER BY m.production_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarLeche(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM milk_production"); return (int)($r[0]['total']??0); }
function insertarLeche(PDO $pdo, int $animalId, string $fecha, float $litros): void {
    ejecutarConsulta($pdo, "INSERT INTO milk_production (animal_id,production_date,quantity_liters) VALUES (:a,:f,:l)",
        [':a'=>$animalId,':f'=>$fecha,':l'=>$litros]);
}
function actualizarLeche(PDO $pdo, int $id, int $animalId, string $fecha, float $litros): void {
    ejecutarConsulta($pdo, "UPDATE milk_production SET animal_id=:a,production_date=:f,quantity_liters=:l WHERE id=:id",
        [':id'=>$id,':a'=>$animalId,':f'=>$fecha,':l'=>$litros]);
}
function eliminarLeche(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM milk_production WHERE id=:id",[':id'=>$id]); }

// ──────────────────────────────────────────────
// SACRIFICIOS
// ──────────────────────────────────────────────
function obtenerSacrificiosRecientes(PDO $pdo, int $limite = 10): array {
    return ejecutarConsulta($pdo,
        "SELECT sr.slaughter_date, at.name AS animal_type, sr.quantity, a.tag AS animal_tag
         FROM slaughter_records sr
         LEFT JOIN animal_types at ON sr.animal_type_id = at.id
         LEFT JOIN animals a ON sr.animal_id = a.id
         ORDER BY sr.slaughter_date DESC LIMIT :limite",[':limite'=>$limite]);
}
function marcarAnimalSacrificado(PDO $pdo, int $animalId): void {
    ejecutarConsulta($pdo, "UPDATE animals SET status='sacrificado' WHERE id=:id",[':id'=>$animalId]);
}
//function registrarSacrificio(PDO $pdo, int $animalId, int $animalTypeId, int $cantidad = 1): void {
//    ejecutarConsulta($pdo, "INSERT INTO slaughter_records (animal_id,animal_type_id,slaughter_date,quantity) VALUES (:a,:t,CURRENT_DATE,:c)",
//        [':a'=>$animalId,':t'=>$animalTypeId,':c'=>$cantidad]);
//}
function registrarSacrificio(PDO $pdo, int $animalId, int $animalTypeId): void
{
    $pdo->beginTransaction();
    try {
        // Insertar registro de sacrificio
        $stmt = $pdo->prepare("INSERT INTO slaughter_records (animal_id, animal_type_id, slaughter_date, quantity) VALUES (:a, :t, CURRENT_DATE, 1)");
        $stmt->execute([':a' => $animalId, ':t' => $animalTypeId]);
        
        // Actualizar estado del animal
        $stmt2 = $pdo->prepare("UPDATE animals SET status = 'sacrificado' WHERE id = :id");
        $stmt2->execute([':id' => $animalId]);
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Error al registrar sacrificio: ' . $e->getMessage());
        throw $e;
    }
}

// ──────────────────────────────────────────────
// HUEVOS
// ──────────────────────────────────────────────
function obtenerHuevosPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo, "SELECT id, production_date, quantity FROM egg_production ORDER BY production_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarHuevos(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM egg_production"); return (int)($r[0]['total']??0); }
function insertarHuevos(PDO $pdo, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo, "INSERT INTO egg_production (production_date,quantity) VALUES (:f,:c)",[':f'=>$fecha,':c'=>$cantidad]);
}
function actualizarHuevos(PDO $pdo, int $id, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo, "UPDATE egg_production SET production_date=:f,quantity=:c WHERE id=:id",[':id'=>$id,':f'=>$fecha,':c'=>$cantidad]);
}
function eliminarHuevos(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM egg_production WHERE id=:id",[':id'=>$id]); }

// ──────────────────────────────────────────────
// GALLINAS
// ──────────────────────────────────────────────
function obtenerGallinasPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo, "SELECT id, inventory_date, quantity FROM chicken_inventory ORDER BY inventory_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarGallinas(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM chicken_inventory"); return (int)($r[0]['total']??0); }
function insertarGallinas(PDO $pdo, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo, "INSERT INTO chicken_inventory (inventory_date,quantity) VALUES (:f,:c)",[':f'=>$fecha,':c'=>$cantidad]);
}
function actualizarGallinas(PDO $pdo, int $id, string $fecha, int $cantidad): void {
    ejecutarConsulta($pdo, "UPDATE chicken_inventory SET inventory_date=:f,quantity=:c WHERE id=:id",[':id'=>$id,':f'=>$fecha,':c'=>$cantidad]);
}
function eliminarGallinas(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM chicken_inventory WHERE id=:id",[':id'=>$id]); }

// ──────────────────────────────────────────────
// ANUNCIOS (animal_ads)
// ──────────────────────────────────────────────
function obtenerAnunciosPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT aa.id, aa.ad_type, aa.quantity, aa.weight_kg, aa.price_per_unit, aa.status,
                at.name AS animal_type, b.name AS breed, u.username
         FROM animal_ads aa
         JOIN animal_types at ON aa.animal_type_id = at.id
         LEFT JOIN breeds b ON aa.breed_id = b.id
         JOIN users u ON aa.user_id = u.id
         ORDER BY aa.created_at DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarAnuncios(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM animal_ads"); return (int)($r[0]['total']??0); }
function eliminarAnuncio(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM animal_ads WHERE id=:id",[':id'=>$id]); }

// ──────────────────────────────────────────────
// TRANSACCIONES
// ──────────────────────────────────────────────
function obtenerTransaccionesPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT t.id, t.transaction_date, t.quantity, t.total_amount,
                at.name AS animal_type, s.username AS seller, b.username AS buyer
         FROM transactions t
         JOIN animal_types at ON t.animal_type_id = at.id
         JOIN users s ON t.seller_id = s.id
         JOIN users b ON t.buyer_id = b.id
         ORDER BY t.transaction_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarTransacciones(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM transactions"); return (int)($r[0]['total']??0); }
function eliminarTransaccion(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM transactions WHERE id=:id",[':id'=>$id]); }

// ──────────────────────────────────────────────
// ÓRDENES DE COMPRA / INSUMOS
// ──────────────────────────────────────────────
function obtenerOrdenesPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT po.id, po.order_date, po.expected_delivery, po.status, po.total_amount, s.name AS supplier
         FROM purchase_orders po JOIN suppliers s ON po.supplier_id = s.id
         ORDER BY po.order_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarOrdenes(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM purchase_orders"); return (int)($r[0]['total']??0); }
function eliminarOrden(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM purchase_orders WHERE id=:id",[':id'=>$id]); }

function obtenerProveedores(PDO $pdo): array { return ejecutarConsulta($pdo, "SELECT id, name FROM suppliers ORDER BY name"); }
function insertarOrden(PDO $pdo, int $supplierId, string $orderDate, string $expected, float $total, string $status = 'pendiente'): void {
    ejecutarConsulta($pdo, "INSERT INTO purchase_orders (supplier_id,order_date,expected_delivery,total_amount,status) VALUES (:s,:o,:e,:t,:st)",
        [':s'=>$supplierId,':o'=>$orderDate,':e'=>$expected,':t'=>$total,':st'=>$status]);
}

// ──────────────────────────────────────────────
// EMPLEADOS / NÓMINA
// ──────────────────────────────────────────────
function obtenerEmpleados(PDO $pdo): array { return ejecutarConsulta($pdo, "SELECT id, name, role, monthly_salary FROM employees ORDER BY name"); }
function insertarEmpleado(PDO $pdo, string $name, string $role, float $salary): void {
    ejecutarConsulta($pdo, "INSERT INTO employees (name,role,monthly_salary) VALUES (:n,:r,:s)",[':n'=>$name,':r'=>$role,':s'=>$salary]);
}
function actualizarEmpleado(PDO $pdo, int $id, string $name, string $role, float $salary): void {
    ejecutarConsulta($pdo, "UPDATE employees SET name=:n,role=:r,monthly_salary=:s WHERE id=:id",[':id'=>$id,':n'=>$name,':r'=>$role,':s'=>$salary]);
}
function eliminarEmpleado(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM employees WHERE id=:id",[':id'=>$id]); }
function obtenerUltimaNomina(PDO $pdo): array {
    return ejecutarConsulta($pdo,
        "SELECT p.id, e.name, e.role, p.gross_salary, p.deductions, p.net_pay, p.payment_date
         FROM payroll p JOIN employees e ON p.employee_id = e.id
         WHERE p.period = (SELECT MAX(period) FROM payroll) ORDER BY e.name");
}

// ──────────────────────────────────────────────
// ALIMENTACIÓN
// ──────────────────────────────────────────────
function obtenerAlimentacionPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT f.id, f.feeding_date, f.quantity_kg, a.name AS animal_name, a.tag, fc.name AS food_name
         FROM feeding f
         JOIN animals a ON f.animal_id = a.id
         JOIN food_catalog fc ON f.food_id = fc.id
         ORDER BY f.feeding_date DESC, a.name LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarAlimentacion(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM feeding"); return (int)($r[0]['total']??0); }
function obtenerAlimentos(PDO $pdo): array { return ejecutarConsulta($pdo, "SELECT id, name FROM food_catalog ORDER BY name"); }
function insertarAlimentacion(PDO $pdo, int $animalId, int $foodId, string $fecha, float $kg): void {
    ejecutarConsulta($pdo, "INSERT INTO feeding (animal_id,food_id,feeding_date,quantity_kg) VALUES (:a,:f,:d,:k)",
        [':a'=>$animalId,':f'=>$foodId,':d'=>$fecha,':k'=>$kg]);
}
function actualizarAlimentacion(PDO $pdo, int $id, int $animalId, int $foodId, string $fecha, float $kg): void {
    ejecutarConsulta($pdo, "UPDATE feeding SET animal_id=:a,food_id=:f,feeding_date=:d,quantity_kg=:k WHERE id=:id",
        [':id'=>$id,':a'=>$animalId,':f'=>$foodId,':d'=>$fecha,':k'=>$kg]);
}
function eliminarAlimentacion(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM feeding WHERE id=:id",[':id'=>$id]); }

// Catálogo de alimentos (CRUD completo)
function obtenerCatalogoAlimentos(PDO $pdo): array { return ejecutarConsulta($pdo, "SELECT id, name, food_type, cost_per_kg, protein_pct, stock_kg FROM food_catalog ORDER BY name"); }
function insertarAlimento(PDO $pdo, string $name, string $type, float $cost, float $protein, float $stock): void {
    ejecutarConsulta($pdo, "INSERT INTO food_catalog (name,food_type,cost_per_kg,protein_pct,stock_kg) VALUES (:n,:t,:c,:p,:s)",
        [':n'=>$name,':t'=>$type,':c'=>$cost,':p'=>$protein,':s'=>$stock]);
}
function actualizarAlimento(PDO $pdo, int $id, string $name, string $type, float $cost, float $protein, float $stock): void {
    ejecutarConsulta($pdo, "UPDATE food_catalog SET name=:n,food_type=:t,cost_per_kg=:c,protein_pct=:p,stock_kg=:s WHERE id=:id",
        [':id'=>$id,':n'=>$name,':t'=>$type,':c'=>$cost,':p'=>$protein,':s'=>$stock]);
}
function eliminarAlimento(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM food_catalog WHERE id=:id",[':id'=>$id]); }

// Eficiencia nutricional (solo visualización / eliminación)
function obtenerEficienciaPaginado(PDO $pdo, int $limit, int $offset): array {
    return ejecutarConsulta($pdo,
        "SELECT ne.id, ne.measurement_date, ne.feed_conversion_ratio, ne.weight_gain_kg, a.name AS animal_name
         FROM nutritional_efficiency ne JOIN animals a ON ne.animal_id = a.id
         ORDER BY ne.measurement_date DESC LIMIT :limit OFFSET :offset",
        [':limit'=>$limit,':offset'=>$offset]);
}
function contarEficiencia(PDO $pdo): int { $r = ejecutarConsulta($pdo, "SELECT COUNT(*) AS total FROM nutritional_efficiency"); return (int)($r[0]['total']??0); }
function eliminarEficiencia(PDO $pdo, int $id): void { ejecutarConsulta($pdo, "DELETE FROM nutritional_efficiency WHERE id=:id",[':id'=>$id]); }
//hola
function obtenerTodosLosTags(PDO $pdo): array
{
    $result = ejecutarConsulta($pdo, "SELECT tag FROM animals");
    return array_column($result, 'tag');
}