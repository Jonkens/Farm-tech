<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
extract($datosVista);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Distribución del Hato · Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #1a4d2a; /* verde oscuro base */
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                              repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-size: 30px 30px, 12px 12px;
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* Tarjetas principales */
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

        /* Pestañas */
        .tab-btn {
            background: transparent;
            color: #e9e2c7;
            font-weight: 500;
            border-bottom: 3px solid transparent;
            padding: 0.5rem 1.2rem;
            margin-right: 0.25rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .tab-btn.active {
            color: #f7e05e;
            border-bottom-color: #f7b32b;
            background: rgba(255, 235, 190, 0.1);
            border-radius: 1rem 1rem 0 0;
        }
        .tab-btn:hover:not(.active) {
            border-bottom-color: #f7b32b;
            color: #fff2cc;
        }

        /* Elementos internos */
        .stat-box {
            background: #fef5e6;
            border-radius: 1.2rem;
            padding: 0.8rem;
            text-align: center;
            border: 1px solid #ecdbaa;
            transition: all 0.2s;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2f6b47;
            line-height: 1.2;
        }
        .stat-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #a77c48;
        }

        .legend-dot {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            margin-right: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .clickable-row {
            cursor: pointer;
            transition: background 0.15s, border-left 0.1s;
            border-left: 3px solid transparent;
        }
        .clickable-row:hover {
            background-color: #fff3e0;
            border-left-color: #b87c4f;
        }

        /* Tablas de detalle */
        .detail-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.85rem;
            background: #fffef7;
            border-radius: 1rem;
            overflow: hidden;
        }
        .detail-table thead th {
            background: #e9dfc7;
            color: #4a3b22;
            font-weight: 600;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #d9c7a3;
        }
        .detail-table tbody td {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid #f0e5d2;
            color: #3e2e1a;
        }
        .detail-table tbody tr:hover {
            background-color: #fff3e0;
        }

        .scrollable-table {
            max-height: 320px;
            overflow-y: auto;
            border-radius: 1rem;
            background: #fffef7;
            border: 1px solid #e2d1b6;
        }

        .back-to-chart {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #d9c7a3;
            color: #4a3b22;
            padding: 0.3rem 0.9rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: background 0.2s;
        }
        .back-to-chart:hover {
            background: #c4ae82;
            color: #2d2b1f;
        }

        .chart-container {
            background: #fffef5;
            border-radius: 1rem;
            padding: 0.5rem;
            border: 1px solid #ecdbaa;
        }

        .badge-ganado {
            background: #2f6b4720;
            color: #f9eec1;
            padding: 0.2rem 0.7rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 500;
            backdrop-filter: blur(2px);
        }

        h1, h2, h3 {
            font-weight: 700;
            letter-spacing: -0.3px;
        }
    </style>
</head>
<body class="antialiased">
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
                <i class="fas fa-chart-pie text-[#f7b32b]"></i> Distribución del Hato
            </h1>
            <p class="text-[#e2d4b5] mt-1 text-sm">Análisis visual de la composición del ganado</p>
        </div>
        <div class="flex gap-2">
            <span class="badge-ganado"><i class="fas-regular fa-cow mr-1"></i> Datos en tiempo real</span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex flex-wrap gap-1 border-b border-[#ecdbaa]/40 mb-6 pb-1">
        <button class="tab-btn active" data-tab="tab-raza"> Por Raza</button>
        <button class="tab-btn" data-tab="tab-sexo"> Por Sexo</button>
        <button class="tab-btn" data-tab="tab-actividad"> Por Actividad</button>
        <button class="tab-btn" data-tab="tab-especie"> Por Especie</button>
        <button class="tab-btn" data-tab="tab-establo"> Por Establo</button>
        <button class="tab-btn" data-tab="tab-crecimiento"> Crecimiento</button>
    </div>

    <!-- RAZA -->
    <div id="tab-raza" class="tab-content">
        <div class="card-ganadero p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-[#fef5e6] rounded-xl p-4 border border-[#e7d5b9]">
                    <h2 class="text-xl font-bold text-[#5a3e1b] mb-3"><i class="fas fa-paw mr-2 text-[#b87c4f]"></i> Razas</h2>
                    <div class="stat-box mb-4">
                        <div class="stat-number"><?= $totalRazas ?></div>
                        <div class="stat-label">Total de animales</div>
                    </div>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        <?php 
                        $colores_raza = ["#2d6a4f", "#b87c4f", "#d4a373", "#8b5a2b", "#a3b18a", "#6c584c"];
                        foreach ($razas as $index => $r): 
                            $porcentaje = $totalRazas > 0 ? round(($r['total'] / $totalRazas) * 100, 1) : 0;
                            $breed_value = htmlspecialchars($r['breed'] ?: 'Sin raza');
                        ?>
                        <div class="clickable-row flex items-center justify-between p-2 rounded-lg"
                             onclick="showDetail('raza', '<?= $breed_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_raza[$index % count($colores_raza)] ?>;"></span>
                                <span class="font-medium text-[#4b2e1a]"><?= $breed_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-[#2d6a4f]"><?= $r['total'] ?></span>
                                <span class="text-xs text-[#8b6946] ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-raza-container" class="chart-container" style="height: 280px;">
                        <canvas id="chartRazas"></canvas>
                    </div>
                    <div id="detalle-raza" class="hidden p-4 bg-[#fffaf0] rounded-xl border border-[#ecdbaa] mt-4">
                        <button class="back-to-chart" onclick="hideDetail('raza')"><i class="fas fa-arrow-left"></i> Volver al gráfico</button>
                        <div id="detalle-raza-content" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEXO -->
    <div id="tab-sexo" class="tab-content hidden">
        <div class="card-ganadero p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-[#fef5e6] rounded-xl p-4 border border-[#e7d5b9]">
                    <h2 class="text-xl font-bold text-[#5a3e1b] mb-3"><i class="fas fa-venus-mars mr-2 text-[#b87c4f]"></i> Sexo</h2>
                    <div class="stat-box mb-4">
                        <div class="stat-number"><?= $totalSexos ?></div>
                        <div class="stat-label">Total de animales</div>
                    </div>
                    <div class="space-y-2">
                        <?php foreach ($sexos as $s): 
                            $porcentaje = $totalSexos > 0 ? round(($s['total'] / $totalSexos) * 100, 1) : 0;
                            $sexo_valor = htmlspecialchars($s['gender']);
                            $color = ($s['gender'] === 'F') ? '#d97706' : '#2d6a4f';
                            $nombreSexo = ($s['gender'] === 'F') ? 'Hembra' : 'Macho';
                        ?>
                        <div class="clickable-row flex items-center justify-between p-2 rounded-lg"
                             onclick="showDetail('sexo', '<?= $sexo_valor ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $color ?>;"></span>
                                <span class="font-medium text-[#4b2e1a]"><?= $nombreSexo ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-[#2d6a4f]"><?= $s['total'] ?></span>
                                <span class="text-xs text-[#8b6946] ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-sexo-container" class="chart-container" style="height: 280px;">
                        <canvas id="chartSexo"></canvas>
                    </div>
                    <div id="detalle-sexo" class="hidden p-4 bg-[#fffaf0] rounded-xl border border-[#ecdbaa] mt-4">
                        <button class="back-to-chart" onclick="hideDetail('sexo')"><i class="fas fa-arrow-left"></i> Volver al gráfico</button>
                        <div id="detalle-sexo-content" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTIVIDAD -->
    <div id="tab-actividad" class="tab-content hidden">
        <div class="card-ganadero p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-[#fef5e6] rounded-xl p-4 border border-[#e7d5b9]">
                    <h2 class="text-xl font-bold text-[#5a3e1b] mb-3"><i class="fas fa-clipboard-list mr-2 text-[#b87c4f]"></i> Actividad</h2>
                    <div class="stat-box mb-4">
                        <div class="stat-number"><?= $totalActividad ?></div>
                        <div class="stat-label">Total registros</div>
                    </div>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        <?php 
                        $colores_actividad = ["#8b5a2b", "#c08a5c", "#d4a373", "#6c584c", "#a3b18a", "#4f6d5e"];
                        foreach ($actividades as $index => $a): 
                            $porcentaje = $totalActividad > 0 ? round(($a['total'] / $totalActividad) * 100, 1) : 0;
                            $status_value = htmlspecialchars($a['status'] ?: 'Sin clasificar');
                        ?>
                        <div class="clickable-row flex items-center justify-between p-2 rounded-lg"
                             onclick="showDetail('actividad', '<?= $status_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_actividad[$index % count($colores_actividad)] ?>;"></span>
                                <span class="font-medium text-[#4b2e1a]"><?= ucfirst($status_value) ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-[#2d6a4f]"><?= $a['total'] ?></span>
                                <span class="text-xs text-[#8b6946] ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-actividad-container" class="chart-container" style="height: 280px;">
                        <canvas id="chartActividad"></canvas>
                    </div>
                    <div id="detalle-actividad" class="hidden p-4 bg-[#fffaf0] rounded-xl border border-[#ecdbaa] mt-4">
                        <button class="back-to-chart" onclick="hideDetail('actividad')"><i class="fas fa-arrow-left"></i> Volver al gráfico</button>
                        <div id="detalle-actividad-content" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ESPECIE -->
    <div id="tab-especie" class="tab-content hidden">
        <div class="card-ganadero p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-[#fef5e6] rounded-xl p-4 border border-[#e7d5b9]">
                    <h2 class="text-xl font-bold text-[#5a3e1b] mb-3"><i class="fas fa-dog mr-2 text-[#b87c4f]"></i> Especie</h2>
                    <div class="stat-box mb-4">
                        <div class="stat-number"><?= $totalEspecies ?></div>
                        <div class="stat-label">Total de animales</div>
                    </div>
                    <div class="space-y-2">
                        <?php 
                        $colores_especie = ["#2d6a4f", "#b87c4f", "#d4a373", "#8b5a2b"];
                        foreach ($especies as $index => $e): 
                            $porcentaje = $totalEspecies > 0 ? round(($e['total'] / $totalEspecies) * 100, 1) : 0;
                            $especie_value = htmlspecialchars($e['animal_type']);
                        ?>
                        <div class="clickable-row flex items-center justify-between p-2 rounded-lg"
                             onclick="showDetail('especie', '<?= $especie_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_especie[$index % count($colores_especie)] ?>;"></span>
                                <span class="font-medium text-[#4b2e1a]"><?= $especie_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-[#2d6a4f]"><?= $e['total'] ?></span>
                                <span class="text-xs text-[#8b6946] ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-especie-container" class="chart-container" style="height: 280px;">
                        <canvas id="chartEspecie"></canvas>
                    </div>
                    <div id="detalle-especie" class="hidden p-4 bg-[#fffaf0] rounded-xl border border-[#ecdbaa] mt-4">
                        <button class="back-to-chart" onclick="hideDetail('especie')"><i class="fas fa-arrow-left"></i> Volver al gráfico</button>
                        <div id="detalle-especie-content" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ESTABLO -->
    <div id="tab-establo" class="tab-content hidden">
        <div class="card-ganadero p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-[#fef5e6] rounded-xl p-4 border border-[#e7d5b9]">
                    <h2 class="text-xl font-bold text-[#5a3e1b] mb-3"><i class="fas fa-building mr-2 text-[#b87c4f]"></i> Establo</h2>
                    <div class="stat-box mb-4">
                        <div class="stat-number"><?= $totalEstablos ?></div>
                        <div class="stat-label">Total de animales</div>
                    </div>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        <?php 
                        $colores_establo = ["#6c584c", "#b87c4f", "#d4a373", "#8b5a2b", "#a3b18a"];
                        foreach ($establos as $index => $e): 
                            $porcentaje = $totalEstablos > 0 ? round(($e['total'] / $totalEstablos) * 100, 1) : 0;
                            $establo_value = htmlspecialchars($e['facility'] ?: 'Sin asignar');
                        ?>
                        <div class="clickable-row flex items-center justify-between p-2 rounded-lg"
                             onclick="showDetail('establo', '<?= $establo_value ?>')">
                            <div class="flex items-center">
                                <span class="legend-dot" style="background-color: <?= $colores_establo[$index % count($colores_establo)] ?>;"></span>
                                <span class="font-medium text-[#4b2e1a]"><?= $establo_value ?></span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-[#2d6a4f]"><?= $e['total'] ?></span>
                                <span class="text-xs text-[#8b6946] ml-1">(<?= $porcentaje ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="lg:w-2/3 relative">
                    <div id="chart-establo-container" class="chart-container" style="height: 280px;">
                        <canvas id="chartEstablo"></canvas>
                    </div>
                    <div id="detalle-establo" class="hidden p-4 bg-[#fffaf0] rounded-xl border border-[#ecdbaa] mt-4">
                        <button class="back-to-chart" onclick="hideDetail('establo')"><i class="fas fa-arrow-left"></i> Volver al gráfico</button>
                        <div id="detalle-establo-content" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CRECIMIENTO -->
    <div id="tab-crecimiento" class="tab-content hidden">
        <div class="card-ganadero p-5">
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="lg:w-1/3 bg-[#fef5e6] rounded-xl p-4 border border-[#e7d5b9]">
                    <h2 class="text-xl font-bold text-[#5a3e1b] mb-3"><i class="fas fa-chart-line mr-2 text-[#b87c4f]"></i> Crecimiento Individual</h2>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="stat-box">
                            <div class="stat-number"><?= number_format($estadisticas['media'], 1) ?></div>
                            <div class="stat-label">Peso promedio (kg)</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?= $estadisticas['total'] ?></div>
                            <div class="stat-label">Animales</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?= number_format($estadisticas['min'], 1) ?></div>
                            <div class="stat-label">Mínimo (kg)</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?= number_format($estadisticas['max'], 1) ?></div>
                            <div class="stat-label">Máximo (kg)</div>
                        </div>
                    </div>
                    <div class="scrollable-table">
                        <table class="detail-table w-full">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Raza</th>
                                    <th>Peso (kg)</th>
                                    <th>Dif. vs media</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($animalesPeso)): ?>
                                    <?php foreach ($animalesPeso as $a): 
                                        $diff = $a['weight'] - $estadisticas['media'];
                                        $diff_color = $diff >= 0 ? 'text-emerald-700' : 'text-rose-700';
                                    ?>
                                    <tr>
                                        <td class="font-mono"><?= htmlspecialchars($a['code']) ?></td>
                                        <td><?= htmlspecialchars($a['name'] ?: '—') ?></td>
                                        <td><?= htmlspecialchars($a['breed'] ?: '-') ?></td>
                                        <td class="font-medium"><?= number_format($a['weight'], 1) ?></td>
                                        <td class="<?= $diff_color ?>"><?= ($diff >= 0 ? '+' : '') . number_format($diff, 1) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="5" class="text-center py-4 text-[#8b6946]">No hay animales con peso registrado</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="lg:w-2/3">
                    <div id="chart-crecimiento-container" class="chart-container" style="height: <?= max(320, $estadisticas['total'] * 28) ?>px;">
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
                    borderColor: '#fff9ef',
                    hoverOffset: 8,
                    cutout: '65%',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, font: { size: 11, family: "'Inter', sans-serif" }, color: '#5a3e1b' } },
                    tooltip: { backgroundColor: '#2d2b1f', titleColor: '#f7efdf', bodyColor: '#e9dfc7' }
                }
            }
        });
    }

    function initRazaChart() {
        if (charts.razas) return;
        const colors = ["#2d6a4f", "#b87c4f", "#d4a373", "#8b5a2b", "#a3b18a", "#6c584c"];
        charts.razas = createDoughnutChart('chartRazas', razas.map(r => r.breed || 'Sin raza'), razas.map(r => parseInt(r.total)), razas.map((_, i) => colors[i % colors.length]));
    }
    function initSexoChart() {
        if (charts.sexos) return;
        const colors = sexos.map(s => s.gender === 'F' ? '#d97706' : '#2d6a4f');
        charts.sexos = createDoughnutChart('chartSexo', sexos.map(s => s.gender === 'F' ? 'Hembra' : 'Macho'), sexos.map(s => parseInt(s.total)), colors);
    }
    function initActividadChart() {
        if (charts.actividad) return;
        const colors = ["#8b5a2b", "#c08a5c", "#d4a373", "#6c584c", "#a3b18a", "#4f6d5e"];
        charts.actividad = createDoughnutChart('chartActividad', actividades.map(a => a.status || 'Sin clasificar'), actividades.map(a => parseInt(a.total)), actividades.map((_, i) => colors[i % colors.length]));
    }
    function initEspecieChart() {
        if (charts.especie) return;
        const colors = ["#2d6a4f", "#b87c4f", "#d4a373", "#8b5a2b"];
        charts.especie = createDoughnutChart('chartEspecie', especies.map(e => e.animal_type), especies.map(e => parseInt(e.total)), especies.map((_, i) => colors[i % colors.length]));
    }
    function initEstabloChart() {
        if (charts.establo) return;
        const colors = ["#6c584c", "#b87c4f", "#d4a373", "#8b5a2b", "#a3b18a"];
        charts.establo = createDoughnutChart('chartEstablo', establos.map(e => e.facility || 'Sin asignar'), establos.map(e => parseInt(e.total)), establos.map((_, i) => colors[i % colors.length]));
    }

    function initCrecimientoChart() {
        if (charts.crecimiento) return;
        if (animalesPeso.length > 0) {
            const labels = animalesPeso.map(a => a.code + (a.name ? ' - ' + a.name : ''));
            const data = animalesPeso.map(a => parseFloat(a.weight));
            const backgroundColors = data.map(w => w >= mediaPeso ? 'rgba(45, 106, 79, 0.7)' : 'rgba(200, 100, 50, 0.7)');
            const ctx = document.getElementById('chartIndividual').getContext('2d');
            charts.crecimiento = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Peso (kg)',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(c => c.replace('0.7', '1')),
                        borderWidth: 1,
                        borderRadius: 6
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
                                    borderColor: '#b87c4f',
                                    borderWidth: 2,
                                    borderDash: [6, 4],
                                    label: {
                                        display: true,
                                        content: 'Promedio ' + mediaPeso.toFixed(1) + ' kg',
                                        position: 'end',
                                        backgroundColor: '#b87c4f',
                                        color: 'white',
                                        font: { size: 10 }
                                    }
                                }
                            }
                        }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Peso (kg)', color: '#5a3e1b' }, grid: { color: '#e7d5b9' } },
                        y: { ticks: { autoSkip: false, font: { size: 9 } }, grid: { display: false } }
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
            else if (type === 'establo') return (c.facility || 'Sin asignar') === value;
            return false;
        });

        const colLabel = { raza: 'Raza', sexo: 'Sexo', actividad: 'Estado', especie: 'Especie', establo: 'Establo' }[type] || '';
        let html = '<table class="detail-table w-full"><thead><tr><th>ID</th><th>Código</th><th>Nombre</th><th>' + colLabel + '</th></tr></thead><tbody>';
        if (filtered.length > 0) {
            filtered.forEach(c => {
                let lastCol;
                if (type === 'raza') lastCol = c.breed || 'Sin raza';
                else if (type === 'sexo') lastCol = c.gender === 'F' ? 'Hembra' : 'Macho';
                else if (type === 'actividad') lastCol = c.status || 'Sin clasificar';
                else if (type === 'especie') lastCol = c.animal_type || '-';
                else if (type === 'establo') lastCol = c.facility || 'Sin asignar';
                html += `<tr>
                            <td class="text-center">${c.id}</td>
                            <td class="font-mono">${c.code}</td>
                            <td>${c.name || '—'}</td>
                            <td>${lastCol}</td>
                         </tr>`;
            });
        } else {
            html += '<tr><td colspan="4" class="text-center py-4 text-[#8b6946]">No hay animales en esta categoría.</td></tr>';
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