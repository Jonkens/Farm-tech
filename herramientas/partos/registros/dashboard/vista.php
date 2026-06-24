<?php
/**
 * @var int $totalMachos
 * @var int $totalHembras
 * @var int $totalCrias
 * @var int $totalAnimales
 * @var int $partosMes
 * @var int $eventosMes
 * @var int $prenadas
 * @var array $ultimosPartos
 * @var array $alertas
 * @var array $tiposDatos
 * @var array $partosMensuales
 * @var array $tiposNombres
 * @var array $tiposCantidades
 * @var array $mesesLabels
 * @var array $partosCantidades
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Panel de Control | Gestión Ganadera</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
        }
        .animate-fade-up {
            animation: fadeUp 0.5s ease-out forwards;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .progress-bar {
            transition: width 1s ease-out;
        }
        .alert-item {
            transition: all 0.2s ease;
        }
        .alert-item:hover {
            transform: translateX(6px);
        }
        .custom-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
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
            transition: width 0.4s, height 0.4s;
        }
        .btn-ripple:active:after {
            width: 200%;
            height: 200%;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #2d6a4f, #1f4d38);
        }
    </style>
</head>
<body>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
    
    <!-- Encabezado -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
                <i class="fas fa-tachometer-alt text-[#f7b32b]"></i> Panel de Control
            </h1>
            <p class="text-[#e2d4b5] mt-1 text-sm font-medium">Visión general del hato · Gestión inteligente</p>
        </div>
        <div class="flex items-center gap-3 glass-card px-5 py-2 shadow-sm">
            <i class="fas fa-calendar-alt text-[#b87c4f]"></i>
            <span class="text-sm font-semibold text-[#5a3e1b]"><?= date('d/m/Y') ?></span>
            <div class="w-px h-5 bg-[#e2d4b5]"></div>
            <i class="fas fa-sync-alt text-gray-400 text-xs hover:text-[#b87c4f] transition cursor-pointer" onclick="location.reload();" title="Actualizar datos"></i>
        </div>
    </div>

    <!-- Tarjetas principales -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 lg:gap-7 mb-10">
        <?php 
        $cards = [
            ['total' => $totalAnimales, 'label' => 'Total Animales', 'icon' => 'fa-paw', 'gradient' => 'from-indigo-500 to-blue-600'],
            ['total' => $totalMachos, 'label' => 'Machos', 'icon' => 'fa-venus-mars', 'gradient' => 'from-blue-500 to-sky-600'],
            ['total' => $totalHembras, 'label' => 'Hembras', 'icon' => 'fa-female', 'gradient' => 'from-rose-400 to-pink-600'],
            ['total' => $totalCrias, 'label' => 'Crías', 'icon' => 'fa-baby-carriage', 'gradient' => 'from-amber-500 to-orange-500']
        ];
        foreach($cards as $idx => $card): ?>
        <div class="stat-card glass-card p-5 animate-fade-up" style="animation-delay: <?= $idx * 0.05 ?>s">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl md:text-4xl font-black text-[#4b2e1a] countup" data-value="<?= $card['total'] ?>">0</div>
                    <div class="text-xs font-medium text-[#8b6946] mt-1 uppercase tracking-wide"><?= $card['label'] ?></div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br <?= $card['gradient'] ?> flex items-center justify-center shadow-md">
                    <i class="fas <?= $card['icon'] ?> text-white text-xl"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Segunda fila (Partos, Eventos, Prenadas) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
        <?php 
        $secCards = [
            ['value' => $partosMes, 'label' => 'Partos este mes', 'icon' => 'fa-birthday-cake', 'gradient' => 'from-purple-500 to-purple-700'],
            ['value' => $eventosMes, 'label' => 'Eventos registrados', 'icon' => 'fa-calendar-check', 'gradient' => 'from-amber-500 to-orange-600'],
            ['value' => $prenadas, 'label' => 'Hembras preñadas', 'icon' => 'fa-heartbeat', 'gradient' => 'from-emerald-500 to-teal-600']
        ];
        foreach($secCards as $idx => $sc): ?>
        <div class="glass-card p-5 flex items-center justify-between gap-3 animate-fade-up" style="animation-delay: <?= 0.15 + $idx * 0.05 ?>s">
            <div>
                <div class="text-2xl md:text-3xl font-extrabold text-[#4b2e1a] countup" data-value="<?= $sc['value'] ?>">0</div>
                <div class="text-xs text-[#8b6946] font-medium mt-1"><i class="fas <?= $sc['icon'] ?> mr-1 opacity-70"></i><?= $sc['label'] ?></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br <?= $sc['gradient'] ?> flex items-center justify-center shadow-inner">
                <i class="fas <?= $sc['icon'] ?> text-white text-lg"></i>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Distribución y accesos rápidos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-[#5a3e1b] text-lg"><i class="fas fa-chart-pie text-[#b87c4f] mr-2"></i>Población por tipo</h3>
                <span class="text-xs bg-[#e9dfc7] text-[#5a3e1b] px-2 py-1 rounded-full"><?= count($tiposDatos) ?> categorías</span>
            </div>
            <?php if (empty($tiposDatos)): ?>
                <div class="text-center py-10 text-[#8b6946]"><i class="fas fa-database text-3xl mb-2 opacity-40"></i><p>Sin datos aún</p></div>
            <?php else: ?>
                <?php $total = array_sum($tiposCantidades); ?>
                <div class="space-y-5">
                    <?php foreach ($tiposDatos as $idx => $tipo): 
                        $pct = $total > 0 ? round(($tipo['cantidad'] / $total) * 100) : 0;
                        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec489a', '#06b6d4'];
                        $randColor = $colors[$idx % count($colors)];
                    ?>
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <div class="flex items-center gap-2"><i class="fas fa-tag text-xs" style="color: <?= $randColor ?>"></i><span class="font-semibold text-[#5a3e1b]"><?= htmlspecialchars($tipo['tipo']) ?></span></div>
                            <span class="text-sm font-mono font-bold text-[#4b2e1a]"><?= $tipo['cantidad'] ?> <span class="text-gray-400 text-xs">(<?= $pct ?>%)</span></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden shadow-inner">
                            <div class="progress-bar bg-gradient-to-r from-[#2d6a4f] to-[#b87c4f] h-2.5 rounded-full" style="width: 0%" data-width="<?= $pct ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 pt-4 border-t border-[#f0e5d2] text-right">
                    <!-- Enlace a gestión de animales (si existe en otro módulo) -->
                    <a href="#" class="text-xs text-[#b87c4f] hover:text-[#9a623b] font-medium">Ver todos <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            <?php endif; ?>
        </div>

        <div class="glass-card p-6">
            <h3 class="font-bold text-[#5a3e1b] text-lg mb-5 flex items-center"><i class="fas fa-bolt text-[#f7b32b] mr-2"></i>Accesos rápidos</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <?php 
                $quickLinks = [
                    ['url' => '?pagina=registrar-evento', 'icon' => 'fa-clipboard-list', 'text' => 'Registrar Evento', 'color' => 'from-purple-500 to-purple-700'],
                    ['url' => '?pagina=registrar-parto', 'icon' => 'fa-child', 'text' => 'Registrar Parto', 'color' => 'from-amber-500 to-orange-600'],
                    ['url' => '?pagina=genealogia', 'icon' => 'fa-tree', 'text' => 'Genealogía', 'color' => 'from-green-500 to-emerald-600'],
                    ['url' => '?pagina=reportes', 'icon' => 'fa-chart-line', 'text' => 'Reportes', 'color' => 'from-indigo-500 to-indigo-700']
                ];
                foreach($quickLinks as $link): ?>
                <a href="<?= $link['url'] ?>" class="group btn-ripple flex flex-col items-center justify-center gap-2 bg-white hover:bg-gradient-to-r <?= $link['color'] ?> hover:text-white transition-all duration-300 rounded-xl py-3 px-2 shadow-sm border border-[#ecdbaa] hover:border-transparent text-[#5a3e1b] hover:shadow-md">
                    <i class="fas <?= $link['icon'] ?> text-xl group-hover:scale-110 transition-transform duration-200"></i>
                    <span class="text-xs font-semibold"><?= $link['text'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Últimos nacimientos + Alertas de preñez -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="glass-card overflow-hidden">
            <div class="border-b border-[#f0e5d2] px-6 py-4 bg-[#fef5e6]">
                <h3 class="font-bold text-[#5a3e1b] flex items-center"><i class="fas fa-baby text-[#b87c4f] mr-2 text-lg"></i>Últimos nacimientos</h3>
            </div>
            <div class="p-5 custom-scroll max-h-[380px] overflow-y-auto">
                <?php if (empty($ultimosPartos)): ?>
                    <div class="text-center py-10 text-[#8b6946]"><i class="fas fa-dove text-3xl mb-2 opacity-40"></i><p class="text-sm">No hay partos registrados aún</p></div>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($ultimosPartos as $p): ?>
                            <div class="group flex items-center gap-4 p-3 rounded-xl bg-[#fffef7] hover:bg-white hover:shadow-md transition-all duration-200 border border-transparent hover:border-[#ecdbaa]">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"><i class="fas fa-cow text-lg"></i></div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-[#4b2e1a] text-sm truncate">✨ <?= htmlspecialchars($p['cria_nombre'] ?? 'Cría sin nombre') ?></div>
                                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5"><i class="fas fa-female text-pink-400"></i> Madre: <?= htmlspecialchars($p['madre_nombre'] ?? '-') ?> <span class="w-1 h-1 bg-gray-300 rounded-full"></span> <i class="far fa-calendar-alt"></i> <?= htmlspecialchars($p['fecha_parto']) ?></div>
                                </div>
                                <a href="../../certificado/generar.php?parto_id=<?= $p['id'] ?>" target="_blank" class="bg-gradient-to-r from-[#2d6a4f] to-[#b87c4f] text-white text-xs px-3 py-1.5 rounded-lg hover:from-[#1f4d38] hover:to-[#9a623b] transition shadow-sm flex items-center gap-1"><i class="fas fa-file-pdf text-xs"></i> PDF</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if(!empty($ultimosPartos)): ?>
            <div class="border-t border-[#f0e5d2] px-5 py-3 bg-[#fef5e6] text-right">
                <a href="?pagina=registrar-parto" class="text-xs font-medium text-[#b87c4f] hover:text-[#9a623b]">Ver historial completo <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <?php endif; ?>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="border-b border-[#f0e5d2] px-6 py-4 bg-[#fef5e6] flex justify-between items-center">
                <h3 class="font-bold text-[#5a3e1b] flex items-center"><i class="fas fa-exclamation-triangle text-[#f7b32b] mr-2"></i>Alertas de preñez</h3>
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full font-semibold"><?= count($alertas) ?> pendientes</span>
            </div>
            <div class="p-5 custom-scroll max-h-[380px] overflow-y-auto">
                <?php if (empty($alertas)): ?>
                    <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-6 text-center text-green-700"><i class="fas fa-check-circle text-2xl mb-2"></i><p class="text-sm font-medium">No hay alertas activas. Todo en orden.</p></div>
                <?php else: ?>
                    <div class="space-y-4">
                        <?php foreach ($alertas as $alerta): 
                            $diasRest = (int) $alerta['dias_restantes'];
                            $totalGestacion = 285; // Valor por defecto, se podría calcular con los días de gestación del tipo
                            $progreso = max(0, min(100, round((($totalGestacion - max(0, $diasRest)) / $totalGestacion) * 100)));
                            if ($diasRest <= 0) {
                                $clase = 'border-l-8 border-red-500 bg-red-50';
                                $badge = '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-red-200 text-red-800">⚠️ URGENTE</span>';
                                $textoEstado = 'Parto vencido o inminente';
                                $barColor = 'bg-red-500';
                            } elseif ($diasRest <= 15) {
                                $clase = 'border-l-8 border-orange-400 bg-orange-50';
                                $badge = '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-orange-200 text-orange-800">🔔 Muy próximo</span>';
                                $textoEstado = "Faltan $diasRest días";
                                $barColor = 'bg-orange-500';
                            } elseif ($diasRest <= 30) {
                                $clase = 'border-l-8 border-yellow-400 bg-yellow-50';
                                $badge = '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-yellow-200 text-yellow-800">🕒 Alerta</span>';
                                $textoEstado = "Faltan $diasRest días";
                                $barColor = 'bg-yellow-500';
                            } else {
                                $clase = 'border-l-8 border-green-400 bg-green-50';
                                $badge = '<span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-green-200 text-green-800">✅ Estable</span>';
                                $textoEstado = "Parto en $diasRest días";
                                $barColor = 'bg-emerald-500';
                            }
                        ?>
                        <div class="alert-item rounded-xl <?= $clase ?> p-4 transition-all">
                            <div class="flex justify-between items-start flex-wrap gap-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap"><span class="font-bold text-[#4b2e1a]"><?= htmlspecialchars($alerta['name']) ?></span><span class="text-xs text-gray-500">(<?= htmlspecialchars($alerta['tag']) ?>)</span><?= $badge ?></div>
                                    <div class="text-xs text-gray-600 mt-1 flex flex-wrap gap-x-3 gap-y-1"><span><i class="far fa-calendar-check"></i> Preñez: <?= $alerta['fecha_prenez'] ?></span><span><i class="far fa-hourglass-half"></i> Parto est.: <?= $alerta['fecha_parto_estimada'] ?></span></div>
                                    <div class="mt-3"><div class="flex justify-between text-[11px] font-medium mb-1"><span>Progreso gestación</span><span><?= $progreso ?>%</span></div><div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden"><div class="progress-bar h-2 rounded-full <?= $barColor ?>" style="width: 0%" data-width="<?= $progreso ?>%"></div></div><div class="mt-2 text-right text-[11px] font-semibold <?= str_replace('bg-','text-', $barColor) ?>"><?= $textoEstado ?></div></div>
                                </div>
                                <form method="POST" action="../../procesos/descartar_alerta.php" class="mt-2">
                                    <input type="hidden" name="descartar_alerta" value="<?= $alerta['id'] ?>">
                                    <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded-full transition" onclick="return confirm('¿Descartar esta alerta?')">Marcar como leído</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="text-center text-[#e2d4b5] text-xs pt-6 border-t border-[#e2d4b5]/30 flex flex-wrap justify-center gap-4">
        <span><i class="far fa-clock"></i> Datos en tiempo real</span>
        <span><i class="fas fa-chart-simple"></i> Gestión eficiente</span>
        <span><i class="fas fa-shield-alt"></i> Seguridad de datos</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de contadores
    const counters = document.querySelectorAll('.countup');
    const speed = 80;
    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCounter = () => {
                const target = parseInt(counter.getAttribute('data-value'));
                let current = parseInt(counter.innerText);
                if (isNaN(current)) current = 0;
                const increment = Math.ceil(target / 20);
                if (current < target) {
                    let nextVal = current + increment;
                    if(nextVal > target) nextVal = target;
                    counter.innerText = nextVal;
                    setTimeout(updateCounter, speed);
                } else {
                    counter.innerText = target;
                }
            };
            updateCounter();
        });
    };
    
    const progressBars = document.querySelectorAll('.progress-bar');
    const animateBars = () => {
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width');
            if(width) bar.style.width = width;
        });
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if(entry.isIntersecting) {
                animateCounters();
                animateBars();
                observer.disconnect();
            }
        });
    }, { threshold: 0.2 });
    
    const dashboardContainer = document.querySelector('.max-w-7xl');
    if(dashboardContainer) observer.observe(dashboardContainer);
    else {
        animateCounters();
        animateBars();
    }

    // Gráficos
    <?php if (!empty($tiposDatos)): ?>
    new Chart(document.getElementById('chartTipos'), { 
        type: 'pie', 
        data: { 
            labels: <?= json_encode($tiposNombres) ?>, 
            datasets: [{ 
                data: <?= json_encode($tiposCantidades) ?>, 
                backgroundColor: ['#3b82f6','#10b981','#f59e0b','#8b5cf6','#ec489a','#06b6d4'], 
                borderWidth: 0, 
                hoverOffset: 8 
            }] 
        }, 
        options: { 
            responsive: true, 
            maintainAspectRatio: true, 
            plugins: { 
                legend: { position: 'bottom', labels: { font: { size: 11 } } } 
            } 
        } 
    });
    <?php endif; ?>
    <?php if (!empty($partosMensuales)): ?>
    new Chart(document.getElementById('chartPartos'), { 
        type: 'bar', 
        data: { 
            labels: <?= json_encode($mesesLabels) ?>, 
            datasets: [{ 
                label: 'Nacimientos', 
                data: <?= json_encode($partosCantidades) ?>, 
                backgroundColor: 'rgba(16, 185, 129, 0.7)', 
                borderColor: '#10b981', 
                borderRadius: 8 
            }] 
        }, 
        options: { 
            responsive: true, 
            maintainAspectRatio: true, 
            plugins: { legend: { display: false } }, 
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } 
        } 
    });
    <?php endif; ?>
});
</script>

<!-- Añadir contenedores para los gráficos (los canvas ya existen en el HTML anterior, los pongo aquí para que no falten) -->
<div style="display:none;">
    <canvas id="chartTipos"></canvas>
    <canvas id="chartPartos"></canvas>
</div>

</body>
</html>