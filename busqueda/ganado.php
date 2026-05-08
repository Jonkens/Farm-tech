<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$db = "ganado_db";
$user = "postgres";
$pass = "software";
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// VALIDAR ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no recibido");
}

// CONSULTA
$stmt = $pdo->prepare("SELECT * FROM vacas WHERE id = ?");
$stmt->execute([$id]);
$vaca = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vaca) {
    die("No se encontró la vaca");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- CONTENEDOR -->
<div class="max-w-4xl mx-auto mt-10 bg-white rounded-2xl shadow-lg p-6">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
<h2 class="text-2xl font-bold text-gray-700">🐄 Detalle del Vacuno</h2>

<a href="index.php" 
class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800">
← Volver
</a>
</div>

<!-- GRID -->
<div class="grid grid-cols-2 gap-6">

<!-- IMAGEN -->
<div>
<img src="<?= htmlspecialchars($vaca['imagen']) ?>" 
class="w-full h-64 object-cover rounded-xl shadow">
</div>

<!-- INFO -->
<div class="space-y-3 text-gray-700">

<p><span class="font-semibold text-gray-500">Nombre:</span> <?= $vaca['nombre'] ?></p>

<p><span class="font-semibold text-gray-500">Tipo:</span> <?= $vaca['tipo'] ?></p>

<p><span class="font-semibold text-gray-500">Raza:</span> <?= $vaca['raza'] ?></p>

<p><span class="font-semibold text-gray-500">Edad:</span> <?= $vaca['edad'] ?> años</p>

<p><span class="font-semibold text-gray-500">Peso:</span> <?= $vaca['peso'] ?> kg</p>

<p><span class="font-semibold text-gray-500">Ubicación:</span> <?= $vaca['ubicacion'] ?></p>

<!-- ESTADO BONITO -->
<p>
</span>
</p>

</div>

</div>

</div>

</body>
</html>