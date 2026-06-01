<?php
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../includes/query_helper.php';

// Obtener listado de animales para el buscador (solo activos, no vendidos/muertos)
$sqlAnimales = "SELECT a.id, a.tag, a.name, a.breed_id, b.name as raza 
                FROM animals a 
                LEFT JOIN breeds b ON a.breed_id = b.id 
                WHERE a.status NOT IN ('vendido', 'muerto')
                ORDER BY a.tag";
$animalesLista = ejecutarConsulta($pdo, $sqlAnimales);

// Procesar guardado de nuevo registro de salud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_chequeo'])) {
    $animal_id = intval($_POST['animal_id']);
    $alimentacion = trim($_POST['alimentacion']);
    $consumo_agua = trim($_POST['agua']);
    $estado_salud = $_POST['estado'];
    $enfermedad = $_POST['enfermedad'] ?: null;
    $vacuna = $_POST['vacuna'] ?: null;
    $tratamiento = $_POST['tratamiento'] ?: null;
    $observaciones = $_POST['obs'] ?: null;

    // Actualizar datos del animal
    $updateAnimal = "UPDATE animals SET alimentacion = :alim, consumo_agua = :agua WHERE id = :id";
    $stmt = $pdo->prepare($updateAnimal);
    $stmt->execute([':alim' => $alimentacion, ':agua' => $consumo_agua, ':id' => $animal_id]);

    // Insertar nuevo registro de salud
    $insertHistorial = "INSERT INTO animal_health_history 
                        (animal_id, estado_salud, enfermedad_detectada, vacuna_aplicada, tratamiento, observaciones) 
                        VALUES (:aid, :estado, :enf, :vac, :trat, :obs)";
    $stmt2 = $pdo->prepare($insertHistorial);
    $stmt2->execute([
        ':aid' => $animal_id,
        ':estado' => $estado_salud,
        ':enf' => $enfermedad,
        ':vac' => $vacuna,
        ':trat' => $tratamiento,
        ':obs' => $observaciones
    ]);

    // Actualizar estado general del animal según el estado de salud
    $nuevo_status = 'activo';
    if ($estado_salud === 'Enfermo') {
        $nuevo_status = 'enfermo';
    } elseif ($estado_salud === 'En observación') {
        $nuevo_status = 'observacion';
    }
    $updateStatus = "UPDATE animals SET status = :status WHERE id = :id";
    $stmt3 = $pdo->prepare($updateStatus);
    $stmt3->execute([':status' => $nuevo_status, ':id' => $animal_id]);

    $mensaje = "✅ Registro de salud guardado correctamente.";
    header("Location: index.php?mensaje=" . urlencode($mensaje));
    exit;
}

// Obtener ÚLTIMOS CHEQUEOS (solo animales con al menos un chequeo)
$sqlUltimosChequeos = "
    SELECT DISTINCT ON (a.id)
        a.id AS animal_id,
        a.tag,
        a.name AS nombre,
        b.name AS raza,
        a.alimentacion,
        a.consumo_agua,
        h.estado_salud,
        h.vacuna_aplicada,
        h.fecha_chequeo
    FROM animals a
    INNER JOIN animal_health_history h ON a.id = h.animal_id
    LEFT JOIN breeds b ON a.breed_id = b.id
    WHERE a.status NOT IN ('vendido', 'muerto')
    ORDER BY a.id, h.fecha_chequeo DESC
";
$ultimosChequeos = ejecutarConsulta($pdo, $sqlUltimosChequeos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Salud del Ganado - Historial Clínico</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  * { font-family: 'Inter', sans-serif; }
  h1,h2,h3 { font-family: 'Merriweather', serif; }
  body {
    background-image: url("vacas de giminis.png");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
  }
  .glass { background: rgba(255,255,255,0.93); backdrop-filter: blur(12px); }
  input:focus, select:focus, textarea:focus {
    outline: none; border-color: #16a34a !important; box-shadow: 0 0 0 3px rgba(22,163,74,0.15);
  }
  #toast {
    position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    color: white; padding: 12px 22px; border-radius: 10px; font-weight: 600; font-size: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    transform: translateY(80px); opacity:0; transition: all 0.35s cubic-bezier(.4,2,.6,1);
  }
  #toast.show { transform: translateY(0); opacity:1; }
  .badge { display:inline-block; padding:2px 9px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }
  .badge-excelente { background:#dcfce7; color:#15803d; }
  .badge-bueno     { background:#dbeafe; color:#1d4ed8; }
  .badge-regular   { background:#fef9c3; color:#92400e; }
  .badge-enfermo   { background:#fee2e2; color:#b91c1c; }
  .badge-observacion { background:#f3e8ff; color:#7e22ce; }
  .fila-animal { cursor: pointer; transition: background 0.2s; }
  .fila-animal:hover { background: #f0fdf4; }
  
  /* Mejora de alineación de la tabla */
  .tabla-chequeos {
    table-layout: fixed;
    width: 100%;
    min-width: 700px;
  }
  .tabla-chequeos th,
  .tabla-chequeos td {
    vertical-align: middle;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .tabla-chequeos th:nth-child(1) { width: 10%; }  /* Código */
  .tabla-chequeos th:nth-child(2) { width: 15%; }  /* Nombre */
  .tabla-chequeos th:nth-child(3) { width: 15%; }  /* Raza */
  .tabla-chequeos th:nth-child(4) { width: 12%; }  /* Alimentación */
  .tabla-chequeos th:nth-child(5) { width: 8%; }   /* Agua */
  .tabla-chequeos th:nth-child(6) { width: 15%; }  /* Estado */
  .tabla-chequeos th:nth-child(7) { width: 25%; }  /* Últ. Vacuna */
  
  /* Ajuste para pantallas muy pequeñas: permitir scroll horizontal */
  @media (max-width: 768px) {
    .overflow-x-auto {
      overflow-x: auto;
    }
    .tabla-chequeos {
      min-width: 700px;
    }
    .tabla-chequeos th,
    .tabla-chequeos td {
      white-space: nowrap;
    }
  }
</style>
</head>
<body class="min-h-screen flex flex-col">

<div class="absolute inset-0 bg-black/45 -z-0"></div>

<div class="relative z-10 flex flex-wrap justify-center gap-6 p-6">
  
  <!-- Sección de Últimos Chequeos (ocupa todo el ancho ahora) -->
  <div class="glass p-6 w-full max-w-[95vw] rounded-2xl shadow-2xl overflow-auto">
    <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
      <div class="flex items-center gap-3">
        <h2 class="text-xl font-bold text-gray-800">📋 Últimos chequeos</h2>
        <button id="abrirModalBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2 shadow">
          <i class="fa fa-plus"></i> Nuevo chequeo
        </button>
      </div>
      <span class="text-xs bg-green-100 text-green-700 font-bold px-3 py-1 rounded-full"><?= count($ultimosChequeos) ?> registros</span>
    </div>

    <div class="relative mb-3">
      <i class="fa fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
      <input type="text" id="filtroTabla" placeholder="Filtrar por nombre, código o raza..." class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm">
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200">
      <table class="w-full border-collapse text-xs tabla-chequeos">
        <thead>
          <tr class="bg-gray-800 text-white">
            <th class="p-2 pl-3">Código</th><th class="p-2">Nombre</th><th class="p-2">Raza</th>
            <th class="p-2">Alimentación</th><th class="p-2">Agua</th><th class="p-2">Estado</th><th class="p-2">Últ. Vacuna</th>
          </tr>
        </thead>
        <tbody id="tablaChequeos">
          <?php if (empty($ultimosChequeos)): ?>
            <tr><td colspan="7" class="text-center p-6 text-gray-400">No hay registros de salud aún. Realiza el primer chequeo.</td></tr>
          <?php else: ?>
            <?php foreach ($ultimosChequeos as $item): ?>
              <tr class="fila-animal border-b border-gray-100" data-animal-id="<?= $item['animal_id'] ?>" data-animal-nombre="<?= htmlspecialchars($item['nombre']) ?>">
                <td class="p-2 pl-3 font-mono text-xs truncate"><?= htmlspecialchars($item['tag']) ?></td>
                <td class="p-2 font-semibold truncate"><?= htmlspecialchars($item['nombre']) ?></td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['raza'] ?? '—') ?></td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['alimentacion'] ?? '—') ?></td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['consumo_agua'] ?? '—') ?></td>
                <td class="p-2">
                  <?php if (!empty($item['estado_salud'])): ?>
                    <span class="badge <?= getBadgeClass($item['estado_salud']) ?>"><?= $item['estado_salud'] ?></span>
                  <?php else: ?>
                    —
                  <?php endif; ?>
                </td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['vacuna_aplicada'] ?? '—') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Modal de historial -->
    <div id="modalHistorial" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
      <div class="glass rounded-2xl w-11/12 max-w-3xl max-h-[80vh] overflow-auto p-5">
        <div class="flex justify-between items-center mb-3">
          <h3 class="text-lg font-bold text-gray-800">📜 Historial de salud de <span id="historialAnimalNombre"></span></h3>
          <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-800"><i class="fa fa-times text-xl"></i></button>
        </div>
        <div id="contenidoHistorial" class="text-sm space-y-2"></div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL PARA NUEVO CHEQUEO (sin cambios) -->
<div id="modalChequeo" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
  <div class="glass rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 relative">
    <button onclick="cerrarModalChequeo()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
    <h2 class="text-xl font-bold mb-2 text-gray-800">🐄 Registro de Chequeo</h2>
    <p class="text-xs text-gray-500 mb-4">Selecciona un animal existente</p>

    <?php if (isset($_GET['mensaje'])): ?>
      <div class="mb-3 p-2 bg-green-100 text-green-700 rounded-lg text-xs text-center"><?= htmlspecialchars($_GET['mensaje']) ?></div>
    <?php endif; ?>

    <form method="POST" id="formChequeo">
      <input type="hidden" name="animal_id" id="animal_id" value="">
      
      <div class="space-y-3">
        <div class="relative">
          <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" id="buscadorAnimal" placeholder="Buscar por código (tag) o nombre..." 
                 class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm" autocomplete="off">
          <div id="sugerencias" class="absolute z-20 w-full bg-white border rounded-lg mt-1 max-h-48 overflow-y-auto hidden shadow-lg"></div>
        </div>

        <div class="relative">
          <i class="fa fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" id="codigo" placeholder="Código del animal" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm" readonly>
        </div>
        <div class="relative">
          <i class="fa fa-paw absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" id="nombreAnimal" placeholder="Nombre del animal" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm" readonly>
        </div>
        <div class="relative">
          <i class="fa fa-dna absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" id="raza" placeholder="Raza" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm" readonly>
        </div>

        <div class="relative">
          <i class="fa fa-wheat-awn absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="alimentacion" id="alimentacion" placeholder="Alimentación diaria (ej: 8kg heno)" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm">
        </div>
        <div class="relative">
          <i class="fa fa-droplet absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="agua" id="agua" placeholder="Consumo de agua (ej: 40L)" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm">
        </div>

        <select name="estado" id="estado" class="w-full p-2 rounded-lg bg-gray-100 border border-gray-200 text-sm">
          <option value="">Estado de salud</option>
          <option>Excelente</option><option>Bueno</option><option>Regular</option>
          <option>Enfermo</option><option>En observación</option>
        </select>

        <select name="enfermedad" id="enfermedad" class="w-full p-2 rounded-lg bg-gray-100 border border-gray-200 text-sm">
          <option value="">Enfermedad detectada</option>
          <option>Ninguna</option><option>Fiebre</option><option>Parásitos</option>
          <option>Infección</option><option>Problemas digestivos</option>
          <option>Metabólicas</option><option>Mastitis</option><option>Diarrea viral bovina</option>
        </select>

        <div class="relative">
          <i class="fa fa-syringe absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="vacuna" id="vacuna" placeholder="Vacuna aplicada" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm">
        </div>
        <div class="relative">
          <i class="fa fa-pills absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" name="tratamiento" id="tratamiento" placeholder="Tratamiento" class="w-full p-2 pl-9 rounded-lg bg-gray-100 border border-gray-200 text-sm">
        </div>
        <textarea name="obs" id="obs" placeholder="Observaciones adicionales..." class="w-full p-2 rounded-lg bg-gray-100 border border-gray-200 text-sm h-[70px] resize-none"></textarea>
      </div>

      <div class="flex gap-2 mt-5">
        <button type="submit" name="guardar_chequeo" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold text-sm transition flex items-center justify-center gap-2">
          <i class="fa fa-save"></i> Guardar Chequeo
        </button>
        <button type="button" onclick="limpiarFormulario()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
          <i class="fa fa-eraser"></i>
        </button>
      </div>
    </form>

    <div class="mt-4 text-center">
      <a href="../../herramientas/busqueda 2/index.php" class="text-sm bg-amber-500 hover:bg-amber-600 text-white px-3 py-1 rounded-full inline-flex items-center gap-1">
        <i class="fa fa-plus"></i> Registrar nuevo animal
      </a>
      <p class="text-xs text-gray-400 mt-2">* Los animales se registran en otra página</p>
    </div>
  </div>
</div>

<div id="toast"></div>

<script>
const animales = <?= json_encode($animalesLista) ?>;

function mostrarToast(msg, color = '#15803d') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.background = color;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2800);
}

// Autocompletado dentro del modal
const buscador = document.getElementById('buscadorAnimal');
const sugerenciasDiv = document.getElementById('sugerencias');
buscador.addEventListener('input', function() {
  const term = this.value.toLowerCase();
  if (term.length < 1) {
    sugerenciasDiv.classList.add('hidden');
    return;
  }
  const filtrados = animales.filter(a => a.tag.toLowerCase().includes(term) || (a.name && a.name.toLowerCase().includes(term)));
  if (filtrados.length === 0) {
    sugerenciasDiv.classList.add('hidden');
    return;
  }
  sugerenciasDiv.innerHTML = filtrados.map(a => `
    <div class="p-2 hover:bg-green-100 cursor-pointer border-b" data-id="${a.id}" data-tag="${a.tag}" data-name="${a.name || ''}" data-raza="${a.raza || ''}">
      <span class="font-mono text-xs">${a.tag}</span> - ${a.name || 'Sin nombre'} <span class="text-gray-400 text-xs">(${a.raza || 'sin raza'})</span>
    </div>
  `).join('');
  sugerenciasDiv.classList.remove('hidden');
  document.querySelectorAll('#sugerencias > div').forEach(el => {
    el.addEventListener('click', function() {
      document.getElementById('animal_id').value = this.dataset.id;
      document.getElementById('codigo').value = this.dataset.tag;
      document.getElementById('nombreAnimal').value = this.dataset.name;
      document.getElementById('raza').value = this.dataset.raza;
      buscador.value = `${this.dataset.tag} - ${this.dataset.name}`;
      sugerenciasDiv.classList.add('hidden');
      cargarDatosAnimal(this.dataset.id);
    });
  });
});

async function cargarDatosAnimal(animalId) {
  try {
    const res = await fetch(`obtener_datos_animal.php?id=${animalId}`);
    const data = await res.json();
    if (data.alimentacion) document.getElementById('alimentacion').value = data.alimentacion;
    if (data.consumo_agua) document.getElementById('agua').value = data.consumo_agua;
  } catch(e) { console.error(e); }
}

function limpiarFormulario() {
  ['animal_id','codigo','nombreAnimal','raza','alimentacion','agua','vacuna','tratamiento','obs'].forEach(id => document.getElementById(id).value = '');
  document.getElementById('estado').selectedIndex = 0;
  document.getElementById('enfermedad').selectedIndex = 0;
  buscador.value = '';
  sugerenciasDiv.classList.add('hidden');
}

function getBadgeClass(estado) {
  const map = {'Excelente':'badge-excelente','Bueno':'badge-bueno','Regular':'badge-regular','Enfermo':'badge-enfermo','En observación':'badge-observacion'};
  return map[estado] || '';
}

async function verHistorial(animalId, nombre) {
  const res = await fetch(`obtener_historial_animal.php?id=${animalId}`);
  const historial = await res.json();
  document.getElementById('historialAnimalNombre').innerText = nombre;
  const contenedor = document.getElementById('contenidoHistorial');
  if (historial.length === 0) {
    contenedor.innerHTML = '<p class="text-gray-400">No hay chequeos previos.</p>';
  } else {
    contenedor.innerHTML = historial.map(h => `
      <div class="border-b pb-2 mb-2">
        <div class="flex justify-between text-xs text-gray-500"><span>📅 ${h.fecha_chequeo}</span><span class="badge ${getBadgeClass(h.estado_salud)}">${h.estado_salud || '?'}</span></div>
        <div><strong>Enfermedad:</strong> ${h.enfermedad_detectada || 'Ninguna'}</div>
        <div><strong>Vacuna:</strong> ${h.vacuna_aplicada || 'N/A'}</div>
        <div><strong>Tratamiento:</strong> ${h.tratamiento || 'N/A'}</div>
        <div><strong>Obs:</strong> ${h.observaciones || '—'}</div>
      </div>
    `).join('');
  }
  document.getElementById('modalHistorial').classList.remove('hidden');
}

function cerrarModal() {
  document.getElementById('modalHistorial').classList.add('hidden');
}

// Abrir y cerrar modal de nuevo chequeo
function abrirModalChequeo() {
  document.getElementById('modalChequeo').classList.remove('hidden');
}
function cerrarModalChequeo() {
  document.getElementById('modalChequeo').classList.add('hidden');
}

// Filtro en tabla
document.getElementById('filtroTabla').addEventListener('input', function() {
  const filtro = this.value.toLowerCase();
  document.querySelectorAll('#tablaChequeos tr.fila-animal').forEach(row => {
    const texto = row.innerText.toLowerCase();
    row.style.display = texto.includes(filtro) ? '' : 'none';
  });
});

// Asignar eventos a filas existentes y al botón de abrir modal
document.querySelectorAll('.fila-animal').forEach(row => {
  row.addEventListener('click', () => verHistorial(row.dataset.animalId, row.dataset.animalNombre));
});
document.getElementById('abrirModalBtn').addEventListener('click', abrirModalChequeo);

// Para cerrar el modal si se hace clic fuera del contenido (opcional)
window.addEventListener('click', function(event) {
  const modal = document.getElementById('modalChequeo');
  if (event.target === modal) cerrarModalChequeo();
});
</script>
</body>
</html>
<?php
function getBadgeClass($estado) {
    $map = ['Excelente'=>'badge-excelente','Bueno'=>'badge-bueno','Regular'=>'badge-regular','Enfermo'=>'badge-enfermo','En observación'=>'badge-observacion'];
    return $map[$estado] ?? '';
}
?>