<?php
/**
 * @var array $partos
 * @var array $hembras
 * @var array $machos
 * @var array $tipos
 * @var array $razas
 * @var string $mensajeToast
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Partos | Hato</title>
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
            padding: 1.5rem;
        }
        .glass-card {
            background: rgba(255, 251, 240, 0.97);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            border: 1px solid #e2d4b5;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .glass-card:hover { transform: translateY(-2px); box-shadow: 0 20px 30px -12px rgba(0,0,0,0.25); }
        .animate-slide-in-right { animation: slideInRight 0.3s ease-out forwards; }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        .table-row-hover { transition: all 0.2s; }
        .table-row-hover:hover { background-color: #fef1df; transform: scale(1.01); }
        input:focus, select:focus, textarea:focus { outline: none; ring: 2px solid #b87c4f; border-color: #b87c4f; }
        .btn-ripple { position: relative; overflow: hidden; }
        .btn-ripple:after { content: ''; position: absolute; top: 50%; left: 50%; width: 0; height: 0; border-radius: 50%; background: rgba(255,255,255,0.3); transform: translate(-50%, -50%); transition: width 0.3s, height 0.3s; }
        .btn-ripple:active:after { width: 200%; height: 200%; }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .bg-gradient-primary { background: linear-gradient(135deg, #2d6a4f, #1f4d38); }
    </style>
</head>
<body>

<?= $mensajeToast ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div><h1 class="text-3xl md:text-4xl font-extrabold text-[#f9eec1] flex items-center gap-3"><i class="fas fa-baby text-[#f7b32b]"></i>Registro de Partos y Nacimientos</h1><p class="text-[#e2d4b5] mt-1 text-sm">Gestión de nacimientos, crías y genealogía</p></div>
        <div class="flex items-center gap-3 glass-card px-5 py-2 shadow-sm"><i class="fas fa-calendar-alt text-[#b87c4f]"></i><span class="text-sm font-semibold text-[#5a3e1b]"><?= date('d/m/Y') ?></span><div class="w-px h-5 bg-[#e2d4b5]"></div><span class="inline-flex items-center gap-1 text-xs bg-[#2d6a4f] text-white px-2 py-1 rounded-full"><i class="fas fa-birthday-cake"></i> <?= count($partos) ?> partos</span></div>
    </div>

    <div class="glass-card rounded-2xl shadow-xl overflow-hidden mb-10">
        <div class="bg-gradient-primary px-6 py-4"><h2 class="text-xl font-bold text-white"><i class="fas fa-plus-circle mr-2"></i> Registrar nuevo parto</h2><p class="text-green-100 text-xs mt-1">Complete los datos del parto y de la cría</p></div>
        <form method="POST" action="../../procesos/guardar_parto.php" class="p-6 md:p-8 space-y-6">
            <div><h3 class="text-md font-semibold text-[#5a3e1b] mb-3"><i class="fas fa-female text-pink-500 mr-2"></i> Información del parto</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Madre *</label><select name="madre_id" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"><?php foreach ($hembras as $h): ?><option value="<?= $h['id'] ?>">🐮 <?= htmlspecialchars($h['name']) ?> (<?= htmlspecialchars($h['tag']) ?>)</option><?php endforeach; ?></select></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Padre (opcional)</label><select name="padre_id" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"><?php foreach ($machos as $m): ?><option value="<?= $m['id'] ?>">🐂 <?= htmlspecialchars($m['name']) ?> (<?= htmlspecialchars($m['tag']) ?>)</option><?php endforeach; ?></select></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Fecha del Parto *</label><input type="date" name="fecha_parto" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Peso al nacer (kg)</label><input type="number" step="0.1" name="peso_kg" placeholder="Ej: 35.5" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"></div>
            </div></div>
            <div><h3 class="text-md font-semibold text-[#5a3e1b] mb-3"><i class="fas fa-baby-carriage text-amber-500 mr-2"></i> Datos de la cría</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Nombre / Identificación *</label><input type="text" name="nombre_cria" required placeholder="Ej: Lucerito" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Arete / Código *</label><input type="text" name="arete_cria" required placeholder="Código único" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Tipo de Ganado *</label><select name="tipo_id_cria" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"><?php foreach ($tipos as $t): ?><option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option><?php endforeach; ?></select></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Sexo de la Cría *</label><select name="sexo_cria" required class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"><option value="Hembra">🐮 Hembra</option><option value="Macho">🐂 Macho</option></select></div>
                <div><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Raza</label><select name="raza_id_cria" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"><?php foreach ($razas as $r): ?><option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option><?php endforeach; ?></select></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Notas adicionales</label><textarea name="notas" rows="3" placeholder="Complicaciones, observaciones, etc." class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7]"></textarea></div>
            </div></div>
            <div class="flex justify-end"><button type="submit" class="bg-gradient-to-r from-[#2d6a4f] to-[#1f4d38] text-white font-semibold px-8 py-3 rounded-xl shadow-md flex items-center gap-2 btn-ripple"><i class="fas fa-save"></i> Registrar Parto</button></div>
        </form>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="bg-gradient-primary px-6 py-4 flex items-center justify-between"><div class="flex items-center gap-2 text-white"><i class="fas fa-list-alt text-xl"></i><span class="font-bold text-lg">Historial de Partos</span><span class="bg-white/30 text-xs px-2 py-1 rounded-full"><?= count($partos) ?> registros</span></div></div>
        <div class="overflow-x-auto custom-scroll">
            <table class="min-w-full divide-y divide-[#f0e5d2]">
                <thead class="bg-[#e9dfc7]"><tr><th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b]">Cría</th><th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b]">Madre</th><th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b]">Padre</th><th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b]">Fecha</th><th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b]">Peso</th><th class="px-6 py-3 text-left text-xs font-semibold text-[#5a3e1b]">Certificado</th></tr></thead>
                <tbody class="bg-white divide-y divide-[#f0e5d2]">
                    <?php if (empty($partos)): ?><tr><td colspan="6" class="px-6 py-12 text-center text-[#8b6946]"><i class="fas fa-baby-carriage text-4xl mb-2 opacity-30"></i><p>No hay partos registrados aún</p></td></tr><?php else: foreach ($partos as $p): ?>
                    <tr class="table-row-hover transition-all">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#4b2e1a]"><i class="fas fa-tag text-green-500 text-xs mr-1"></i><?= htmlspecialchars($p['cria_nombre'] ?? '-') ?> <span class="text-xs text-gray-400">(<?= htmlspecialchars($p['cria_tag'] ?? '-') ?>)</span></td>
                        <td class="px-6 py-4 text-sm"><i class="fas fa-female text-pink-400 text-xs mr-1"></i><?= htmlspecialchars($p['madre_nombre'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-sm"><?php if (!empty($p['padre_nombre'])): ?><i class="fas fa-mars text-blue-400 text-xs mr-1"></i><?= htmlspecialchars($p['padre_nombre']) ?><?php else: ?>—<?php endif; ?></td>
                        <td class="px-6 py-4 text-sm"><i class="far fa-calendar-alt mr-1"></i><?= htmlspecialchars($p['fecha_parto'] ?? '') ?></td>
                        <td class="px-6 py-4 text-sm"><?= htmlspecialchars($p['peso_kg'] ?? '-') ?> kg</td>
                        <td class="px-6 py-4"><a href="../../certificado/generar.php?parto_id=<?= $p['id'] ?>" target="_blank" class="inline-flex items-center gap-1 bg-gradient-to-r from-[#2d6a4f] to-[#b87c4f] text-white px-3 py-1.5 rounded-lg text-xs shadow-sm"><i class="fas fa-file-pdf"></i> PDF</a></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>setTimeout(() => { const toast = document.getElementById('toast'); if(toast) { toast.style.opacity = '0'; setTimeout(() => toast?.remove(), 300); } }, 5000);</script>
</body>
</html>