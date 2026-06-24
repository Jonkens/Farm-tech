<?php
/**
 * @var array $eventos
 * @var array $hembras
 * @var array $machos
 * @var array $tipos_evento
 * @var string $mensajeToast
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Eventos Reproductivos | Hato</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body {
            background: #1a4d2a;
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
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.25);
        }
        .animate-slide-in-right {
            animation: slideInRight 0.3s ease-out forwards;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .table-row-hover {
            transition: all 0.2s;
        }
        .table-row-hover:hover {
            background-color: #fef1df;
            transform: scale(1.01);
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            ring: 2px solid #b87c4f;
            border-color: #b87c4f;
        }
        .btn-ripple {
            position: relative;
            overflow: hidden;
        }
        .btn-ripple:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }
        .btn-ripple:active:after {
            width: 200%;
            height: 200%;
        }
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #2d6a4f, #1f4d38);
        }
    </style>
</head>
<body>

<?= $mensajeToast ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
                <i class="fas fa-heartbeat text-[#f7b32b]"></i> Eventos Reproductivos
            </h1>
            <p class="text-[#e2d4b5] mt-1 text-sm font-medium">Registro de celos, inseminaciones, montas y preñeces</p>
        </div>
        <div class="flex items-center gap-3 glass-card px-5 py-2 shadow-sm">
            <i class="fas fa-calendar-alt text-[#b87c4f]"></i>
            <span class="text-sm font-semibold text-[#5a3e1b]"><?= date('d/m/Y') ?></span>
            <div class="w-px h-5 bg-[#e2d4b5]"></div>
            <div class="flex gap-1">
                <span class="inline-flex items-center gap-1 text-xs bg-[#2d6a4f] text-white px-2 py-1 rounded-full"><i class="fas fa-list"></i> <?= count($eventos) ?> eventos</span>
            </div>
        </div>
    </div>

    <!-- Formulario nuevo evento -->
    <div class="glass-card rounded-2xl shadow-xl overflow-hidden mb-10">
        <div class="bg-gradient-primary px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2"><i class="fas fa-plus-circle"></i> Registrar nuevo evento</h2>
            <p class="text-green-100 text-xs mt-1">Complete los datos del evento reproductivo</p>
        </div>
        <form method="POST" action="../../procesos/guardar_evento.php" class="p-6 md:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Hembra <span class="text-red-500">*</span></label>
                    <select name="animal_id" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#b87c4f] bg-[#fffef7]">
                        <option value="">-- Seleccionar hembra --</option>
                        <?php foreach ($hembras as $h): ?>
                            <option value="<?= $h['id'] ?>">🐮 <?= htmlspecialchars($h['name']) ?> (<?= htmlspecialchars($h['tag']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Tipo de Evento <span class="text-red-500">*</span></label>
                    <select name="tipo_evento" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#b87c4f] bg-[#fffef7]">
                        <?php foreach ($tipos_evento as $te): ?>
                            <option value="<?= $te ?>"><?= $te ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Fecha <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#b87c4f] bg-[#fffef7]">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Padre (opcional)</label>
                    <select name="padre_id" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#b87c4f] bg-[#fffef7]">
                        <option value="">-- No aplica --</option>
                        <?php foreach ($machos as $m): ?>
                            <option value="<?= $m['id'] ?>">🐂 <?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['tag']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Notas adicionales</label>
                    <textarea name="notas" rows="3" placeholder="Observaciones, resultados, etc." class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#b87c4f] bg-[#fffef7]"></textarea>
                </div>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" class="bg-gradient-to-r from-[#2d6a4f] to-[#1f4d38] hover:from-[#1f4d38] hover:to-[#2d6a4f] text-white font-semibold px-8 py-3 rounded-xl transition shadow-md flex items-center gap-2 btn-ripple">
                    <i class="fas fa-save"></i> Guardar Evento
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de eventos -->
    <div class="glass-card overflow-hidden">
        <div class="bg-gradient-primary px-6 py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-white">
                <i class="fas fa-calendar-alt text-xl"></i>
                <span class="font-bold text-lg">Historial de Eventos</span>
                <span class="bg-white/30 text-xs px-2 py-1 rounded-full font-mono"><?= count($eventos) ?> registros</span>
            </div>
        </div>
        <div class="overflow-x-auto custom-scroll">
            <table class="min-w-full divide-y divide-[#f0e5d2]">
                <thead class="bg-[#e9dfc7]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b] uppercase tracking-wider">Animal</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b] uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b] uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b] uppercase tracking-wider">Padre</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b] uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0e5d2]">
                    <?php if (empty($eventos)): ?>
                        <tr><td colspan="5" class="px-6 py-12 text-center text-[#8b6946]"><i class="fas fa-calendar-times text-4xl mb-2 opacity-30"></i><p class="text-sm">No hay eventos registrados aún</p></td></tr>
                    <?php else: ?>
                        <?php foreach ($eventos as $e):
                            $padre = null;
                            if (!empty($e['padre_id']) && is_numeric($e['padre_id'])) {
                                $padre = buscarAnimalPorId((int) $e['padre_id']);
                            }
                            $badgeColor = obtenerBadgeColorEvento($e['tipo_evento']);
                        ?>
                        <tr class="table-row-hover transition-all duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#4b2e1a]">
                                <div class="flex items-center gap-2"><i class="fas fa-cow text-pink-400 text-xs"></i> <?= htmlspecialchars($e['animal_name'] ?? '-') ?> <span class="text-xs text-gray-400">(<?= htmlspecialchars($e['animal_tag'] ?? '-') ?>)</span></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 rounded-full text-xs font-semibold <?= $badgeColor ?>"><?= htmlspecialchars($e['tipo_evento']) ?></span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><i class="far fa-calendar-alt mr-1 text-gray-400"></i><?= htmlspecialchars($e['fecha']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <?php if ($padre): ?>
                                    <div class="flex items-center gap-1"><i class="fas fa-bull text-blue-500 text-xs"></i> <?= htmlspecialchars($padre['name']) ?> <span class="text-xs text-gray-400">(<?= htmlspecialchars($padre['tag']) ?>)</span></div>
                                <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button onclick='abrirEditarEvento(<?= json_encode($e) ?>)' class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-3 py-1.5 rounded-lg text-xs transition shadow-sm mr-1"><i class="fas fa-edit mr-1"></i> Editar</button>
                                <button onclick="confirmarEliminarEvento('<?= $e['id'] ?>', '<?= addslashes($e['tipo_evento']) ?>')" class="bg-red-700 hover:bg-red-800 text-white px-3 py-1.5 rounded-lg text-xs transition shadow-sm"><i class="fas fa-trash-alt mr-1"></i> Eliminar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Editar Evento -->
<div id="modal-editar-evento" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 opacity-0 animate-fade-in-up" id="modal-contenido-evento">
        <div class="bg-gradient-primary px-6 py-4 rounded-t-2xl flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center gap-2"><i class="fas fa-pen-alt"></i> Editar Evento</h3>
            <button onclick="cerrarModalEvento()" class="text-white hover:text-gray-200 text-2xl leading-5">&times;</button>
        </div>
        <form method="POST" action="../../procesos/editar_evento.php" class="p-6 space-y-5">
            <input type="hidden" name="id_editar" id="ev-id">
            <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Hembra</label><select name="animal_id" id="ev-animal" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-2.5"><?php foreach ($hembras as $h): ?><option value="<?= $h['id'] ?>">🐮 <?= htmlspecialchars($h['name']) ?> (<?= htmlspecialchars($h['tag']) ?>)</option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Tipo de Evento</label><select name="tipo_evento" id="ev-tipo" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-2.5"><?php foreach ($tipos_evento as $te): ?><option value="<?= $te ?>"><?= $te ?></option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Fecha</label><input type="date" name="fecha" id="ev-fecha" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-2.5"></div>
            <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Padre (opcional)</label><select name="padre_id" id="ev-padre" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-2.5"><option value="">-- No aplica --</option><?php foreach ($machos as $m): ?><option value="<?= $m['id'] ?>">🐂 <?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['tag']) ?>)</option><?php endforeach; ?></select></div>
            <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Notas</label><textarea name="notas" id="ev-notas" rows="2" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-2.5"></textarea></div>
            <div class="flex justify-end gap-3 pt-4"><button type="button" onclick="cerrarModalEvento()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-xl transition font-medium">Cancelar</button><button type="submit" class="bg-[#2d6a4f] hover:bg-[#1f4d38] text-white px-5 py-2 rounded-xl transition flex items-center gap-2 shadow-sm"><i class="fas fa-save"></i> Guardar Cambios</button></div>
        </form>
    </div>
</div>

<script>
function abrirEditarEvento(evento) {
    document.getElementById('ev-id').value = evento.id;
    document.getElementById('ev-fecha').value = evento.fecha;
    document.getElementById('ev-notas').value = evento.notas || '';
    document.getElementById('ev-animal').value = evento.animal_id;
    document.getElementById('ev-tipo').value = evento.tipo_evento;
    document.getElementById('ev-padre').value = evento.padre_id || '';
    const modal = document.getElementById('modal-editar-evento');
    modal.classList.remove('hidden');
    setTimeout(() => { document.getElementById('modal-contenido-evento').classList.remove('scale-95', 'opacity-0'); document.getElementById('modal-contenido-evento').classList.add('scale-100', 'opacity-100'); }, 10);
}
function cerrarModalEvento() {
    const modal = document.getElementById('modal-editar-evento');
    const content = document.getElementById('modal-contenido-evento');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
}
function confirmarEliminarEvento(id, tipo) {
    if(confirm(`⚠️ ¿Eliminar evento "${tipo}"?\nEsta acción no se puede deshacer.`)) window.location.href = `../../procesos/eliminar_evento.php?id=${id}`;
}
document.getElementById('modal-editar-evento').addEventListener('click', e => { if(e.target === document.getElementById('modal-editar-evento')) cerrarModalEvento(); });
setTimeout(() => { const toast = document.getElementById('toast'); if(toast) toast.style.opacity = '0'; setTimeout(() => toast?.remove(), 300); }, 5000);
</script>
</body>
</html>