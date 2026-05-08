
<?php

$host = 'localhost';
$db   = 'ganado_db';
$user = 'postgres';
$pass = 'software';
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    $pdo = null;
}

$ganadoLista = [];
$total = 0;
$domesticos = 0;
$silvestres = 0;
$peso_promedio = 0;
$ultimo_registro = 'Sin registros';

if ($pdo) {
    $stmt = $pdo->query("SELECT * FROM vacas ORDER BY id DESC");
    $ganadoLista = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = count($ganadoLista);

    if (!empty($ganadoLista)) {
        $fechas = array_column($ganadoLista, 'fecha_registro');
        rsort($fechas);
        $ultimo_registro = date('d/m/Y', strtotime($fechas[0]));
    }

    foreach ($ganadoLista as $vaca) {
        if (strtolower($vaca['tipo']) == 'domestica') {
            $domesticos++;
        } else {
            $silvestres++;
        }

        $peso_promedio += (float)$vaca['peso'];
    }

    $peso_promedio = $total > 0 ? round($peso_promedio / $total) : 0;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Regitro de Ganado</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto p-6">

    <!-- HEADER -->
    <header class="bg-gradient-to-r from-blue-950 to-slate-900 rounded-2xl shadow-lg px-8 py-6 flex justify-between items-center mb-8">
        <h1 class="text-white text-4xl font-bold flex items-center gap-3">
            🐄 Registro de Ganado
        </h1>

        <button 
            type="button"
            onclick="document.getElementById('modal').classList.remove('hidden')"
            class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-md font-semibold transition cursor-pointer">
            + Agregar 
        </button>
    </header>

    <!-- TARJETAS -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">

    <div onclick="filtrarTipo('')" class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 cursor-pointer hover:scale-105 transition">
        <div class="bg-green-100 p-4 rounded-full text-3xl">🐄</div>
        <div>
            <p class="text-gray-500">Total de ganado</p>
            <h2 class="text-3xl font-bold text-green-500"><?= $total ?></h2>
        </div>
    </div>

    <div onclick="filtrarTipo('domestica')" class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 cursor-pointer hover:scale-105 transition">
        <div class="bg-blue-100 p-4 rounded-full text-3xl">🐾</div>
        <div>
            <p class="text-gray-500">Domésticos</p>
            <h2 class="text-3xl font-bold text-blue-500"><?= $domesticos ?></h2>
        </div>
    </div>

    <div onclick="filtrarTipo('silvestre')" class="bg-white rounded-2xl shadow p-5 flex items-center gap-4 cursor-pointer hover:scale-105 transition">
        <div class="bg-yellow-100 p-4 rounded-full text-3xl">🌲</div>
        <div>
            <p class="text-gray-500">Silvestres</p>
            <h2 class="text-3xl font-bold text-yellow-500"><?= $silvestres ?></h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow p-5 flex items-center gap-4">
        <div class="bg-red-100 p-4 rounded-full text-3xl">📅</div>
        <div>
            <p class="text-gray-500">Último Registro</p>
            <h2 class="text-xl font-bold text-red-500"><?= $ultimo_registro ?></h2>
        </div>
    </div>

</div>




    <!-- TABLA -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">

        <!-- BUSCADOR -->
        <div class="mb-6 relative">
            <span class="absolute left-4 top-3 text-gray-400 text-xl">🔍</span>
            <input
                type="text"
                id="buscador"
                placeholder="Buscar por nombre, tipo o raza..."
                class="w-full border border-gray-300 rounded-xl px-12 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto rounded-2xl border border-gray-200">
            <table class="w-full bg-white">
                <thead class="bg-gradient-to-r from-blue-900 to-slate-800 text-white">
                    <tr>
                        <th class="py-4 text-center">Imagen</th>
                        <th class="py-4 text-left pl-4">Nombre</th>
                        <th class="py-4 text-left">Tipo</th>
                        <th class="py-4 text-left">Raza</th>
                        <th class="py-4 text-left">Edad</th>
                        <th class="py-4 text-left">Peso</th>
                        <th class="py-4 text-left">Fecha</th>
                        <th class="py-4 text-center">Acción</th>
                    </tr>
                </thead>

                <tbody id="tablaGanado" class="divide-y divide-gray-200 text-sm text-gray-700">
                <?php foreach ($ganadoLista as $vaca): ?>
                    <tr class="hover:bg-gray-50 transition" 
    data-tipo="<?= trim(strtolower($vaca['tipo'])) ?>">

                        <td class="py-3 text-center">
                            <a href="ganado.php?id=<?= $vaca['id'] ?>">
                                <img src="<?= htmlspecialchars($vaca['imagen']) ?>" class="w-16 h-16 object-cover rounded-lg border mx-auto">
                            </a>
                        </td>

                        <td class="pl-4 font-semibold text-gray-800">
                            <?= $vaca['nombre'] ?>
                        </td>

                        <td>
                            <span class="px-3 py-1 rounded-full text-xs font-medium <?= strtolower($vaca['tipo']) == 'domestica' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?= ucfirst($vaca['tipo']) ?>
                            </span>
                        </td>

                        <td><?= $vaca['raza'] ?></td>
                        <td><?= $vaca['edad'] ?> años</td>
                        <td><?= $vaca['peso'] ?> Kg</td>

                        

                        <td><?= !empty($vaca['fecha_registro']) ? date('d/m/Y', strtotime($vaca['fecha_registro'])) : 'Sin fecha' ?></td>

                        <td>
                            <div class="flex justify-center gap-2">
                                <a href="editar.php?id=<?= $vaca['id'] ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-medium transition">
                                    ✏ Editar
                                </a>

                                <a href="eliminar.php?id=<?= $vaca['id'] ?>" 
   onclick="return confirm('¿Seguro que deseas eliminar este registro?')"
   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-medium transition">
    🗑 Eliminar
</a>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="flex justify-between items-center mt-6">
            <p class="text-gray-500 text-sm">
                Mostrando 1 a <?= count($ganadoLista) ?> de <?= $total ?> registros
            </p>

            <div class="flex gap-2">
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">◀</button>
                <button class="px-4 py-2 bg-green-500 text-white rounded-lg">1</button>
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">▶</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
    <div class="bg-white w-96 rounded-2xl shadow-xl p-6 relative">

        <button 
            type="button"
            onclick="document.getElementById('modal').classList.add('hidden')"
            class="absolute top-3 right-4 text-gray-500 text-2xl hover:text-red-500">
            &times;
        </button>

        <h2 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">
            Registrar ganado
        </h2>

        <form method="POST" action="guardar.php" enctype="multipart/form-data">
            <input type="text" name="nombre" placeholder="Nombre" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" name="tipo" placeholder="Tipo" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" name="raza" placeholder="Raza" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" name="edad" placeholder="Edad" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" name="peso" placeholder="Peso" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" name="ubicacion" placeholder="Ubicación" class="w-full border rounded-lg px-3 py-2 mb-3">

            <label class="block border rounded-lg py-2 px-3 mb-4 cursor-pointer text-center hover:bg-gray-50">
                📁 Seleccionar imagen
                <input type="file" name="imagen" class="hidden">
            </label>

            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('modal').classList.add('hidden')" class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">
                    Cancelar
                </button>

                <button type="submit" class="w-1/2 bg-slate-800 text-white py-2 rounded-lg hover:bg-slate-900">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const buscador = document.getElementById("buscador");

    buscador.addEventListener("keyup", function(){
        let texto = this.value.toLowerCase();
        let filas = document.querySelectorAll("#tablaGanado tr");

        filas.forEach(function(fila){
            let nombre = fila.children[1].textContent.toLowerCase();
            let tipo = fila.children[2].textContent.toLowerCase();
            let raza = fila.children[3].textContent.toLowerCase();

            fila.style.display = (
                nombre.includes(texto) ||
                tipo.includes(texto) ||
                raza.includes(texto)
            ) ? "" : "none";
        });
    });
});


function filtrarTipo(tipo) {
    let filas = document.querySelectorAll("#tablaGanado tr");

    filas.forEach(function(fila) {
        let tipoFila = fila.children[2].innerText
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .trim();

        let filtro = tipo
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

        if (filtro === "todos") {
            fila.style.display = "";
        } 
        else if (tipoFila.includes(filtro)) {
            fila.style.display = "";
        } 
        else {
            fila.style.display = "none";
        }
    });
}
</script>

</body>
</html>
