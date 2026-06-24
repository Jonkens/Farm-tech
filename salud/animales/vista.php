<?php
/**
 * Vista del módulo de Salud del Ganado.
 * Recibe $datosVista del controlador.
 */
if (!isset($datosVista) || !is_array($datosVista)) {
    die('Error: Datos de vista no disponibles.');
}
extract($datosVista);
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
  h1, h2, h3 { font-family: 'Merriweather', serif; }
  body {
    font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    background: #1a4d2a; /* verde oscuro base */
    background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                      repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
    background-size: 30px 30px, 12px 12px;
    background-attachment: fixed;
    min-height: 100vh;
    padding: 1.5rem;
  }
  .glass-card {
    background: rgba(255, 251, 240, 0.97);
    backdrop-filter: blur(8px);
    border-radius: 1.5rem;
    border: 1px solid #e2d4b5;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
  }
  input, select, textarea {
    background-color: #fffef7;
    border: 1px solid #ecdbaa;
    border-radius: 0.75rem;
    transition: all 0.2s;
  }
  input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #b87c4f;
    box-shadow: 0 0 0 3px rgba(184, 124, 79, 0.2);
  }
  .btn-primary {
    background-color: #2d6a4f;
    color: white;
    transition: all 0.2s;
  }
  .btn-primary:hover {
    background-color: #1f4d38;
    transform: translateY(-1px);
  }
  .btn-secondary {
    background-color: #b87c4f;
    color: white;
  }
  .btn-secondary:hover {
    background-color: #9a623b;
  }
  .badge {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }
  .badge-excelente { background: #dcfce7; color: #15803d; }
  .badge-bueno     { background: #dbeafe; color: #1d4ed8; }
  .badge-regular   { background: #fef9c3; color: #92400e; }
  .badge-enfermo   { background: #fee2e2; color: #b91c1c; }
  .badge-observacion { background: #f3e8ff; color: #7e22ce; }
  .fila-animal {
    cursor: pointer;
    transition: background 0.2s;
  }
  .fila-animal:hover {
    background-color: #fef1df;
  }
  .tabla-chequeos {
    table-layout: fixed;
    min-width: 700px;
  }
  .tabla-chequeos th, .tabla-chequeos td {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .tabla-chequeos th:nth-child(1) { width: 10%; }
  .tabla-chequeos th:nth-child(2) { width: 15%; }
  .tabla-chequeos th:nth-child(3) { width: 15%; }
  .tabla-chequeos th:nth-child(4) { width: 12%; }
  .tabla-chequeos th:nth-child(5) { width: 8%; }
  .tabla-chequeos th:nth-child(6) { width: 15%; }
  .tabla-chequeos th:nth-child(7) { width: 25%; }

  @media (max-width: 768px) {
    .overflow-x-auto {
      overflow-x: auto;
    }
  }
  .sugerencias {
    position: absolute;
    z-index: 20;
    width: 100%;
    background: white;
    border: 1px solid #ecdbaa;
    border-radius: 0.75rem;
    margin-top: 0.25rem;
    max-height: 12rem;
    overflow-y: auto;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
  }
  .sugerencia-item {
    padding: 0.5rem;
    cursor: pointer;
    border-bottom: 1px solid #f0e5d2;
  }
  .sugerencia-item:hover {
    background-color: #fef1df;
  }
  #toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    background-color: #2d6a4f;
    color: white;
    padding: 12px 22px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
    transform: translateY(80px);
    opacity: 0;
    transition: all 0.35s cubic-bezier(.4,2,.6,1);
  }
  #toast.show {
    transform: translateY(0);
    opacity: 1;
  }
</style>
</head>
<body>

<div class="max-w-7xl mx-auto">
  <!-- Título -->
  <div class="mb-6 flex flex-wrap justify-between items-center gap-3">
    <div>
      <h1 class="text-3xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
        <i class="fas fa-heartbeat text-[#f7b32b]"></i> Salud del Ganado
      </h1>
      <p class="text-[#e2d4b5] mt-1 text-sm">Historial clínico y control sanitario</p>
    </div>
    <button id="abrirModalBtn" class="btn-primary px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-md">
      <i class="fa fa-plus"></i> Nuevo chequeo
    </button>
  </div>

  <!-- Mensajes flash -->
  <?php if ($mensajeExito): ?>
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-xl border border-green-300"><?= $mensajeExito ?></div>
  <?php endif; ?>
  <?php if ($mensajeError): ?>
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-xl border border-red-300"><?= $mensajeError ?></div>
  <?php endif; ?>

  <!-- Tarjeta principal: lista de últimos chequeos -->
  <div class="glass-card p-5">
    <div class="flex justify-between items-center mb-3 flex-wrap gap-2">
      <h2 class="text-xl font-bold text-[#4b2e1a]"> Últimos chequeos</h2>
      <span class="text-xs bg-[#e9dfc7] text-[#5a3e1b] font-bold px-3 py-1 rounded-full"><?= count($ultimosChequeos) ?> registros</span>
    </div>

    <!-- Filtro de tabla -->
    <div class="relative mb-3">
      <i class="fa fa-filter absolute left-3 top-1/2 -translate-y-1/2 text-[#b87c4f] text-sm"></i>
      <input type="text" id="filtroTabla" placeholder="Filtrar por nombre, código o raza..." class="w-full p-2 pl-9 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm">
    </div>

    <!-- Tabla de últimos chequeos -->
    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="w-full text-xs tabla-chequeos">
        <thead class="bg-[#2d6a4f] text-[#fef5e6]">
          <tr>
            <th class="p-2 pl-3">Código</th><th class="p-2">Nombre</th><th class="p-2">Raza</th>
            <th class="p-2">Alimentación</th><th class="p-2">Agua</th><th class="p-2">Estado</th><th class="p-2">Últ. Vacuna</th>
          </tr>
        </thead>
        <tbody id="tablaChequeos">
          <?php if (empty($ultimosChequeos)): ?>
            <tr><td colspan="7" class="text-center p-6 text-gray-400">No hay registros de salud aún. Realiza el primer chequeo.</td></tr>
          <?php else: ?>
            <?php foreach ($ultimosChequeos as $item): ?>
              <tr class="fila-animal border-b border-[#f0e5d2]" data-animal-id="<?= $item['animal_id'] ?>" data-animal-nombre="<?= htmlspecialchars($item['nombre']) ?>">
                <td class="p-2 pl-3 font-mono truncate"><?= htmlspecialchars($item['tag']) ?></td>
                <td class="p-2 font-semibold truncate"><?= htmlspecialchars($item['nombre']) ?></td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['raza'] ?? '—') ?></td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['alimentacion'] ?? '—') ?></td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['consumo_agua'] ?? '—') ?></td>
                <td class="p-2">
                  <?php if (!empty($item['estado_salud'])): ?>
                    <span class="badge <?= getBadgeClass($item['estado_salud']) ?>"><?= $item['estado_salud'] ?></span>
                  <?php else: ?>—<?php endif; ?>
                </td>
                <td class="p-2 truncate"><?= htmlspecialchars($item['vacuna_aplicada'] ?? '—') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- MODAL PARA NUEVO CHEQUEO -->
<div id="modalChequeo" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
  <div class="glass-card w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 relative">
    <button onclick="cerrarModalChequeo()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
    <h2 class="text-xl font-bold mb-2 text-[#4b2e1a]">🐄 Registro de Chequeo</h2>
    <p class="text-xs text-gray-500 mb-4">Selecciona un animal existente</p>

    <form method="POST" id="formChequeo">
      <input type="hidden" name="animal_id" id="animal_id" value="">

      <div class="space-y-3">
        <!-- Buscador con autocompletado -->
        <div class="relative">
          <i class="fa fa-search absolute left-3 top-1/2 -translate-y-1/2 text-[#b87c4f] text-sm"></i>
          <input type="text" id="buscadorAnimal" placeholder="Buscar por código (tag) o nombre..." class="w-full p-2 pl-9 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm" autocomplete="off">
          <div id="sugerencias" class="hidden sugerencias"></div>
        </div>

        <div><i class="fa fa-tag mr-2 text-[#b87c4f]"></i><input type="text" id="codigo" placeholder="Código del animal" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm" readonly></div>
        <div><i class="fa fa-paw mr-2 text-[#b87c4f]"></i><input type="text" id="nombreAnimal" placeholder="Nombre del animal" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm" readonly></div>
        <div><i class="fa fa-dna mr-2 text-[#b87c4f]"></i><input type="text" id="raza" placeholder="Raza" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm" readonly></div>

        <div><i class="fa fa-wheat-awn mr-2 text-[#b87c4f]"></i><input type="text" name="alimentacion" id="alimentacion" placeholder="Alimentación diaria (ej: 8kg heno)" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm"></div>
        <div><i class="fa fa-droplet mr-2 text-[#b87c4f]"></i><input type="text" name="agua" id="agua" placeholder="Consumo de agua (ej: 40L)" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm"></div>

        <select name="estado" id="estado" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm" required>
          <option value="">Seleccione un estado de salud</option>
          <option>Excelente</option><option>Bueno</option><option>Regular</option>
          <option>Enfermo</option><option>En observación</option>
        </select>

        <select name="enfermedad" id="enfermedad" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm">
          <option value="">Enfermedad detectada</option>
          <option>Ninguna</option><option>Fiebre</option><option>Parásitos</option>
          <option>Infección</option><option>Problemas digestivos</option>
          <option>Metabólicas</option><option>Mastitis</option><option>Diarrea viral bovina</option>
        </select>

        <div><i class="fa fa-syringe mr-2 text-[#b87c4f]"></i><input type="text" name="vacuna" id="vacuna" placeholder="Vacuna aplicada" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm"></div>
        <div><i class="fa fa-pills mr-2 text-[#b87c4f]"></i><input type="text" name="tratamiento" id="tratamiento" placeholder="Tratamiento" class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm"></div>
        <textarea name="obs" id="obs" placeholder="Observaciones adicionales..." class="w-full p-2 bg-[#fffef7] border border-[#ecdbaa] rounded-lg text-sm h-[70px] resize-none"></textarea>
      </div>

      <div class="flex gap-2 mt-5">
        <button type="submit" name="guardar_chequeo" class="flex-1 btn-primary py-2 rounded-lg font-semibold text-sm flex items-center justify-center gap-2">
          <i class="fa fa-save"></i> Guardar Chequeo
        </button>
        <button type="button" onclick="limpiarFormulario()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
          <i class="fa fa-eraser"></i>
        </button>
      </div>
    </form>

    <div class="mt-4 text-center">
      <a href="../../herramientas/busqueda 2/index.php" class="text-sm bg-amber-600 hover:bg-amber-700 text-white px-3 py-1 rounded-full inline-flex items-center gap-1">
        <i class="fa fa-plus"></i> Registrar nuevo animal
      </a>
      <p class="text-xs text-gray-500 mt-2">* Los animales se registran en otra página</p>
    </div>
  </div>
</div>

<!-- MODAL DE HISTORIAL -->
<div id="modalHistorial" class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 hidden">
  <div class="glass-card w-11/12 max-w-3xl max-h-[80vh] overflow-auto p-5">
    <div class="flex justify-between items-center mb-3">
      <h3 class="text-lg font-bold text-[#4b2e1a]">📜 Historial de salud de <span id="historialAnimalNombre"></span></h3>
      <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-800"><i class="fa fa-times text-xl"></i></button>
    </div>
    <div id="contenidoHistorial" class="text-sm space-y-2"></div>
  </div>
</div>

<div id="toast"></div>

<script>
const animales = <?= json_encode($animalesLista) ?>;

function mostrarToast(msg, color = '#2d6a4f') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.background = color;
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2800);
}

// Autocompletado
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
    <div class="sugerencia-item" data-id="${a.id}" data-tag="${a.tag}" data-name="${a.name || ''}" data-raza="${a.raza || ''}">
      <span class="font-mono text-xs">${a.tag}</span> - ${a.name || 'Sin nombre'} <span class="text-gray-400 text-xs">(${a.raza || 'sin raza'})</span>
    </div>
  `).join('');
  sugerenciasDiv.classList.remove('hidden');

  document.querySelectorAll('#sugerencias .sugerencia-item').forEach(el => {
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
    const res = await fetch(`index.php?ajax=get_datos_animal&id=${animalId}`);
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

async function verHistorial(animalId, nombre) {
  const res = await fetch(`index.php?ajax=get_historial&id=${animalId}`);
  const historial = await res.json();
  document.getElementById('historialAnimalNombre').innerText = nombre;
  const contenedor = document.getElementById('contenidoHistorial');
  if (historial.length === 0) {
    contenedor.innerHTML = '<p class="text-gray-400">No hay chequeos previos.</p>';
  } else {
    contenedor.innerHTML = historial.map(h => {
      const badgeClass = getBadgeClassJS(h.estado_salud);
      return `
        <div class="border-b pb-2 mb-2">
          <div class="flex justify-between text-xs text-gray-500"><span>📅 ${h.fecha_chequeo}</span><span class="badge ${badgeClass}">${h.estado_salud || '?'}</span></div>
          <div><strong>Enfermedad:</strong> ${h.enfermedad_detectada || 'Ninguna'}</div>
          <div><strong>Vacuna:</strong> ${h.vacuna_aplicada || 'N/A'}</div>
          <div><strong>Tratamiento:</strong> ${h.tratamiento || 'N/A'}</div>
          <div><strong>Obs:</strong> ${h.observaciones || '—'}</div>
        </div>
      `;
    }).join('');
  }
  document.getElementById('modalHistorial').classList.remove('hidden');
}

function getBadgeClassJS(estado) {
  const map = {
    'Excelente': 'badge-excelente',
    'Bueno': 'badge-bueno',
    'Regular': 'badge-regular',
    'Enfermo': 'badge-enfermo',
    'En observación': 'badge-observacion'
  };
  return map[estado] || '';
}

function cerrarModal() {
  document.getElementById('modalHistorial').classList.add('hidden');
}
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

// Eventos
document.querySelectorAll('.fila-animal').forEach(row => {
  row.addEventListener('click', () => verHistorial(row.dataset.animalId, row.dataset.animalNombre));
});
document.getElementById('abrirModalBtn').addEventListener('click', abrirModalChequeo);
window.addEventListener('click', function(event) {
  const modal = document.getElementById('modalChequeo');
  if (event.target === modal) cerrarModalChequeo();
});
</script>
</body>
</html>