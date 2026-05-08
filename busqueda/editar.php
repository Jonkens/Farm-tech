<?php
$host = 'localhost';
$db   = 'ganado_db';
$user = 'postgres';
$pass = 'software';
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Error de conexión");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no válido");
}

// OBTENER DATOS
$stmt = $pdo->prepare("SELECT * FROM vacas WHERE id = ?");
$stmt->execute([$id]);
$vaca = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vaca) {
    die("Vaca no encontrada");
}

// GUARDAR CAMBIOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $tipo = trim($_POST['tipo']);
    $raza = trim($_POST['raza']);
    $peso = $_POST['peso'];
    $edad = $_POST['edad'];

    if ($nombre && $tipo && $raza) {

        $sql = "UPDATE vacas SET nombre=?, tipo=?, raza=?, peso=?, edad=? WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $tipo, $raza, $peso, $edad, $id]);

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Vaca</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-2xl mx-auto mt-10 bg-white rounded-2xl shadow-lg p-6">

<!-- HEADER -->
<div class="flex justify-between items-center mb-6">
<h2 class="text-xl font-bold text-gray-700">✏️ Editar Vaca</h2>

<a href="index.php" 
class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
← Volver
</a>
</div>

<form method="POST" class="space-y-4">

<div>
<label class="text-sm text-gray-500">Nombre</label>
<input type="text" name="nombre" value="<?= $vaca['nombre'] ?>"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-400">
</div>

<div>
<label class="text-sm text-gray-500">Tipo</label>
<input type="text" name="tipo" value="<?= $vaca['tipo'] ?>"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-400">
</div>

<div>
<label class="text-sm text-gray-500">Raza</label>
<input type="text" name="raza" value="<?= $vaca['raza'] ?>"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-400">
</div>

<div>
<label class="text-sm text-gray-500">Peso (kg)</label>
<input type="number" name="peso" value="<?= $vaca['peso'] ?>"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-400">
</div>

<div>
<label class="text-sm text-gray-500">Edad</label>
<input type="number" name="edad" value="<?= $vaca['edad'] ?>"
class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-slate-400">
</div>

<!-- BOTONES -->
<div class="flex gap-3 pt-4">

<button type="submit"
class="w-1/2 bg-slate-700 hover:bg-slate-800 text-white py-2 rounded-lg">
Guardar cambios
</button>

<a href="index.php"
class="w-1/2 text-center border py-2 rounded-lg hover:bg-gray-100">
Cancelar
</a>

</div>

</form>

</div>

</body>
</html>