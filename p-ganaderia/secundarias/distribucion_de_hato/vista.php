<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
//distribucion de hato
extract($datosVista);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribución de Hato · Panel Interactivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { background: #f8fafc; }
        .chart-container { position: relative; width: 100%; }
        .legend-dot { display: inline-block; width: 12px; height: 12px; border-radius: 50%; margin-right: 8px; }
        .clickable-row { transition: background-color 0.2s, box-shadow 0.2s; }
        .clickable-row:hover { background-color: #f3f4f6; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .scrollable-table { max-height: 300px; overflow-y: auto; }
        .tab-btn.active { border-bottom-color: #3b82f6; color: #1d4ed8; }
        .detail-table {
            width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem;
            border-radius: 0.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .detail-table thead th { background-color: #f8fafc; color: #334155; font-weight: 600; padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .detail-table tbody td { padding: 0.6rem 1rem; border-bottom: 1px solid #f1f5f9; color: #1e293b; }
        .detail-table tbody tr:last-child td { border-bottom: none; }
        .detail-table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .detail-table tbody tr:nth-child(odd) { background-color: #ffffff; }
        .detail-table tbody tr:hover { background-color: #f1f5f9; }
        .back-to-chart {
            display: inline-flex; align-items: center; gap: 0.25rem; color: #2563eb;
            font-size: 0.875rem; font-weight: 500; margin-bottom: 0.75rem; padding: 0.25rem 0.5rem;
            border-radius: 0.375rem; transition: background-color 0.2s;
        }
        .back-to-chart:hover { background-color: #eff6ff; text-decoration: underline; }
        .category-card {
            background-color: white; border-radius: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9; margin-bottom: 0.5rem;
        }
    </style>
</head>
<body class="p-4 md:p-6 font-sans antialiased">
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            📊 Distribución del Hato
        </h1>
        <p class="text-gray-600 mt-1">Análisis interactivo de razas, sexo, actividad, especie y establo</p>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6">
        <button class="tab-btn active px-4 py-2 font-medium text-sm border-b-2 border-transparent hover:border-gray-300 focus:outline-none" data-tab="tab-raza">🐄 Raza</button>
        <button class="tab-btn px-4 py-2 font-medium text-sm border-b-2 border-transparent hover:border-gray-300 focus:outline-none" data-tab="tab-sexo">⚥ Sexo</button>
        <button class="tab-btn px-4 py-2 font-medium text-sm border-b-2 border-transparent hover:border-gray-300 focus:outline-none" data-tab="tab-actividad">📋 Actividad</button>
        <button class="tab-btn px-4 py-2 font-medium text-sm border-b-2 border-transparent hover:border-gray-300 focus:outline-none" data-tab="tab-especie">🐾 Especie</button>
        <button class="tab-btn px-4 py-2 font-medium text-sm border-b-2 border-transparent hover:border-gray-300 focus:outline-none" data-tab="tab-establo">🏠 Establo</button>
        <button class="tab-btn px-4 py-2 font-medium text-sm border-b-2 border-transparent hover:border-gray-300 focus:outline-none" data-tab="tab-crecimiento">📈 Crecimiento</button>
    </div>

    <!-- RAZA -->
    <div id="tab-raza" class="tab-content">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">🐄 Distribución por Raza</h2>
                    <p class="text-3xl font-bold text-gray-800"><?= $totalRazas ?></p>
                    <p class="text-sm text-gray-500 mb-4">Total de animales</p>
                    <div class="space-y-2">
                        <?php 
                        $colores_raza = ["#3b82f6", "#ef4444", "#22c55e", "#f59e0b", "#8b5cf6", "#14b8a6"];
                        foreach ($razas as $index => $r): 
                            $porcentaje = $totalRazas > 0 ? round(($r['total'] / $totalRazas) * 100, 1) : 0;
                            $breed_value = htmlspecialchars($r['breed'] ?: 'Sin raza');
                        ?>
                        <div class="category-card flex items-center justify-between p-2 cursor-pointer clickable-row"
                             onclick="showDetail('raza', '<?= $breed_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_raza[$index % count($colores_raza)] ?>;"></span>
                                <span class="font-medium text-gray-700"><?= $breed_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900"><?= $r['total'] ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-raza-container" class="chart-container" style="height: 250px;">
                        <canvas id="chartRazas"></canvas>
                    </div>
                    <div id="detalle-raza" class="hidden p-4 bg-white rounded-lg border border-gray-200 mt-4">
                        <button class="back-to-chart" onclick="hideDetail('raza')"><i class="fas fa-arrow-left text-xs"></i> Volver al gráfico</button>
                        <div id="detalle-raza-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEXO -->
    <div id="tab-sexo" class="tab-content hidden">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">⚥ Distribución por Sexo</h2>
                    <p class="text-3xl font-bold text-gray-800"><?= $totalSexos ?></p>
                    <p class="text-sm text-gray-500 mb-4">Total de animales</p>
                    <div class="space-y-2">
                        <?php 
                        foreach ($sexos as $s): 
                            $porcentaje = $totalSexos > 0 ? round(($s['total'] / $totalSexos) * 100, 1) : 0;
                            $sexo_valor = htmlspecialchars($s['gender']);
                            $color = ($s['gender'] === 'H') ? '#ec4899' : '#3b82f6';
                        ?>
                        <div class="category-card flex items-center justify-between p-2 cursor-pointer clickable-row"
                             onclick="showDetail('sexo', '<?= $sexo_valor ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $color ?>;"></span>
                                <span class="font-medium text-gray-700 capitalize"><?= ($s['gender'] === 'H') ? 'Hembra' : 'Macho' ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900"><?= $s['total'] ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-sexo-container" class="chart-container" style="height: 250px;">
                        <canvas id="chartSexo"></canvas>
                    </div>
                    <div id="detalle-sexo" class="hidden p-4 bg-white rounded-lg border border-gray-200 mt-4">
                        <button class="back-to-chart" onclick="hideDetail('sexo')"><i class="fas fa-arrow-left text-xs"></i> Volver al gráfico</button>
                        <div id="detalle-sexo-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTIVIDAD -->
    <div id="tab-actividad" class="tab-content hidden">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">📋 Actividad del Hato</h2>
                    <p class="text-3xl font-bold text-gray-800"><?= $totalActividad ?></p>
                    <p class="text-sm text-gray-500 mb-4">Total registrado en actividad</p>
                    <div class="space-y-2">
                        <?php 
                        $colores_actividad = ["#8b5cf6", "#ec4899", "#f59e0b", "#10b981", "#ef4444", "#3b82f6"];
                        foreach ($actividades as $index => $a): 
                            $porcentaje = $totalActividad > 0 ? round(($a['total'] / $totalActividad) * 100, 1) : 0;
                            $status_value = htmlspecialchars($a['status'] ?: 'Sin clasificar');
                        ?>
                        <div class="category-card flex items-center justify-between p-2 cursor-pointer clickable-row"
                             onclick="showDetail('actividad', '<?= $status_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_actividad[$index % count($colores_actividad)] ?>;"></span>
                                <span class="font-medium text-gray-700"><?= $status_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900"><?= $a['total'] ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-actividad-container" class="chart-container" style="height: 250px;">
                        <canvas id="chartActividad"></canvas>
                    </div>
                    <div id="detalle-actividad" class="hidden p-4 bg-white rounded-lg border border-gray-200 mt-4">
                        <button class="back-to-chart" onclick="hideDetail('actividad')"><i class="fas fa-arrow-left text-xs"></i> Volver al gráfico</button>
                        <div id="detalle-actividad-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ESPECIE (Nuevo) -->
    <div id="tab-especie" class="tab-content hidden">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">🐾 Distribución por Especie</h2>
                    <p class="text-3xl font-bold text-gray-800"><?= $totalEspecies ?></p>
                    <p class="text-sm text-gray-500 mb-4">Total de animales</p>
                    <div class="space-y-2">
                        <?php 
                        $colores_especie = ["#0ea5e9", "#8b5cf6", "#f59e0b", "#10b981", "#ef4444"];
                        foreach ($especies as $index => $e): 
                            $porcentaje = $totalEspecies > 0 ? round(($e['total'] / $totalEspecies) * 100, 1) : 0;
                            $especie_value = htmlspecialchars($e['animal_type']);
                        ?>
                        <div class="category-card flex items-center justify-between p-2 cursor-pointer clickable-row"
                             onclick="showDetail('especie', '<?= $especie_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_especie[$index % count($colores_especie)] ?>;"></span>
                                <span class="font-medium text-gray-700"><?= $especie_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900"><?= $e['total'] ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-especie-container" class="chart-container" style="height: 250px;">
                        <canvas id="chartEspecie"></canvas>
                    </div>
                    <div id="detalle-especie" class="hidden p-4 bg-white rounded-lg border border-gray-200 mt-4">
                        <button class="back-to-chart" onclick="hideDetail('especie')"><i class="fas fa-arrow-left text-xs"></i> Volver al gráfico</button>
                        <div id="detalle-especie-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ESTABLO (Nuevo) -->
    <div id="tab-establo" class="tab-content hidden">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">🏠 Distribución por Establo</h2>
                    <p class="text-3xl font-bold text-gray-800"><?= $totalEstablos ?></p>
                    <p class="text-sm text-gray-500 mb-4">Total de animales</p>
                    <div class="space-y-2">
                        <?php 
                        $colores_establo = ["#6366f1", "#14b8a6", "#f43f5e", "#fbbf24", "#8b5cf6"];
                        foreach ($establos as $index => $e): 
                            $porcentaje = $totalEstablos > 0 ? round(($e['total'] / $totalEstablos) * 100, 1) : 0;
                            $establo_value = htmlspecialchars($e['facility']);
                        ?>
                        <div class="category-card flex items-center justify-between p-2 cursor-pointer clickable-row"
                             onclick="showDetail('establo', '<?= $establo_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_establo[$index % count($colores_establo)] ?>;"></span>
                                <span class="font-medium text-gray-700"><?= $establo_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900"><?= $e['total'] ?></span>
                                <span class="text-sm text-gray-500 ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-establo-container" class="chart-container" style="height: 250px;">
                        <canvas id="chartEstablo"></canvas>
                    </div>
                    <div id="detalle-establo" class="hidden p-4 bg-white rounded-lg border border-gray-200 mt-4">
                        <button class="back-to-chart" onclick="hideDetail('establo')"><i class="fas fa-arrow-left text-xs"></i> Volver al gráfico</button>
                        <div id="detalle-establo-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CRECIMIENTO -->
    <div id="tab-crecimiento" class="tab-content hidden">
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-lg p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-gray-50/80 rounded-xl p-5 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">📈 Comparativa de Crecimiento Individual</h2>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="bg-white p-3 rounded-lg border border-gray-100 text-center">
                            <p class="text-sm text-gray-500">Peso promedio</p>
                            <p class="text-2xl font-bold text-emerald-600"><?= number_format($estadisticas['media'], 1) ?><span class="text-sm"> kg</span></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-100 text-center">
                            <p class="text-sm text-gray-500">Animales</p>
                            <p class="text-2xl font-bold text-gray-700"><?= $estadisticas['total'] ?></p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-100 text-center">
                            <p class="text-sm text-gray-500">Mínimo</p>
                            <p class="text-lg font-semibold text-gray-700"><?= number_format($estadisticas['min'], 1) ?> kg</p>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-gray-100 text-center">
                            <p class="text-sm text-gray-500">Máximo</p>
                            <p class="text-lg font-semibold text-gray-700"><?= number_format($estadisticas['max'], 1) ?> kg</p>
                        </div>
                    </div>
                    <div class="scrollable-table border border-gray-200 rounded-lg bg-white">
                        <table class="min-w-full text-sm detail-table">
                            <thead class="sticky top-0 bg-gray-100 text-gray-600">
                                <tr>
                                    <th class="text-left py-1 px-2">Código</th>
                                    <th class="text-left py-1 px-2">Nombre</th>
                                    <th class="text-left py-1 px-2">Raza</th>
                                    <th class="text-right py-1 px-2">Peso</th>
                                    <th class="text-right py-1 px-2">Dif.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($animalesPeso)): ?>
                                    <?php foreach ($animalesPeso as $a): 
                                        $diff = $a['weight'] - $estadisticas['media'];
                                        $diff_color = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                                    ?>
                                    <tr class="border-b border-gray-100">
                                        <td class="py-1 px-2 font-mono"><?= htmlspecialchars($a['code']) ?></td>
                                        <td class="py-1 px-2"><?= htmlspecialchars($a['name']) ?></td>
                                        <td class="py-1 px-2"><?= htmlspecialchars($a['breed'] ?: '-') ?></td>
                                        <td class="py-1 px-2 text-right font-mono"><?= number_format($a['weight'], 1) ?></td>
                                        <td class="py-1 px-2 text-right font-mono <?= $diff_color ?>"><?= ($diff >= 0 ? '+' : '') . number_format($diff, 1) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-gray-400">No hay animales con peso registrado</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="lg:w-2/3">
                    <div id="chart-crecimiento-container" class="chart-container" style="height: <?= max(300, $estadisticas['total'] * 25) ?>px;">
                        <canvas id="chartIndividual"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const animalesPeso = <?= json_encode($animalesPeso) ?>;
    const mediaPeso = <?= $estadisticas['media'] ?>;
    const allCows = <?= json_encode($todosLosAnimales) ?>;
    const razas = <?= json_encode($razas) ?>;
    const sexos = <?= json_encode($sexos) ?>;
    const actividades = <?= json_encode($actividades) ?>;
    const especies = <?= json_encode($especies) ?>;
    const establos = <?= json_encode($establos) ?>;

    // Instancias de gráficos por pestaña
    const charts = {
        razas: null,
        sexos: null,
        actividad: null,
        especie: null,
        establo: null,
        crecimiento: null
    };

    function createDoughnutChart(ctxId, labels, data, colors) {
        const ctx = document.getElementById(ctxId).getContext('2d');
        return new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { size: 12 } } }
                },
                cutout: '60%',
            }
        });
    }

    function initRazaChart() {
        if (charts.razas) return;
        const colors = ["#3b82f6", "#ef4444", "#22c55e", "#f59e0b", "#8b5cf6", "#14b8a6"];
        charts.razas = createDoughnutChart('chartRazas', razas.map(r => r.breed || 'Sin raza'), razas.map(r => parseInt(r.total)), razas.map((_, i) => colors[i % colors.length]));
    }

    function initSexoChart() {
        if (charts.sexos) return;
        const colors = sexos.map(s => s.gender === 'H' ? '#ec4899' : '#3b82f6');
        charts.sexos = createDoughnutChart('chartSexo', sexos.map(s => s.gender === 'H' ? 'Hembra' : 'Macho'), sexos.map(s => parseInt(s.total)), colors);
    }

    function initActividadChart() {
        if (charts.actividad) return;
        const colors = ["#8b5cf6", "#ec4899", "#f59e0b", "#10b981", "#ef4444", "#3b82f6"];
        charts.actividad = createDoughnutChart('chartActividad', actividades.map(a => a.status || 'Sin clasificar'), actividades.map(a => parseInt(a.total)), actividades.map((_, i) => colors[i % colors.length]));
    }

    function initEspecieChart() {
        if (charts.especie) return;
        const colors = ["#0ea5e9", "#8b5cf6", "#f59e0b", "#10b981", "#ef4444"];
        charts.especie = createDoughnutChart('chartEspecie', especies.map(e => e.animal_type), especies.map(e => parseInt(e.total)), especies.map((_, i) => colors[i % colors.length]));
    }

    function initEstabloChart() {
        if (charts.establo) return;
        const colors = ["#6366f1", "#14b8a6", "#f43f5e", "#fbbf24", "#8b5cf6"];
        charts.establo = createDoughnutChart('chartEstablo', establos.map(e => e.facility), establos.map(e => parseInt(e.total)), establos.map((_, i) => colors[i % colors.length]));
    }

    function initCrecimientoChart() {
        if (charts.crecimiento) return;
        if (animalesPeso.length > 0) {
            const labels = animalesPeso.map(a => a.code + ' (' + (a.breed || '?') + ')');
            const data = animalesPeso.map(a => parseFloat(a.weight));
            const backgroundColors = data.map(w => w >= mediaPeso ? 'rgba(16, 185, 129, 0.8)' : 'rgba(239, 68, 68, 0.8)');
            const ctx = document.getElementById('chartIndividual').getContext('2d');
            charts.crecimiento = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Peso (kg)',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(c => c.replace('0.8', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (ctx) => ctx.raw + ' kg' } },
                        annotation: {
                            annotations: {
                                lineaMedia: {
                                    type: 'line',
                                    xMin: mediaPeso,
                                    xMax: mediaPeso,
                                    borderColor: '#3b82f6',
                                    borderWidth: 2,
                                    borderDash: [6, 4],
                                    label: {
                                        display: true,
                                        content: 'Promedio ' + mediaPeso.toFixed(1) + ' kg',
                                        position: 'end',
                                        backgroundColor: '#3b82f6',
                                        font: { size: 11 }
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Peso (kg)' }, grid: { color: '#e2e8f0' } },
                        y: { ticks: { autoSkip: false } }
                    }
                }
            });
        }
    }

    function showDetail(type, value) {
        const detailContainer = document.getElementById('detalle-' + type);
        const chartContainer = document.getElementById('chart-' + type + '-container');
        if (!detailContainer || !chartContainer) return;

        if (!detailContainer.classList.contains('hidden') && detailContainer.dataset.currentValue === value) {
            detailContainer.classList.add('hidden');
            chartContainer.classList.remove('hidden');
            detailContainer.dataset.currentValue = '';
            return;
        }

        let filtered = allCows.filter(c => {
            if (type === 'raza') return (c.breed || 'Sin raza') === value;
            else if (type === 'sexo') return c.gender === value;
            else if (type === 'actividad') return (c.status || 'Sin clasificar') === value;
            else if (type === 'especie') return c.animal_type === value;
            else if (type === 'establo') return c.facility === value;
            return false;
        });

        const colLabel = {
            raza: 'Raza',
            sexo: 'Sexo',
            actividad: 'Estado',
            especie: 'Especie',
            establo: 'Establo'
        }[type] || '';

        let html = '<table class="detail-table"><thead><tr><th>ID</th><th>Código</th><th>Nombre</th><th>' + colLabel + '</th></tr></thead><tbody>';
        if (filtered.length > 0) {
            filtered.forEach(c => {
                let lastCol;
                if (type === 'raza') lastCol = c.breed || 'Sin raza';
                else if (type === 'sexo') lastCol = c.gender === 'H' ? 'Hembra' : 'Macho';
                else if (type === 'actividad') lastCol = c.status || 'Sin clasificar';
                else if (type === 'especie') lastCol = c.animal_type || '-';
                else if (type === 'establo') lastCol = c.facility || '-';
                html += `<tr>
                            <td>${c.id}</td>
                            <td class="font-mono">${c.code}</td>
                            <td>${c.name}</td>
                            <td>${lastCol}</td>
                         </tr>`;
            });
        } else {
            html += '<tr><td colspan="4" class="text-center py-4 text-gray-400">No hay animales en esta categoría.</td></tr>';
        }
        html += '</tbody></table>';

        document.getElementById('detalle-' + type + '-content').innerHTML = html;
        detailContainer.dataset.currentValue = value;
        detailContainer.classList.remove('hidden');
        chartContainer.classList.add('hidden');
    }

    function hideDetail(type) {
        document.getElementById('detalle-' + type).classList.add('hidden');
        document.getElementById('chart-' + type + '-container').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        function switchTab(tabId) {
            tabContents.forEach(tc => tc.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            tabs.forEach(tab => tab.classList.remove('active'));
            const activeTab = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
            if (activeTab) activeTab.classList.add('active');

            if (tabId === 'tab-raza') initRazaChart();
            else if (tabId === 'tab-sexo') initSexoChart();
            else if (tabId === 'tab-actividad') initActividadChart();
            else if (tabId === 'tab-especie') initEspecieChart();
            else if (tabId === 'tab-establo') initEstabloChart();
            else if (tabId === 'tab-crecimiento') initCrecimientoChart();
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                switchTab(this.dataset.tab);
            });
        });

        switchTab('tab-raza');
    });
</script>
</body>
</html>