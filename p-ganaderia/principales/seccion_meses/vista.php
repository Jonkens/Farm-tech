<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
extract($datosVista);

$lecheJSON  = json_encode(['fijo' => $lecheFijo['mensual'], 'comparar' => $lecheComparar['mensual']]);
$carneJSON  = json_encode(['fijo' => $carneFijo['mensual'], 'comparar' => $carneComparar['mensual']]);
$huevosJSON = json_encode(['fijo' => $huevosFijo['mensual'], 'comparar' => $huevosComparar['mensual']]);
$mesesJSON  = json_encode($mesesNombres);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparativa Mensual | Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            background: linear-gradient(145deg, #f4f7fb 0%, #e9eef4 100%);
            min-height: 100vh;
            padding: 1.5rem;
        }
        .card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.1);
        }
        .row-pair {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1.5rem;
            align-items: stretch;
        }
        @media (max-width: 768px) {
            .row-pair { grid-template-columns: 1fr; gap: 1rem; }
            body { padding: 1rem; }
        }
        .info-card { display: flex; flex-direction: column; justify-content: space-between; }
        .chart-container canvas { max-height: 220px; width: 100%; }

        /* Dark mode */
        .dark { background: linear-gradient(145deg, #1a202c 0%, #0f172a 100%); color: #e2e8f0; }
        .dark .card { background-color: #2d3748; }
        .dark .bg-gray-50 { background-color: #1a202c; }
        .dark .text-gray-800, .dark .text-gray-700 { color: #f7fafc; }
        .dark .text-gray-600, .dark .text-gray-500 { color: #cbd5e0; }
        .dark .border-gray-100, .dark .border-gray-200 { border-color: #4a5568; }
        .dark .bg-gradient-to-r.from-blue-50 { background-image: linear-gradient(to right, #1e3a8a, #1e40af); }
        .dark .bg-gradient-to-r.from-rose-50 { background-image: linear-gradient(to right, #7f1d1d, #991b1b); }
        .dark .bg-gradient-to-r.from-yellow-50 { background-image: linear-gradient(to right, #713f12, #854d0e); }
        .dark .bg-blue-100 { background-color: #1e3a8a; }
        .dark .bg-rose-100 { background-color: #7f1d1d; }
        .dark .bg-yellow-100 { background-color: #713f12; }
    </style>
</head>
<body class="antialiased">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-emerald-700 to-teal-600 bg-clip-text text-transparent">
                    📅 Comparativa Mensual
                </h1>
                <p class="text-gray-500 mt-1">
                    Año actual (fijo): <strong><?= $anioFijo ?></strong>
                </p>
            </div>
            <div class="flex items-center gap-3 bg-white/60 backdrop-blur-sm px-5 py-2 rounded-full shadow-sm">
                <form method="GET" id="yearForm" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Comparar con:</label>
                    <select name="year" class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                        <?php foreach ($aniosDisponibles as $anio): ?>
                            <option value="<?= $anio ?>" <?= $anio == $anioComparar ? 'selected' : '' ?>><?= $anio ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1 rounded-lg text-sm transition" id="submitBtn">
                        🔍 Ver
                    </button>
                </form>
                <button id="darkModeToggle" class="text-xl ml-2" title="Modo oscuro">🌙</button>
            </div>
        </div>

        <!-- FILA 1: Leche -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-5 py-3 border-b border-blue-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">🥛 Leche</h2>
                    <span class="bg-blue-200 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">total anual</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-gray-500">Año <?= $anioFijo ?> (fijo)</p>
                            <p class="text-2xl font-bold text-gray-800"><?= number_format($lecheFijo['total'], 2) ?> <span class="text-sm font-normal">litros</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Año <?= $anioComparar ?></p>
                            <p class="text-lg font-semibold text-gray-700"><?= number_format($lecheComparar['total'], 2) ?> l</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                        <?php if ($cambioLeche >= 0): ?>
                            <span class="text-green-600 text-sm font-semibold">▲ <?= abs($cambioLeche) ?>%</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">▼ <?= abs($cambioLeche) ?>%</span>
                        <?php endif; ?>
                        <span class="text-gray-500 text-xs">vs año seleccionado</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-blue-50 p-2 rounded-lg">
                            <span class="text-gray-500">Promedio mensual</span>
                            <p class="font-bold text-gray-700"><?= number_format($lechePromedio) ?> L</p>
                        </div>
                        <div class="bg-blue-50 p-2 rounded-lg">
                            <span class="text-gray-500">Mes pico</span>
                            <p class="font-bold text-gray-700"><?= number_format($lecheMesPico) ?> L</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-4 gap-1 text-xs">
                        <?php foreach ($mesesNombres as $idx => $mes): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-500"><?= $mes ?></span>
                                <span class="font-mono"><?= round($lecheFijo['mensual'][$idx]) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-4">
                    <div class="bg-blue-100 p-2 rounded-xl"><i class="fas fa-chart-bar text-blue-500 text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Producción mensual <span class="text-sm font-normal text-gray-500">(litros)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaLeche"></canvas></div>
            </div>
        </div>

        <!-- FILA 2: Sacrificios -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="bg-gradient-to-r from-rose-50 to-orange-50 px-5 py-3 border-b border-rose-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">🪓 Sacrificios</h2>
                    <span class="bg-rose-200 text-rose-800 text-xs px-3 py-1 rounded-full font-semibold">total anual</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-gray-500">Año <?= $anioFijo ?> (fijo)</p>
                            <p class="text-2xl font-bold text-gray-800"><?= $carneFijo['total'] ?> <span class="text-sm font-normal">cabezas</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Año <?= $anioComparar ?></p>
                            <p class="text-lg font-semibold text-gray-700"><?= $carneComparar['total'] ?> cab.</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                        <?php if ($cambioCarne >= 0): ?>
                            <span class="text-green-600 text-sm font-semibold">▲ <?= abs($cambioCarne) ?>%</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">▼ <?= abs($cambioCarne) ?>%</span>
                        <?php endif; ?>
                        <span class="text-gray-500 text-xs">vs año seleccionado</span>
                    </div>
                    <div class="mt-3 bg-rose-50 p-2 rounded-lg">
                        <span class="text-gray-600 text-xs">🐄 Kg carne estimados (año fijo)</span>
                        <p class="font-bold text-rose-700"><?= number_format($desgloseCarneFijo['total_kg']) ?> kg</p>
                    </div>
                    <?php if (!empty($desgloseCarneFijo['desglose'])): ?>
                    <div class="mt-3 text-xs space-y-1">
                        <?php foreach ($desgloseCarneFijo['desglose'] as $d): ?>
                        <div class="flex justify-between">
                            <span><?= htmlspecialchars($d['tipo']) ?></span>
                            <span><?= $d['cabezas'] ?> cab. (<?= number_format($d['kg']) ?> kg)</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-4">
                    <div class="bg-rose-100 p-2 rounded-xl"><i class="fas fa-weight-hanging text-rose-500 text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Sacrificios mensuales <span class="text-sm font-normal text-gray-500">(cabezas)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaCarne"></canvas></div>
            </div>
        </div>

        <!-- FILA 3: Huevos -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 px-5 py-3 border-b border-yellow-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">🥚 Huevos</h2>
                    <span class="bg-yellow-200 text-yellow-800 text-xs px-3 py-1 rounded-full font-semibold">total anual</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-gray-500">Año <?= $anioFijo ?> (fijo)</p>
                            <p class="text-2xl font-bold text-gray-800"><?= number_format($huevosFijo['total']) ?> <span class="text-sm font-normal">unidades</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Año <?= $anioComparar ?></p>
                            <p class="text-lg font-semibold text-gray-700"><?= number_format($huevosComparar['total']) ?> uds</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                        <?php if ($cambioHuevos >= 0): ?>
                            <span class="text-green-600 text-sm font-semibold">▲ <?= abs($cambioHuevos) ?>%</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">▼ <?= abs($cambioHuevos) ?>%</span>
                        <?php endif; ?>
                        <span class="text-gray-500 text-xs">vs año seleccionado</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-amber-50 p-2 rounded-lg">
                            <span class="text-gray-500">Promedio mensual</span>
                            <p class="font-bold text-gray-700"><?= number_format($huevosPromedio) ?> uds</p>
                        </div>
                        <div class="bg-amber-50 p-2 rounded-lg">
                            <span class="text-gray-500">Mes pico</span>
                            <p class="font-bold text-gray-700"><?= number_format($huevosMesPico) ?> uds</p>
                        </div>
                    </div>
                    <div class="mt-3 bg-yellow-50 p-2 rounded-lg">
                        <span class="text-gray-600 text-xs">🐔 Gallinas (promedio anual)</span>
                        <p class="font-bold text-gray-800"><?= number_format($promedioGallinasFijo) ?></p>
                        <span class="text-gray-500 text-xs">Eficiencia: <?= $eficiencia ?>%</span>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-4">
                    <div class="bg-yellow-100 p-2 rounded-xl"><i class="fas fa-chart-simple text-yellow-600 text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Producción mensual <span class="text-sm font-normal text-gray-500">(huevos)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaHuevos"></canvas></div>
            </div>
        </div>

        <div class="mt-8 text-center text-gray-400 text-sm border-t border-gray-200 pt-6">
            Comparando año fijo <strong><?= $anioFijo ?></strong> con año <strong><?= $anioComparar ?></strong>
        </div>
    </div>

    <script>
        const meses = <?= $mesesJSON ?>;
        const lecheData = <?= $lecheJSON ?>;
        const carneData = <?= $carneJSON ?>;
        const huevosData = <?= $huevosJSON ?>;

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { font: { size: 11, family: "'Inter', sans-serif" }, usePointStyle: true, boxWidth: 6 } },
                tooltip: { backgroundColor: '#1e293b', titleFont: { size: 12 }, bodyFont: { size: 11 }, padding: 6, cornerRadius: 6 }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e2e8f0' }, title: { display: true, text: 'Cantidad', font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { size: 10 } } }
            },
            layout: { padding: { top: 4, bottom: 4, left: 4, right: 4 } }
        };

        new Chart(document.getElementById('graficaLeche'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    { label: 'Año <?= $anioComparar ?>', data: lecheData.comparar, backgroundColor: '#93c5fd', borderColor: '#3b82f6', borderWidth: 1, borderRadius: 6, barPercentage: 0.4, categoryPercentage: 0.8 },
                    { label: 'Año <?= $anioFijo ?> (fijo)', data: lecheData.fijo, backgroundColor: '#10b981', borderColor: '#059669', borderWidth: 1, borderRadius: 6, barPercentage: 0.4, categoryPercentage: 0.8 }
                ]
            },
            options: baseOptions
        });

        new Chart(document.getElementById('graficaCarne'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    { label: 'Año <?= $anioComparar ?>', data: carneData.comparar, backgroundColor: '#fca5a5', borderColor: '#ef4444', borderWidth: 1, borderRadius: 6, barPercentage: 0.4, categoryPercentage: 0.8 },
                    { label: 'Año <?= $anioFijo ?> (fijo)', data: carneData.fijo, backgroundColor: '#f59e0b', borderColor: '#d97706', borderWidth: 1, borderRadius: 6, barPercentage: 0.4, categoryPercentage: 0.8 }
                ]
            },
            options: baseOptions
        });

        new Chart(document.getElementById('graficaHuevos'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    { label: 'Año <?= $anioComparar ?>', data: huevosData.comparar, backgroundColor: '#c4b5fd', borderColor: '#8b5cf6', borderWidth: 1, borderRadius: 6, barPercentage: 0.4, categoryPercentage: 0.8 },
                    { label: 'Año <?= $anioFijo ?> (fijo)', data: huevosData.fijo, backgroundColor: '#a78bfa', borderColor: '#7c3aed', borderWidth: 1, borderRadius: 6, barPercentage: 0.4, categoryPercentage: 0.8 }
                ]
            },
            options: baseOptions
        });

        // Dark mode toggle
        const darkToggle = document.getElementById('darkModeToggle');
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark');
            darkToggle.textContent = '☀️';
        }
        darkToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            const isDark = document.body.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            darkToggle.textContent = isDark ? '☀️' : '🌙';
        });

        // Spinner en formulario
        document.getElementById('yearForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '⏳ Cargando...';
            btn.disabled = true;
        });
    </script>
</body>
</html>