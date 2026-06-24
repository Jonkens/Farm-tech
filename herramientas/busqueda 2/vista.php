<?php
/**
 * @var int $total
 * @var array $statsPorTipo
 * @var array $animales
 * @var int $totalPages
 * @var int $page
 * @var int $totalAnimals
 * @var array $tiposAnimal
 * @var array|null $detalle
 * @var string $edadDetalle
 * @var array|null $animalEdit
 * @var array $razasEdit
 * @var int $ultimoRegistro
 * @var array $facilidades
 */

if (!isset($detalle) && !isset($animalEdit)) {
    $modo = 'listado';
} elseif (isset($detalle)) {
    $modo = 'detalle';
} else {
    $modo = 'edicion';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $modo === 'listado' ? 'Registro de Animales' : ($modo === 'detalle' ? 'Detalle de Animal' : 'Editar Animal') ?> - Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #1a4d2a;
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                              repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-size: 30px 30px, 12px 12px;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .card-ganadero {
            background: #fff9ef;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2d4b5;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-ganadero:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.25);
        }
        .btn-primary {
            background-color: #2d6a4f;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #1f4d38;
        }
        .btn-secondary {
            background-color: #b87c4f;
        }
        .btn-secondary:hover {
            background-color: #9a623b;
        }
        .btn-outline {
            border: 1px solid #d4a373;
            color: #5a3e1b;
        }
        .btn-outline:hover {
            background-color: #fef5e6;
        }
        .tabla-ganado thead {
            background: #2d6a4f;
            color: #fef5e6;
        }
        .tabla-ganado tbody tr:hover {
            background-color: #fef1df;
        }
        .modal-content {
            background: #fffef7;
            border-radius: 1.25rem;
            border: 1px solid #ecdbaa;
        }
        .badge-estado {
            background: #e9dfc7;
            color: #5a3e1b;
            border-radius: 2rem;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 500;
        }
        .stat-card {
            background: #fef5e6;
            border-radius: 1rem;
            padding: 0.75rem;
            border: 1px solid #ecdbaa;
            transition: all 0.2s;
        }
        .stat-card:hover {
            transform: scale(1.02);
            background: #fff0df;
        }
        .filter-active {
            background-color: #2d6a4f !important;
            color: white !important;
        }
    </style>
</head>
<body>

<div class="max-w-7xl mx-auto">

<?php if ($modo === 'listado'): ?>
    <!-- ==================== LISTADO PRINCIPAL ==================== -->
    <div class="card-ganadero p-6 mb-8">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-[#4b2e1a] flex items-center gap-2">
                     Registro de Animales
                </h1>
                <p class="text-[#7a5a3a] text-sm mt-1">Gestión completa del hato</p>
            </div>
            <button type="button" onclick="abrirModal()" class="btn-primary text-white px-6 py-2 rounded-xl shadow-md font-semibold transition cursor-pointer">
                + Agregar animal
            </button>
        </div>
    </div>

    <!-- Tarjetas estadísticas (sin emojis) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-7 gap-3 mb-8">
        <div onclick="filtrarTipo(0)" class="stat-card cursor-pointer text-center" id="filtro-todos">
            <div class="text-2xl"></div>
            <p class="text-gray-600 text-xs">Total animales</p>
            <h2 class="text-xl font-bold text-[#2d6a4f]"><?= $total ?></h2>
        </div>
        <?php foreach ($statsPorTipo as $tipo): ?>
        <div onclick="filtrarTipo('<?= htmlspecialchars($tipo['name']) ?>')" class="stat-card cursor-pointer text-center" data-tipo-nombre="<?= $tipo['name'] ?>">
            <!-- Eliminado el emoji: mostramos el nombre del tipo en su lugar -->
            <div class="text-sm font-semibold text-[#4b2e1a]"><?= htmlspecialchars($tipo['name']) ?></div>
            <p class="text-gray-600 text-xs">Cantidad</p>
            <h2 class="text-xl font-bold text-[#b87c4f]"><?= $tipo['count'] ?></h2>
        </div>
        <?php endforeach; ?>
        <div class="stat-card text-center">
            <div class="text-2xl"></div>
            <p class="text-gray-600 text-xs">Último registro</p>
            <p class="text-sm font-bold text-[#8b5a2b] truncate max-w-[120px]"><?= $ultimoRegistro ?></p>
        </div>
    </div>

    <!-- Tabla con buscador -->
    <div class="card-ganadero p-6">
        <div class="mb-4 relative">
            <span class="absolute left-4 top-3 text-[#b87c4f] text-xl"></span>
            <input type="text" id="buscador" placeholder="Buscar por nombre, raza o tipo..."
                class="w-full border border-[#ecdbaa] rounded-xl px-12 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#2d6a4f] bg-[#fffef7]">
        </div>
        <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
            <table class="w-full bg-white tabla-ganado">
                <thead>
                    <tr class="bg-[#2d6a4f] text-[#fef5e6]">
                        <th class="py-3 text-left pl-4">Arete</th>
                        <th class="py-3 text-left">Nombre</th>
                        <th class="py-3 text-left">Tipo</th>
                        <th class="py-3 text-left">Raza</th>
                        <th class="py-3 text-left">Ubicación</th>
                        <th class="py-3 text-left">Edad</th>
                        <th class="py-3 text-left">Peso (kg)</th>
                        <th class="py-3 text-left">Género</th>
                        <th class="py-3 text-left">Estado</th>
                        <th class="py-3 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody id="tablaAnimales" class="divide-y divide-[#f0e5d2] text-sm text-gray-700">
                <?php foreach ($animales as $animal): ?>
                    <tr class="hover:bg-[#fef1df] transition" data-tipo="<?= htmlspecialchars($animal['animal_type_name']) ?>">
                        <td class="pl-4 font-mono"><?= htmlspecialchars($animal['tag']) ?></td>
                        <td class="font-semibold">
                            <a href="index.php?view=<?= $animal['id'] ?>" class="text-[#2d6a4f] hover:underline"><?= htmlspecialchars($animal['name']) ?></a>
                        </td>
                        <td><span class="badge-estado"><?= htmlspecialchars($animal['animal_type_name']) ?></span></td>
                        <td><?= htmlspecialchars($animal['breed_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($animal['facility_name'] ?? '—') ?></td>
                        <td><?= calcularEdad($animal['birth_date']) ?></td>
                        <td><?= $animal['weight_kg'] !== null ? number_format((float)$animal['weight_kg'], 2) : '—' ?></td>
                        <td><?= $animal['gender'] == 'M' ? 'Macho' : 'Hembra' ?></td>
                        <td><?= ucfirst($animal['status']) ?></td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <a href="index.php?edit=<?= $animal['id'] ?>" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-3 py-1 rounded-lg text-xs font-medium transition"> Editar</a>
                                <a href="index.php?delete=<?= $animal['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este registro?')" class="bg-red-700 hover:bg-red-800 text-white px-3 py-1 rounded-lg text-xs font-medium transition"> Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center items-center mt-6 gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page-1 ?>" class="px-4 py-2 border border-[#d4a373] rounded-lg hover:bg-[#fef5e6] transition">Anterior</a>
            <?php else: ?>
                <span class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">◀ Anterior</span>
            <?php endif; ?>
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <?php if ($i == $page): ?>
                    <span class="px-4 py-2 bg-[#2d6a4f] text-white rounded-lg"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="px-4 py-2 border border-[#d4a373] rounded-lg hover:bg-[#fef5e6] transition"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>" class="px-4 py-2 border border-[#d4a373] rounded-lg hover:bg-[#fef5e6] transition">Siguiente </a>
            <?php else: ?>
                <span class="px-4 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed">Siguiente </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="text-center text-[#8b6946] text-sm mt-3">
            Mostrando <?= count($animales) ?> de <?= $totalAnimals ?> registros
        </div>
    </div>

    <!-- Modal de creación (con diseño acorde) -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
        <div class="modal-content w-full max-w-lg max-h-[90vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4 border-b border-[#ecdbaa] pb-2">
                <h2 class="text-xl font-bold text-[#4b2e1a]">Registrar nuevo animal</h2>
                <button type="button" onclick="cerrarModal()" class="text-gray-400 hover:text-red-600 text-2xl">&times;</button>
            </div>
            <form method="POST" action="index.php" id="createForm">
                <input type="hidden" name="action" value="create">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Género *</label>
                        <select name="gender" id="gender" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                            <option value="">Seleccione género</option>
                            <option value="M">Macho</option>
                            <option value="F">Hembra</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Código / Arete *</label>
                        <input type="text" name="tag" id="tag" required readonly class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-gray-100">
                        <p class="text-xs text-gray-500 mt-1">Se genera automáticamente según el género.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de animal *</label>
                        <div class="flex gap-2">
                            <select name="animal_type_id" id="tipo_animal" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]" onchange="cargarRazas()">
                                <option value="">Seleccione tipo de animal</option>
                                <?php foreach ($tiposAnimal as $tipo): ?>
                                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" onclick="abrirModalTipo()" class="btn-secondary text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap">+ Nuevo tipo</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Raza *</label>
                        <div class="flex gap-2">
                            <select name="breed_id" id="raza_animal" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                                <option value="">Primero seleccione un tipo</option>
                            </select>
                            <button type="button" onclick="abrirModalRaza()" class="btn-secondary text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap">+ Nueva raza</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación (Establo, Corral, Galpón...)</label>
                        <div class="flex gap-2">
                            <select name="facility_id" id="facility_id" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                                <option value="">Seleccione una ubicación</option>
                                <?php foreach ($facilidades as $fac): ?>
                                    <option value="<?= $fac['id'] ?>"><?= htmlspecialchars($fac['name']) . ' (' . htmlspecialchars($fac['facility_type']) . ')' ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" onclick="abrirModalFacilidad()" class="btn-secondary text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap">+ Nueva ubicación</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre *</label>
                        <input type="text" name="name" placeholder="Nombre del animal" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peso (kg) *</label>
                        <input type="number" step="0.01" name="weight_kg" placeholder="Ej: 350.50" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                        <select name="status" id="status" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]"></select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de nacimiento *</label>
                        <input type="date" name="birth_date" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de ingreso *</label>
                        <input type="date" name="entry_date" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notas</label>
                        <textarea name="notes" rows="3" placeholder="Notas adicionales..." class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="cerrarModal()" class="w-1/2 border border-[#d4a373] py-2 rounded-lg hover:bg-[#fef5e6] transition">Cancelar</button>
                    <button type="submit" class="w-1/2 bg-[#2d6a4f] text-white py-2 rounded-lg hover:bg-[#1f4d38] transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales auxiliares (tipo, raza, facilidad) con el mismo estilo -->
    <div id="modalTipo" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
        <div class="modal-content w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-[#4b2e1a] mb-4">Nuevo tipo de animal</h3>
            <input type="text" id="nuevoTipoNombre" placeholder="Ej: Bovino, Ovino, Caprino..." class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-4 bg-[#fffef7]">
            <div class="flex gap-3">
                <button onclick="cerrarModalTipo()" class="w-1/2 border border-[#d4a373] py-2 rounded-lg hover:bg-[#fef5e6]">Cancelar</button>
                <button onclick="guardarNuevoTipo()" class="w-1/2 bg-[#2d6a4f] text-white py-2 rounded-lg hover:bg-[#1f4d38]">Guardar</button>
            </div>
        </div>
    </div>

    <div id="modalRaza" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
        <div class="modal-content w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-[#4b2e1a] mb-4">Nueva raza</h3>
            <select id="razaTipoId" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-3 bg-[#fffef7]">
                <option value="">Seleccione el tipo de animal</option>
                <?php foreach ($tiposAnimal as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="nuevaRazaNombre" placeholder="Nombre de la raza" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-4 bg-[#fffef7]">
            <div class="flex gap-3">
                <button onclick="cerrarModalRaza()" class="w-1/2 border border-[#d4a373] py-2 rounded-lg hover:bg-[#fef5e6]">Cancelar</button>
                <button onclick="guardarNuevaRaza()" class="w-1/2 bg-[#2d6a4f] text-white py-2 rounded-lg hover:bg-[#1f4d38]">Guardar</button>
            </div>
        </div>
    </div>

    <div id="modalFacilidad" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-4">
        <div class="modal-content w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-[#4b2e1a] mb-4">Nueva ubicación</h3>
            <input type="text" id="nuevaFacilidadNombre" placeholder="Nombre (ej. Establo Norte)" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-3 bg-[#fffef7]">
            <input type="text" id="nuevaFacilidadTipo" placeholder="Tipo (establo, corral, galpón, potrero...)" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-3 bg-[#fffef7]">
            <input type="number" id="nuevaFacilidadCapacidad" placeholder="Capacidad (opcional)" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-3 bg-[#fffef7]">
            <input type="text" id="nuevaFacilidadUbicacion" placeholder="Ubicación (opcional)" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 mb-4 bg-[#fffef7]">
            <div class="flex gap-3">
                <button onclick="cerrarModalFacilidad()" class="w-1/2 border border-[#d4a373] py-2 rounded-lg hover:bg-[#fef5e6]">Cancelar</button>
                <button onclick="guardarNuevaFacilidad()" class="w-1/2 bg-[#2d6a4f] text-white py-2 rounded-lg hover:bg-[#1f4d38]">Guardar</button>
            </div>
        </div>
    </div>

    <script>
        // Estados para el modal
        const commonStatusesModal = [
            { value: 'activo', label: 'Activo' },
            { value: 'enfermo', label: 'Enfermo' },
            { value: 'sacrificado', label: 'Sacrificado' },
            { value: 'vendido', label: 'Vendido' },
            { value: 'muerto', label: 'Muerto' },
            { value: 'becerro', label: 'Becerro' }
        ];
        const femaleSpecificModal = [
            { value: 'produccion', label: 'Producción' },
            { value: 'gestacion', label: 'Gestación' },
            { value: 'preñada', label: 'Preñada' }
        ];
        const maleSpecificModal = [
            { value: 'reproduccion', label: 'Reproducción' }
        ];

        function updateStatusOptionsModal(gender) {
            let options = [...commonStatusesModal];
            if (gender === 'F') options = [...femaleSpecificModal, ...options];
            else if (gender === 'M') options = [...maleSpecificModal, ...options];
            const statusSelect = document.getElementById('status');
            statusSelect.innerHTML = '';
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.label;
                statusSelect.appendChild(option);
            });
        }

        async function fetchNextTag(gender) {
            if (!gender) return;
            try {
                const response = await fetch(`index.php?get_next_tag=1&gender=${gender}`);
                const data = await response.json();
                if (data.tag) document.getElementById('tag').value = data.tag;
            } catch (error) { console.error(error); }
        }

        document.getElementById('gender').addEventListener('change', function() {
            const gender = this.value;
            updateStatusOptionsModal(gender);
            if (gender) fetchNextTag(gender);
            else document.getElementById('tag').value = '';
        });

        function cargarRazas() {
            var tipoId = document.getElementById('tipo_animal').value;
            var razaSelect = document.getElementById('raza_animal');
            if (!tipoId) { razaSelect.innerHTML = '<option value="">Primero seleccione tipo</option>'; return; }
            fetch('index.php?get_razas=1&tipo=' + tipoId)
                .then(response => response.json())
                .then(data => {
                    razaSelect.innerHTML = '';
                    if (data.length === 0) razaSelect.innerHTML = '<option value="">No hay razas disponibles</option>';
                    else data.forEach(raza => {
                        var option = document.createElement('option');
                        option.value = raza.id;
                        option.textContent = raza.name;
                        razaSelect.appendChild(option);
                    });
                })
                .catch(err => { console.error(err); razaSelect.innerHTML = '<option value="">Error al cargar razas</option>'; });
        }

        function abrirModal() {
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('createForm').reset();
            document.getElementById('tag').value = '';
            document.getElementById('raza_animal').innerHTML = '<option value="">Primero seleccione tipo</option>';
            updateStatusOptionsModal('');
        }
        function cerrarModal() { document.getElementById('modal').classList.add('hidden'); }

        // Búsqueda en tiempo real
        document.addEventListener("DOMContentLoaded", function(){
            const buscador = document.getElementById("buscador");
            if(buscador){
                buscador.addEventListener("keyup", function(){
                    let texto = this.value.toLowerCase();
                    let filas = document.querySelectorAll("#tablaAnimales tr");
                    filas.forEach(function(fila){
                        let nombre = fila.children[1]?.textContent.toLowerCase() || '';
                        let tipo = fila.children[2]?.textContent.toLowerCase() || '';
                        let raza = fila.children[3]?.textContent.toLowerCase() || '';
                        fila.style.display = (nombre.includes(texto) || tipo.includes(texto) || raza.includes(texto)) ? "" : "none";
                    });
                });
            }
        });

        function filtrarTipo(tipoNombre) {
            let filas = document.querySelectorAll("#tablaAnimales tr");
            // resetear estilo activo en tarjetas
            document.querySelectorAll('.stat-card').forEach(card => card.classList.remove('filter-active'));
            if (tipoNombre === 0 || tipoNombre === "0") {
                filas.forEach(fila => fila.style.display = "");
                document.getElementById('filtro-todos').classList.add('filter-active');
                return;
            }
            filas.forEach(function(fila){
                let tipoFila = fila.getAttribute('data-tipo') || "";
                fila.style.display = (tipoFila === tipoNombre) ? "" : "none";
            });
            // resaltar tarjeta
            let targetCard = Array.from(document.querySelectorAll('.stat-card')).find(card => card.getAttribute('data-tipo-nombre') === tipoNombre);
            if(targetCard) targetCard.classList.add('filter-active');
        }

        // Funciones para modales auxiliares
        function abrirModalTipo() { document.getElementById('modalTipo').classList.remove('hidden'); }
        function cerrarModalTipo() { document.getElementById('modalTipo').classList.add('hidden'); document.getElementById('nuevoTipoNombre').value = ''; }
        async function guardarNuevoTipo() {
            const nombre = document.getElementById('nuevoTipoNombre').value.trim();
            if (!nombre) { alert('Debe ingresar un nombre'); return; }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_tipo');
                formData.append('nombre', nombre);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) { alert(data.error); return; }
                const selectTipo = document.getElementById('tipo_animal');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                selectTipo.appendChild(option);
                selectTipo.value = data.id;
                cargarRazas();
                cerrarModalTipo();
            } catch (err) { console.error(err); alert('Error al guardar el tipo'); }
        }

        function abrirModalRaza() {
            const selectTipoRaza = document.getElementById('razaTipoId');
            const currentTipos = document.querySelectorAll('#tipo_animal option');
            selectTipoRaza.innerHTML = '<option value="">Seleccione el tipo de animal</option>';
            currentTipos.forEach(opt => {
                if (opt.value) {
                    const newOpt = document.createElement('option');
                    newOpt.value = opt.value;
                    newOpt.textContent = opt.textContent;
                    selectTipoRaza.appendChild(newOpt);
                }
            });
            document.getElementById('modalRaza').classList.remove('hidden');
        }
        function cerrarModalRaza() { document.getElementById('modalRaza').classList.add('hidden'); document.getElementById('nuevaRazaNombre').value = ''; document.getElementById('razaTipoId').value = ''; }
        async function guardarNuevaRaza() {
            const tipoId = document.getElementById('razaTipoId').value;
            const nombre = document.getElementById('nuevaRazaNombre').value.trim();
            if (!tipoId || !nombre) { alert('Debe seleccionar un tipo y escribir el nombre de la raza'); return; }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_raza');
                formData.append('animal_type_id', tipoId);
                formData.append('nombre', nombre);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) { alert(data.error); return; }
                const selectRaza = document.getElementById('raza_animal');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                selectRaza.appendChild(option);
                selectRaza.value = data.id;
                cerrarModalRaza();
            } catch (err) { console.error(err); alert('Error al guardar la raza'); }
        }

        function abrirModalFacilidad() { document.getElementById('modalFacilidad').classList.remove('hidden'); }
        function cerrarModalFacilidad() { document.getElementById('modalFacilidad').classList.add('hidden'); document.getElementById('nuevaFacilidadNombre').value = ''; document.getElementById('nuevaFacilidadTipo').value = ''; document.getElementById('nuevaFacilidadCapacidad').value = ''; document.getElementById('nuevaFacilidadUbicacion').value = ''; }
        async function guardarNuevaFacilidad() {
            const nombre = document.getElementById('nuevaFacilidadNombre').value.trim();
            const tipo = document.getElementById('nuevaFacilidadTipo').value.trim();
            const capacidad = document.getElementById('nuevaFacilidadCapacidad').value;
            const ubicacion = document.getElementById('nuevaFacilidadUbicacion').value.trim();
            if (!nombre || !tipo) { alert('Debe ingresar nombre y tipo'); return; }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_facilidad');
                formData.append('nombre', nombre);
                formData.append('facility_type', tipo);
                if (capacidad) formData.append('capacity', capacidad);
                if (ubicacion) formData.append('location', ubicacion);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) { alert(data.error); return; }
                const selectFac = document.getElementById('facility_id');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = `${data.name} (${data.facility_type})`;
                selectFac.appendChild(option);
                selectFac.value = data.id;
                cerrarModalFacilidad();
            } catch (err) { console.error(err); alert('Error al guardar la ubicación'); }
        }
    </script>

<?php elseif ($modo === 'detalle'): ?>
    <!-- ==================== VISTA DETALLE ==================== -->
    <div class="max-w-4xl mx-auto">
        <div class="card-ganadero p-6">
            <div class="flex justify-between items-center mb-6 border-b border-[#ecdbaa] pb-3">
                <h2 class="text-2xl font-bold text-[#4b2e1a]"> Detalle del Animal</h2>
                <a href="index.php" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-4 py-2 rounded-lg transition">← Volver</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <p><span class="font-semibold text-gray-700">ID/Arete:</span> <span class="font-mono"><?= htmlspecialchars($detalle['tag']) ?></span></p>
                    <p><span class="font-semibold text-gray-700">Nombre:</span> <?= htmlspecialchars($detalle['name']) ?></p>
                    <p><span class="font-semibold text-gray-700">Tipo:</span> <?= htmlspecialchars($detalle['animal_type_name']) ?></p>
                    <p><span class="font-semibold text-gray-700">Raza:</span> <?= htmlspecialchars($detalle['breed_name'] ?? '-') ?></p>
                    <p><span class="font-semibold text-gray-700">Ubicación:</span> <?= htmlspecialchars($detalle['facility_name'] ?? '—') ?></p>
                </div>
                <div class="space-y-2">
                    <p><span class="font-semibold text-gray-700">Fecha nacimiento:</span> <?= formatearFecha($detalle['birth_date'] ?? null) ?></p>
                    <p><span class="font-semibold text-gray-700">Edad:</span> <?= $edadDetalle ?></p>
                    <p><span class="font-semibold text-gray-700">Peso:</span> <?= $detalle['weight_kg'] !== null ? number_format((float)$detalle['weight_kg'], 2) . ' kg' : '—' ?></p>
                    <p><span class="font-semibold text-gray-700">Género:</span> <?= $detalle['gender'] == 'M' ? 'Macho' : 'Hembra' ?></p>
                    <p><span class="font-semibold text-gray-700">Estado:</span> <span class="badge-estado"><?= ucfirst($detalle['status']) ?></span></p>
                    <p><span class="font-semibold text-gray-700">Notas:</span> <?= nl2br(htmlspecialchars($detalle['notes'] ?: '—')) ?></p>
                </div>
            </div>
        </div>
    </div>

<?php elseif ($modo === 'edicion'): ?>
    <!-- ==================== VISTA EDICIÓN ==================== -->
    <div class="max-w-2xl mx-auto">
        <div class="card-ganadero p-6">
            <div class="flex justify-between items-center mb-6 border-b border-[#ecdbaa] pb-3">
                <h2 class="text-xl font-bold text-[#4b2e1a]"> Editar Animal</h2>
                <a href="index.php" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-4 py-2 rounded-lg transition">← Volver</a>
            </div>
            <form method="POST" class="space-y-4" id="editForm">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $animalEdit['id'] ?>">
                <div>
                    <label class="text-sm font-medium text-gray-700">Género *</label>
                    <select name="gender" id="edit_gender" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                        <option value="M" <?= $animalEdit['gender'] == 'M' ? 'selected' : '' ?>>Macho</option>
                        <option value="F" <?= $animalEdit['gender'] == 'F' ? 'selected' : '' ?>>Hembra</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Código / Arete *</label>
                    <input type="text" name="tag" required value="<?= htmlspecialchars($animalEdit['tag']) ?>" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                    <p class="text-xs text-gray-500 mt-1">Puede modificarlo, pero no debe repetirse.</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Tipo de animal *</label>
                    <select name="animal_type_id" id="edit_tipo" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]" onchange="cargarRazasEdit()">
                        <option value="">Seleccione</option>
                        <?php foreach ($tiposAnimal as $tipo): ?>
                            <option value="<?= $tipo['id'] ?>" <?= $tipo['id'] == $animalEdit['animal_type_id'] ? 'selected' : '' ?>><?= htmlspecialchars($tipo['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Raza *</label>
                    <select name="breed_id" id="edit_raza" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                        <?php foreach ($razasEdit as $raza): ?>
                            <option value="<?= $raza['id'] ?>" <?= $raza['id'] == $animalEdit['breed_id'] ? 'selected' : '' ?>><?= htmlspecialchars($raza['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Ubicación (Establo, Corral...)</label>
                    <select name="facility_id" id="edit_facility_id" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                        <option value="">Seleccione una ubicación</option>
                        <?php foreach ($facilidades as $fac): ?>
                            <option value="<?= $fac['id'] ?>" <?= ($fac['id'] == ($animalEdit['facility_id'] ?? null)) ? 'selected' : '' ?>><?= htmlspecialchars($fac['name']) . ' (' . htmlspecialchars($fac['facility_type']) . ')' ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="abrirModalFacilidadEdit()" class="mt-2 btn-secondary text-white px-3 py-1 rounded-lg text-sm">+ Nueva ubicación</button>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Nombre *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($animalEdit['name']) ?>" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Peso (kg) *</label>
                    <input type="number" step="0.01" name="weight_kg" required value="<?= htmlspecialchars($animalEdit['weight_kg'] ?? '') ?>" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Estado</label>
                    <select name="status" id="edit_status" required class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]"></select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Fecha nacimiento *</label>
                    <input type="date" name="birth_date" required value="<?= $animalEdit['birth_date'] ?>" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Notas</label>
                    <textarea name="notes" rows="3" class="w-full border border-[#ecdbaa] rounded-lg px-3 py-2 bg-[#fffef7]"><?= htmlspecialchars($animalEdit['notes'] ?? '') ?></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="w-1/2 bg-[#2d6a4f] hover:bg-[#1f4d38] text-white py-2 rounded-lg transition">Guardar cambios</button>
                    <a href="index.php" class="w-1/2 text-center border border-[#d4a373] py-2 rounded-lg hover:bg-[#fef5e6] transition">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const commonStatuses = [
            { value: 'activo', label: 'Activo' },
            { value: 'enfermo', label: 'Enfermo' },
            { value: 'sacrificado', label: 'Sacrificado' },
            { value: 'vendido', label: 'Vendido' },
            { value: 'muerto', label: 'Muerto' },
            { value: 'becerro', label: 'Becerro' }
        ];
        const femaleSpecific = [
            { value: 'produccion', label: 'Producción' },
            { value: 'gestacion', label: 'Gestación' },
            { value: 'preñada', label: 'Preñada' }
        ];
        const maleSpecific = [
            { value: 'reproduccion', label: 'Reproducción' }
        ];

        function updateStatusOptions(gender, selectedValue = null) {
            let options = [...commonStatuses];
            if (gender === 'F') options = [...femaleSpecific, ...options];
            else if (gender === 'M') options = [...maleSpecific, ...options];
            const statusSelect = document.getElementById('edit_status');
            statusSelect.innerHTML = '';
            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt.value;
                option.textContent = opt.label;
                if (selectedValue && selectedValue === opt.value) option.selected = true;
                statusSelect.appendChild(option);
            });
        }

        function cargarRazasEdit() {
            var tipoId = document.getElementById('edit_tipo').value;
            var razaSelect = document.getElementById('edit_raza');
            if (!tipoId) { razaSelect.innerHTML = '<option value="">Primero seleccione tipo</option>'; return; }
            fetch('index.php?get_razas=1&tipo=' + tipoId)
                .then(res => res.json())
                .then(data => {
                    razaSelect.innerHTML = '';
                    if (data.length === 0) razaSelect.innerHTML = '<option value="">No hay razas disponibles</option>';
                    else data.forEach(raza => {
                        var option = document.createElement('option');
                        option.value = raza.id;
                        option.textContent = raza.name;
                        razaSelect.appendChild(option);
                    });
                })
                .catch(err => { console.error(err); razaSelect.innerHTML = '<option value="">Error al cargar razas</option>'; });
        }

        // Reutilizar modales de creación para agregar facilidad desde edición
        function abrirModalFacilidadEdit() {
            window.editMode = true;
            abrirModalFacilidad();
        }

        // Sobrescribir guardarNuevaFacilidad para que actualice también el select de edición
        const originalGuardarNuevaFacilidad = window.guardarNuevaFacilidad;
        window.guardarNuevaFacilidad = async function() {
            const nombre = document.getElementById('nuevaFacilidadNombre').value.trim();
            const tipo = document.getElementById('nuevaFacilidadTipo').value.trim();
            const capacidad = document.getElementById('nuevaFacilidadCapacidad').value;
            const ubicacion = document.getElementById('nuevaFacilidadUbicacion').value.trim();
            if (!nombre || !tipo) { alert('Debe ingresar nombre y tipo'); return; }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_facilidad');
                formData.append('nombre', nombre);
                formData.append('facility_type', tipo);
                if (capacidad) formData.append('capacity', capacidad);
                if (ubicacion) formData.append('location', ubicacion);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) { alert(data.error); return; }
                // Actualizar select de creación si existe
                const selectFacCreate = document.getElementById('facility_id');
                if (selectFacCreate) {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = `${data.name} (${data.facility_type})`;
                    selectFacCreate.appendChild(option);
                    selectFacCreate.value = data.id;
                }
                // Actualizar select de edición
                const selectFacEdit = document.getElementById('edit_facility_id');
                if (selectFacEdit) {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = `${data.name} (${data.facility_type})`;
                    selectFacEdit.appendChild(option);
                    selectFacEdit.value = data.id;
                }
                cerrarModalFacilidad();
            } catch (err) { console.error(err); alert('Error al guardar la ubicación'); }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const genderSelect = document.getElementById('edit_gender');
            const currentStatus = "<?= $animalEdit['status'] ?? '' ?>";
            updateStatusOptions(genderSelect.value, currentStatus);
            genderSelect.addEventListener('change', function() { updateStatusOptions(this.value); });
        });
    </script>
    <!-- Los modales de tipo, raza y facilidad ya están definidos arriba (en listado) y son reutilizables -->
<?php endif; ?>

</div>
</body>
</html> 