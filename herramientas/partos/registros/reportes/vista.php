<?php
/**
 * @var int $totalAnimales
 * @var int $totalPartos
 * @var int $totalPrenadas
 * @var array $distribucionTipos
 * @var array $partosMensuales
 * @var array $partosPorTipo
 * @var array $ultimosPartos
 * @var array $tiposNombres
 * @var array $tiposCantidades
 * @var array $mesesLabels
 * @var array $partosCantidades
 * @var array $tipoPartoLabels
 * @var array $tipoPartoData
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Estadísticos | Hato</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: #1a4d2a;
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                              repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-attachment: fixed;
            padding: 1.5rem;
        }
        .glass-card {
            background: rgba(255, 251, 240, 0.97);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            border: 1px solid #e2d4b5;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .glass-card:hover { transform: translateY(-2px); box-shadow: 0 20px 30px -12px rgba(0,0,0,0.25); }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); }
        .animate-fade-up { animation: fadeUp 0.5s ease-out forwards; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#f9eec1] flex items-center gap-3">
                <i class="fas fa-chart-line text-[#f7b32b]"></i> Reportes Estadísticos
            </h1>
            <p class="text-[#e2d4b5] mt-1 text-sm">Análisis de población y partos</p>
        </div>
        <div class="flex items-center gap-3 glass-card px-5 py-2 shadow-sm">
            <i class="fas fa-calendar-alt text-[#b87c4f]"></i>
            <span class="text-sm font-semibold text-[#5a3e1b]"><?= date('d/m/Y') ?></span>
            <div class="w-px h-5 bg-[#e2d4b5]"></div>
            <i class="fas fa-sync-alt text-gray-400 text-xs hover:text-[#b87c4f] transition cursor-pointer" onclick="location.reload();" title="Actualizar"></i>
        </div>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-5 mb-10">
        <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-5 text-white animate-fade-up">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold"><?= $totalAnimales ?></div>
                    <div class="text-xs opacity-90 mt-1">Total Animales</div>
                </div>
                <i class="fas fa-paw text-3xl opacity-80"></i>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg p-5 text-white animate-fade-up" style="animation-delay:0.05s">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold"><?= $totalPartos ?></div>
                    <div class="text-xs opacity-90 mt-1">Partos registrados</div>
                </div>
                <i class="fas fa-baby text-3xl opacity-80"></i>
            </div>
        </div>
        <div class="stat-card bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl shadow-lg p-5 text-white animate-fade-up" style="animation-delay:0.1s">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold"><?= $totalPrenadas ?></div>
                    <div class="text-xs opacity-90 mt-1">Hembras preñadas</div>
                </div>
                <i class="fas fa-heartbeat text-3xl opacity-80"></i>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#5a3e1b] text-lg"><i class="fas fa-chart-pie text-[#b87c4f] mr-2"></i>Distribución por tipo</h3>
                <span class="text-xs bg-[#e9dfc7] text-[#5a3e1b] px-2 py-1 rounded-full"><?= array_sum($tiposCantidades) ?> total</span>
            </div>
            <?php if (empty($tiposNombres)): ?>
                <div class="text-center py-12 text-[#8b6946]"><i class="fas fa-database text-4xl mb-2 opacity-30"></i><p>Sin datos</p></div>
            <?php else: ?>
                <canvas id="chartTipos" class="w-full h-64 md:h-72"></canvas>
                <div class="mt-5 grid grid-cols-2 gap-2 text-sm">
                    <?php foreach ($distribucionTipos as $item): ?>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-1">
                            <span class="text-gray-600"><?= htmlspecialchars($item['tipo']) ?></span>
                            <span class="font-bold text-gray-800"><?= $item['cantidad'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#5a3e1b] text-lg"><i class="fas fa-chart-line text-amber-500 mr-2"></i>Evolución de partos (últimos 6 meses)</h3>
                <span class="text-xs bg-[#e9dfc7] text-[#5a3e1b] px-2 py-1 rounded-full"><?= array_sum($partosCantidades) ?> partos</span>
            </div>
            <?php if (empty($partosMensuales)): ?>
                <div class="text-center py-12 text-[#8b6946]"><i class="fas fa-chart-line text-4xl mb-2 opacity-30"></i><p>No hay partos en los últimos 6 meses</p></div>
            <?php else: ?>
                <canvas id="chartPartos" class="w-full h-64 md:h-72"></canvas>
                <div class="mt-4 text-center text-xs text-gray-500"><i class="fas fa-info-circle"></i> Tendencia mensual de nacimientos</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tabla de últimos partos y gráfico de partos por tipo -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#5a3e1b] text-lg"><i class="fas fa-clock text-indigo-500 mr-2"></i>Últimos partos registrados</h3>
                <a href="?pagina=registrar-parto" class="text-xs text-[#b87c4f] hover:text-[#9a623b] font-medium">Ver todos <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <?php if (empty($ultimosPartos)): ?>
                <div class="text-center py-8 text-[#8b6946]"><i class="fas fa-baby-carriage text-3xl mb-2 opacity-30"></i><p>No hay partos registrados aún</p></div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Fecha</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Cría</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500">Madre</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($ultimosPartos as $p): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-600"><?= htmlspecialchars($p['fecha_parto']) ?></td>
                                    <td class="px-4 py-2 text-sm font-medium text-gray-800"><?= htmlspecialchars($p['cria_nombre'] ?? '-') ?> <span class="text-gray-400 text-xs">(<?= htmlspecialchars($p['cria_tag'] ?? '-') ?>)</span></td>
                                    <td class="px-4 py-2 text-sm text-gray-600"><?= htmlspecialchars($p['madre_nombre'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-[#5a3e1b] text-lg"><i class="fas fa-chart-bar text-green-500 mr-2"></i>Partos por tipo de animal</h3>
                <span class="text-xs bg-[#e9dfc7] text-[#5a3e1b] px-2 py-1 rounded-full"><?= array_sum($tipoPartoData) ?> total</span>
            </div>
            <?php if (empty($tipoPartoLabels)): ?>
                <div class="text-center py-12 text-[#8b6946]"><i class="fas fa-chart-bar text-4xl mb-2 opacity-30"></i><p>Sin datos</p></div>
            <?php else: ?>
                <canvas id="chartPartosTipo" class="w-full h-64 md:h-72"></canvas>
                <div class="mt-4 text-center text-xs text-gray-500"><i class="fas fa-info-circle"></i> Distribución de nacimientos por especie</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if (!empty($tiposNombres)): ?>
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

    <?php if (!empty($tipoPartoLabels)): ?>
    new Chart(document.getElementById('chartPartosTipo'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($tipoPartoLabels) ?>,
            datasets: [{
                label: 'Partos',
                data: <?= json_encode($tipoPartoData) ?>,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec489a', '#06b6d4', '#f97316', '#14b8a6'],
                borderRadius: 6
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
</body>
</html>