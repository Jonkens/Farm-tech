<?php
require_once __DIR__ . '/../conexion.php';

// Verificar conexión
function verificarConexion($pdo) {
    try {
        $pdo->query('SELECT 1');
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

echo "<h2>Prueba de conexión a PostgreSQL</h2>";

if (verificarConexion($pdo)) {
    echo "<p style='color:green;'>✅ Conexión exitosa a la base de datos</p>";
} else {
    echo "<p style='color:red;'>❌ Error en la conexión</p>";
}

// Prueba adicional: consultar tablas
try {
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
    $tablas = $stmt->fetchAll();

    echo "<h3>Tablas en la base de datos:</h3><ul>";
    foreach ($tablas as $tabla) {
        echo "<li>" . htmlspecialchars($tabla['table_name']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "<p style='color:red;'>Error al consultar tablas: " . $e->getMessage() . "</p>";
}
?>
