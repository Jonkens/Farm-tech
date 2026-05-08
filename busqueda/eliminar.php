<?php
$host = 'localhost';
$db   = 'ganado_db';
$user = 'postgres';
$pass = 'software';
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Error BD: " . $e->getMessage());
}

// VALIDAR ID
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit();
}

// OBTENER IMAGEN
$stmt = $pdo->prepare("SELECT imagen FROM vacas WHERE id = ?");
$stmt->execute([$id]);
$vaca = $stmt->fetch(PDO::FETCH_ASSOC);

if ($vaca) {

    // ELIMINAR IMAGEN (si existe)
    if (!empty($vaca['imagen']) && file_exists($vaca['imagen'])) {
        unlink($vaca['imagen']);
    }

    // ELIMINAR REGISTRO
    $stmt = $pdo->prepare("DELETE FROM vacas WHERE id = ?");
    $stmt->execute([$id]);
}

// REDIRECCIÓN
header("Location: index.php");
exit();