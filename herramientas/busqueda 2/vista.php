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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $modo === 'listado' ? 'Registro de Animales' : ($modo === 'detalle' ? 'Detalle de Animal' : 'Editar Animal') ?> - Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .cursor-pointer { cursor: pointer; }
        .transition { transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto p-6">

<?php if ($modo === 'listado'): ?>
    <!-- ==================== LISTADO PRINCIPAL ==================== -->
    <header class="bg-gradient-to-r from-blue-950 to-slate-900 rounded-2xl shadow-lg px-8 py-6 flex justify-between items-center mb-8">
        <h1 class="text-white text-4xl font-bold flex items-center gap-3">🐄 Registro de Animales</h1>
        <button type="button" onclick="abrirModal()" class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl shadow-md font-semibold transition cursor-pointer">
            + Agregar
        </button>
    </header>

    <!-- Tarjetas estadísticas -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-7 gap-3 mb-8">
        <div onclick="filtrarTipo(0)" class="bg-white rounded-xl shadow p-3 flex items-center gap-2 cursor-pointer hover:scale-105 transition">
            <div class="bg-green-100 p-2 rounded-full text-2xl">🐄</div>
            <div>
                <p class="text-gray-500 text-xs">Total animales</p>
                <h2 class="text-xl font-bold text-green-500"><?= $total ?></h2>
            </div>
        </div>
        <?php foreach ($statsPorTipo as $tipo): ?>
        <div onclick="filtrarTipo('<?= htmlspecialchars($tipo['name']) ?>')" class="bg-white rounded-xl shadow p-3 flex items-center gap-2 cursor-pointer hover:scale-105 transition" data-tipo-nombre="<?= $tipo['name'] ?>">
            <div class="bg-blue-100 p-2 rounded-full text-2xl"><?= obtenerIconoTipo($tipo['name']) ?></div>
            <div>
                <p class="text-gray-500 text-xs"><?= $tipo['name'] ?></p>
                <h2 class="text-xl font-bold text-blue-500"><?= $tipo['count'] ?></h2>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="bg-white rounded-xl shadow p-3 flex items-center gap-2">
            <div class="bg-red-100 p-2 rounded-full text-2xl">📅</div>
            <div>
                <p class="text-gray-500 text-xs">Último registro</p>
                <p class="text-sm font-bold text-red-500 truncate max-w-[120px]"><?= $ultimoRegistro ?></p>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
        <div class="mb-6 relative">
            <span class="absolute left-4 top-3 text-gray-400 text-xl">🔍</span>
            <input type="text" id="buscador" placeholder="Buscar por nombre, raza o tipo..."
                class="w-full border border-gray-300 rounded-xl px-12 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="overflow-x-auto rounded-2xl border border-gray-200">
            <table class="w-full bg-white">
                <thead class="bg-gradient-to-r from-blue-900 to-slate-800 text-white">
                    <tr>
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
                <tbody id="tablaAnimales" class="divide-y divide-gray-200 text-sm text-gray-700">
                <?php foreach ($animales as $animal): ?>
                    <tr class="hover:bg-gray-50 transition" data-tipo="<?= htmlspecialchars($animal['animal_type_name']) ?>">
                        <td class="pl-4 font-mono"><?= htmlspecialchars($animal['tag']) ?></td>
                        <td class="font-semibold text-gray-800">
                            <a href="index.php?view=<?= $animal['id'] ?>" class="hover:underline"><?= htmlspecialchars($animal['name']) ?></a>
                        </td>
                        <td><span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700"><?= htmlspecialchars($animal['animal_type_name']) ?></span></td>
                        <td><?= htmlspecialchars($animal['breed_name'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($animal['facility_name'] ?? '—') ?></td>
                        <td><?= calcularEdad($animal['birth_date']) ?></td>
                        <td><?= number_format($animal['weight_kg'], 2) ?></td>
                        <td><?= $animal['gender'] == 'M' ? 'Macho' : 'Hembra' ?></td>
                        <td><?= ucfirst($animal['status']) ?></td>
                        <td class="text-center">
                            <div class="flex justify-center gap-2">
                                <a href="index.php?edit=<?= $animal['id'] ?>" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg text-xs font-medium transition">✏ Editar</a>
                                <a href="index.php?delete=<?= $animal['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este registro?')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-medium transition">🗑 Eliminar</a>
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
                <a href="?page=<?= $page-1 ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-100">◀ Anterior</a>
            <?php else: ?>
                <span class="px-4 py-2 border rounded-lg text-gray-400 cursor-not-allowed">◀ Anterior</span>
            <?php endif; ?>
            <?php
            $start = max(1, $page - 2);
            $end = min($totalPages, $page + 2);
            for ($i = $start; $i <= $end; $i++):
            ?>
                <?php if ($i == $page): ?>
                    <span class="px-4 py-2 bg-green-500 text-white rounded-lg"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-100"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page+1 ?>" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Siguiente ▶</a>
            <?php else: ?>
                <span class="px-4 py-2 border rounded-lg text-gray-400 cursor-not-allowed">Siguiente ▶</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="text-center text-gray-500 text-sm mt-3">
            Mostrando <?= count($animales) ?> de <?= $totalAnimals ?> registros
        </div>
    </div>

    <!-- Modal de creación -->
    <div id="modal" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl p-6 relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="cerrarModal()" class="absolute top-3 right-4 text-gray-500 text-2xl hover:text-red-500">&times;</button>
            <h2 class="text-xl font-bold text-gray-700 mb-4 border-b pb-2">Registrar nuevo animal</h2>
            <form method="POST" action="index.php" id="createForm">
                <input type="hidden" name="action" value="create">
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Género *</label>
                        <select name="gender" id="gender" required class="w-full border rounded-lg px-3 py-2">
                            <option value="">Seleccione género</option>
                            <option value="M">Macho</option>
                            <option value="H">Hembra</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Código / Arete *</label>
                        <input type="text" name="tag" id="tag" required readonly class="w-full border rounded-lg px-3 py-2 bg-gray-100">
                        <p class="text-xs text-gray-400 mt-1">Se genera automáticamente según el género.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Tipo de animal *</label>
                        <div class="flex gap-2">
                            <select name="animal_type_id" id="tipo_animal" required class="w-full border rounded-lg px-3 py-2" onchange="cargarRazas()">
                                <option value="">Seleccione tipo de animal</option>
                                <?php foreach ($tiposAnimal as $tipo): ?>
                                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" onclick="abrirModalTipo()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap">+ Nuevo tipo</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Raza *</label>
                        <div class="flex gap-2">
                            <select name="breed_id" id="raza_animal" required class="w-full border rounded-lg px-3 py-2">
                                <option value="">Primero seleccione un tipo</option>
                            </select>
                            <button type="button" onclick="abrirModalRaza()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap">+ Nueva raza</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Ubicación (Establo, Corral, Galpón...)</label>
                        <div class="flex gap-2">
                            <select name="facility_id" id="facility_id" class="w-full border rounded-lg px-3 py-2">
                                <option value="">Seleccione una ubicación</option>
                                <?php foreach ($facilidades as $fac): ?>
                                    <option value="<?= $fac['id'] ?>"><?= htmlspecialchars($fac['name']) . ' (' . htmlspecialchars($fac['facility_type']) . ')' ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" onclick="abrirModalFacilidad()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg text-sm whitespace-nowrap">+ Nueva ubicación</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nombre *</label>
                        <input type="text" name="name" placeholder="Nombre del animal" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Peso (kg) *</label>
                        <input type="number" step="0.01" name="weight_kg" placeholder="Ej: 350.50" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Estado *</label>
                        <select name="status" id="status" required class="w-full border rounded-lg px-3 py-2"></select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Fecha de nacimiento *</label>
                        <input type="date" name="birth_date" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Fecha de ingreso *</label>
                        <input type="date" name="entry_date" required class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Notas</label>
                        <textarea name="notes" rows="3" placeholder="Notas adicionales..." class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="cerrarModal()" class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">Cancelar</button>
                    <button type="submit" class="w-1/2 bg-slate-800 text-white py-2 rounded-lg hover:bg-slate-900">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para agregar nuevo tipo de animal -->
    <div id="modalTipo" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
            <h3 class="text-lg font-bold mb-4">Nuevo tipo de animal</h3>
            <input type="text" id="nuevoTipoNombre" placeholder="Ej: Bovino, Ovino, Caprino..." class="w-full border rounded-lg px-3 py-2 mb-4">
            <div class="flex gap-3">
                <button onclick="cerrarModalTipo()" class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">Cancelar</button>
                <button onclick="guardarNuevoTipo()" class="w-1/2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
            </div>
        </div>
    </div>

    <!-- Modal para agregar nueva raza -->
    <div id="modalRaza" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
            <h3 class="text-lg font-bold mb-4">Nueva raza</h3>
            <select id="razaTipoId" class="w-full border rounded-lg px-3 py-2 mb-3">
                <option value="">Seleccione el tipo de animal</option>
                <?php foreach ($tiposAnimal as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" id="nuevaRazaNombre" placeholder="Nombre de la raza" class="w-full border rounded-lg px-3 py-2 mb-4">
            <div class="flex gap-3">
                <button onclick="cerrarModalRaza()" class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">Cancelar</button>
                <button onclick="guardarNuevaRaza()" class="w-1/2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
            </div>
        </div>
    </div>

    <!-- Modal para agregar nueva facilidad (ubicación) -->
    <div id="modalFacilidad" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
            <h3 class="text-lg font-bold mb-4">Nueva ubicación</h3>
            <input type="text" id="nuevaFacilidadNombre" placeholder="Nombre (ej. Establo Norte)" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" id="nuevaFacilidadTipo" placeholder="Tipo (establo, corral, galpón, potrero...)" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="number" id="nuevaFacilidadCapacidad" placeholder="Capacidad (opcional)" class="w-full border rounded-lg px-3 py-2 mb-3">
            <input type="text" id="nuevaFacilidadUbicacion" placeholder="Ubicación (opcional)" class="w-full border rounded-lg px-3 py-2 mb-4">
            <div class="flex gap-3">
                <button onclick="cerrarModalFacilidad()" class="w-1/2 border py-2 rounded-lg hover:bg-gray-100">Cancelar</button>
                <button onclick="guardarNuevaFacilidad()" class="w-1/2 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Guardar</button>
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
            if (gender === 'H') options = [...femaleSpecificModal, ...options];
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
            if (tipoNombre === 0 || tipoNombre === "0") {
                filas.forEach(fila => fila.style.display = "");
                return;
            }
            filas.forEach(function(fila){
                let tipoFila = fila.getAttribute('data-tipo') || "";
                fila.style.display = (tipoFila === tipoNombre) ? "" : "none";
            });
        }

        // Funciones para nuevo tipo de animal
        function abrirModalTipo() {
            document.getElementById('modalTipo').classList.remove('hidden');
        }
        function cerrarModalTipo() {
            document.getElementById('modalTipo').classList.add('hidden');
            document.getElementById('nuevoTipoNombre').value = '';
        }
        async function guardarNuevoTipo() {
            const nombre = document.getElementById('nuevoTipoNombre').value.trim();
            if (!nombre) {
                alert('Debe ingresar un nombre');
                return;
            }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_tipo');
                formData.append('nombre', nombre);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const selectTipo = document.getElementById('tipo_animal');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                selectTipo.appendChild(option);
                selectTipo.value = data.id;
                cargarRazas();
                cerrarModalTipo();
            } catch (err) {
                console.error(err);
                alert('Error al guardar el tipo');
            }
        }

        // Funciones para nueva raza
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
        function cerrarModalRaza() {
            document.getElementById('modalRaza').classList.add('hidden');
            document.getElementById('nuevaRazaNombre').value = '';
            document.getElementById('razaTipoId').value = '';
        }
        async function guardarNuevaRaza() {
            const tipoId = document.getElementById('razaTipoId').value;
            const nombre = document.getElementById('nuevaRazaNombre').value.trim();
            if (!tipoId || !nombre) {
                alert('Debe seleccionar un tipo y escribir el nombre de la raza');
                return;
            }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_raza');
                formData.append('animal_type_id', tipoId);
                formData.append('nombre', nombre);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const selectRaza = document.getElementById('raza_animal');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.name;
                selectRaza.appendChild(option);
                selectRaza.value = data.id;
                cerrarModalRaza();
            } catch (err) {
                console.error(err);
                alert('Error al guardar la raza');
            }
        }

        // Funciones para nueva facilidad (ubicación)
        function abrirModalFacilidad() {
            document.getElementById('modalFacilidad').classList.remove('hidden');
        }
        function cerrarModalFacilidad() {
            document.getElementById('modalFacilidad').classList.add('hidden');
            document.getElementById('nuevaFacilidadNombre').value = '';
            document.getElementById('nuevaFacilidadTipo').value = '';
            document.getElementById('nuevaFacilidadCapacidad').value = '';
            document.getElementById('nuevaFacilidadUbicacion').value = '';
        }
        async function guardarNuevaFacilidad() {
            const nombre = document.getElementById('nuevaFacilidadNombre').value.trim();
            const tipo = document.getElementById('nuevaFacilidadTipo').value.trim();
            const capacidad = document.getElementById('nuevaFacilidadCapacidad').value;
            const ubicacion = document.getElementById('nuevaFacilidadUbicacion').value.trim();
            if (!nombre || !tipo) {
                alert('Debe ingresar nombre y tipo');
                return;
            }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_facilidad');
                formData.append('nombre', nombre);
                formData.append('facility_type', tipo);
                if (capacidad) formData.append('capacity', capacidad);
                if (ubicacion) formData.append('location', ubicacion);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                const selectFac = document.getElementById('facility_id');
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = `${data.name} (${data.facility_type})`;
                selectFac.appendChild(option);
                selectFac.value = data.id;
                cerrarModalFacilidad();
            } catch (err) {
                console.error(err);
                alert('Error al guardar la ubicación');
            }
        }
    </script>

<?php elseif ($modo === 'detalle'): ?>
    <!-- ==================== VISTA DETALLE ==================== -->
    <div class="max-w-4xl mx-auto mt-10 bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-700">🐄 Detalle del Animal</h2>
            <a href="index.php" class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800">← Volver</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <p><span class="font-semibold">ID/Arete:</span> <?= htmlspecialchars($detalle['tag']) ?></p>
                <p><span class="font-semibold">Nombre:</span> <?= htmlspecialchars($detalle['name']) ?></p>
                <p><span class="font-semibold">Tipo:</span> <?= htmlspecialchars($detalle['animal_type_name']) ?></p>
                <p><span class="font-semibold">Raza:</span> <?= htmlspecialchars($detalle['breed_name']) ?></p>
                <p><span class="font-semibold">Ubicación:</span> <?= htmlspecialchars($detalle['facility_name'] ?? '—') ?></p>
            </div>
            <div class="space-y-2">
                <p><span class="font-semibold">Fecha nacimiento:</span> <?= formatearFecha($detalle['birth_date'] ?? null) ?></p>
                <p><span class="font-semibold">Edad:</span> <?= $edadDetalle ?></p>
                <p><span class="font-semibold">Peso:</span> <?= $detalle['weight_kg'] ?> kg</p>
                <p><span class="font-semibold">Género:</span> <?= $detalle['gender'] == 'M' ? 'Macho' : 'Hembra' ?></p>
                <p><span class="font-semibold">Estado:</span> <?= ucfirst($detalle['status']) ?></p>
                <p><span class="font-semibold">Notas:</span> <?= nl2br(htmlspecialchars($detalle['notes'])) ?></p>
            </div>
        </div>
    </div>

<?php elseif ($modo === 'edicion'): ?>
    <!-- ==================== VISTA EDICIÓN ==================== -->
    <div class="max-w-2xl mx-auto mt-10 bg-white rounded-2xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-700">✏️ Editar Animal</h2>
            <a href="index.php" class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">← Volver</a>
        </div>
        <form method="POST" class="space-y-4" id="editForm">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $animalEdit['id'] ?>">
            <div>
                <label class="text-sm text-gray-500">Género *</label>
                <select name="gender" id="edit_gender" required class="w-full border rounded-lg px-3 py-2">
                    <option value="M" <?= $animalEdit['gender'] == 'M' ? 'selected' : '' ?>>Macho</option>
                    <option value="H" <?= $animalEdit['gender'] == 'H' ? 'selected' : '' ?>>Hembra</option>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-500">Código / Arete *</label>
                <input type="text" name="tag" required value="<?= htmlspecialchars($animalEdit['tag']) ?>" class="w-full border rounded-lg px-3 py-2" id="edit_tag">
                <p class="text-xs text-gray-400 mt-1">Puede modificarlo, pero no debe repetirse.</p>
            </div>
            <div>
                <label class="text-sm text-gray-500">Tipo de animal *</label>
                <select name="animal_type_id" id="edit_tipo" required class="w-full border rounded-lg px-3 py-2" onchange="cargarRazasEdit()">
                    <option value="">Seleccione</option>
                    <?php foreach ($tiposAnimal as $tipo): ?>
                        <option value="<?= $tipo['id'] ?>" <?= $tipo['id'] == $animalEdit['animal_type_id'] ? 'selected' : '' ?>><?= htmlspecialchars($tipo['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-500">Raza *</label>
                <select name="breed_id" id="edit_raza" required class="w-full border rounded-lg px-3 py-2">
                    <?php foreach ($razasEdit as $raza): ?>
                        <option value="<?= $raza['id'] ?>" <?= $raza['id'] == $animalEdit['breed_id'] ? 'selected' : '' ?>><?= htmlspecialchars($raza['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-sm text-gray-500">Ubicación (Establo, Corral...)</label>
                <select name="facility_id" id="edit_facility_id" class="w-full border rounded-lg px-3 py-2">
                    <option value="">Seleccione una ubicación</option>
                    <?php foreach ($facilidades as $fac): ?>
                        <option value="<?= $fac['id'] ?>" <?= ($fac['id'] == $animalEdit['facility_id']) ? 'selected' : '' ?>><?= htmlspecialchars($fac['name']) . ' (' . htmlspecialchars($fac['facility_type']) . ')' ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="button" onclick="abrirModalFacilidadEdit()" class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm">+ Nueva ubicación</button>
            </div>
            <div>
                <label class="text-sm text-gray-500">Nombre *</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($animalEdit['name']) ?>" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-500">Peso (kg) *</label>
                <input type="number" step="0.01" name="weight_kg" required value="<?= $animalEdit['weight_kg'] ?>" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-500">Estado</label>
                <select name="status" id="edit_status" required class="w-full border rounded-lg px-3 py-2"></select>
            </div>
            <div>
                <label class="text-sm text-gray-500">Fecha nacimiento *</label>
                <input type="date" name="birth_date" required value="<?= $animalEdit['birth_date'] ?>" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-500">Fecha de ingreso *</label>
                <input type="date" name="entry_date" required value="<?= $animalEdit['entry_date'] ?>" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="text-sm text-gray-500">Notas</label>
                <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2"><?= htmlspecialchars($animalEdit['notes']) ?></textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="w-1/2 bg-slate-700 hover:bg-slate-800 text-white py-2 rounded-lg">Guardar cambios</button>
                <a href="index.php" class="w-1/2 text-center border py-2 rounded-lg hover:bg-gray-100">Cancelar</a>
            </div>
        </form>
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
            if (gender === 'H') options = [...femaleSpecific, ...options];
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

        // Funciones para agregar facilidad desde edición
        function abrirModalFacilidadEdit() {
            // Podemos reutilizar los mismos modales y funciones, pero necesitamos un modal específico
            // O simplemente llamamos al modal global y luego actualizamos el select de edición.
            // Por simplicidad, usamos el mismo modal y luego añadimos la opción también al select de edición.
            // Creamos una función global que agregue también al select de edición.
            window.editMode = true;
            abrirModalFacilidad();
        }

        // Modificamos la función guardarNuevaFacilidad para que también actualice el select de edición si existe
        const originalGuardarNuevaFacilidad = window.guardarNuevaFacilidad;
        window.guardarNuevaFacilidad = async function() {
            const nombre = document.getElementById('nuevaFacilidadNombre').value.trim();
            const tipo = document.getElementById('nuevaFacilidadTipo').value.trim();
            const capacidad = document.getElementById('nuevaFacilidadCapacidad').value;
            const ubicacion = document.getElementById('nuevaFacilidadUbicacion').value.trim();
            if (!nombre || !tipo) {
                alert('Debe ingresar nombre y tipo');
                return;
            }
            try {
                const formData = new FormData();
                formData.append('action', 'agregar_facilidad');
                formData.append('nombre', nombre);
                formData.append('facility_type', tipo);
                if (capacidad) formData.append('capacity', capacidad);
                if (ubicacion) formData.append('location', ubicacion);
                const response = await fetch('index.php', { method: 'POST', body: formData });
                const data = await response.json();
                if (data.error) {
                    alert(data.error);
                    return;
                }
                // Actualizar select de creación (si existe)
                const selectFacCreate = document.getElementById('facility_id');
                if (selectFacCreate) {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = `${data.name} (${data.facility_type})`;
                    selectFacCreate.appendChild(option);
                    selectFacCreate.value = data.id;
                }
                // Actualizar select de edición (si existe)
                const selectFacEdit = document.getElementById('edit_facility_id');
                if (selectFacEdit) {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.textContent = `${data.name} (${data.facility_type})`;
                    selectFacEdit.appendChild(option);
                    selectFacEdit.value = data.id;
                }
                cerrarModalFacilidad();
            } catch (err) {
                console.error(err);
                alert('Error al guardar la ubicación');
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const genderSelect = document.getElementById('edit_gender');
            const currentStatus = "<?= $animalEdit['status'] ?>";
            updateStatusOptions(genderSelect.value, currentStatus);
            genderSelect.addEventListener('change', function() { updateStatusOptions(this.value); });
        });
    </script>
    <!-- Incluir los modales de tipo, raza y facilidad también para edición (se reutilizan los mismos) -->
    <!-- Copiar los mismos modales de creación aquí abajo (se pueden mover fuera del if de listado para que estén disponibles siempre) -->
<?php endif; ?>

</div>
</body>
</html>