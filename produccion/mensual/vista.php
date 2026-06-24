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
        * {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            background: #1a4d2a; /* verde oscuro base */
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                              repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-size: 30px 30px, 12px 12px;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(2px);
            border-radius: 1.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.4);
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
        .info-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .chart-container canvas {
            max-height: 220px;
            width: 100%;
        }
        /* Header gradients */
        .header-green {
            background: linear-gradient(135deg, #2d6a4f 0%, #52b788 100%);
            color: white;
        }
        .header-brown {
            background: linear-gradient(135deg, #8b5a2b 0%, #c28b5e 100%);
            color: white;
        }
        .header-amber {
            background: linear-gradient(135deg, #d4a02b 0%, #f9c74f 100%);
            color: #1a2e1a;
        }
        .badge-green {
            background: #2d6a4f;
            color: white;
        }
        .badge-brown {
            background: #8b5a2b;
            color: white;
        }
        .badge-amber {
            background: #d4a02b;
            color: #1a2e1a;
        }
        .text-green-dark { color: #2d6a4f; }
        .text-brown-dark { color: #8b5a2b; }
        .text-amber-dark { color: #d4a02b; }
        .bg-green-light { background: #e6f4ea; }
        .bg-brown-light { background: #f5ede4; }
        .bg-amber-light { background: #fef7e0; }
        .bg-gray-soft { background: #f3f4f6; }
    </style>
</head>
<body class="antialiased">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-2">
                    <span class="bg-white/20 p-2 rounded-full text-amber-300"></span>
                    Comparativa Mensual
                </h1>
                <p class="text-amber-100/80 mt-1">
                    Año actual (fijo): <strong><?= $anioFijo ?></strong>
                </p>
            </div>
            <div class="flex items-center gap-3 bg-white/20 backdrop-blur-sm px-5 py-2 rounded-full shadow-md border border-white/30">
                <span class="text-white/80 text-sm">Comparar con:</span>
                <form method="GET" id="yearForm" class="flex items-center gap-2">
                    <select name="year" class="border border-white/30 bg-white/10 text-white rounded-lg px-3 py-1 text-sm focus:ring-amber-400 focus:border-amber-400">
                        <?php foreach ($aniosDisponibles as $anio): ?>
                            <option value="<?= $anio ?>" <?= $anio == $anioComparar ? 'selected' : '' ?>><?= $anio ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1 rounded-lg text-sm transition shadow-md" id="submitBtn">
                        🔍 Ver
                    </button>
                </form>
            </div>
        </div>

        <!-- FILA 1: Leche -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="header-green px-5 py-3 border-b border-green-700/30 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2">🥛 Leche</h2>
                    <span class="badge-green text-xs px-3 py-1 rounded-full font-semibold">total anual</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-gray-600">Año <?= $anioFijo ?> (fijo)</p>
                            <p class="text-2xl font-bold text-green-dark"><?= number_format($lecheFijo['total'], 2) ?> <span class="text-sm font-normal text-gray-500">litros</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Año <?= $anioComparar ?></p>
                            <p class="text-lg font-semibold text-gray-700"><?= number_format($lecheComparar['total'], 2) ?> l</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2 p-2 bg-green-light rounded-lg">
                        <?php if ($cambioLeche >= 0): ?>
                            <span class="text-green-700 text-sm font-semibold">▲ <?= abs($cambioLeche) ?>%</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">▼ <?= abs($cambioLeche) ?>%</span>
                        <?php endif; ?>
                        <span class="text-gray-500 text-xs">vs año seleccionado</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-green-light p-2 rounded-lg">
                            <span class="text-gray-600">Promedio mensual</span>
                            <p class="font-bold text-green-dark"><?= number_format($lechePromedio) ?> L</p>
                        </div>
                        <div class="bg-green-light p-2 rounded-lg">
                            <span class="text-gray-600">Mes pico</span>
                            <p class="font-bold text-green-dark"><?= number_format($lecheMesPico) ?> L</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-4 gap-1 text-xs">
                        <?php foreach ($mesesNombres as $idx => $mes): ?>
                            <div class="flex justify-between">
                                <span class="text-gray-500"><?= $mes ?></span>
                                <span class="font-mono text-green-dark"><?= round($lecheFijo['mensual'][$idx]) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3 mb-4">
                    <div class="bg-green-light p-2 rounded-xl"><i class="fas fa-chart-bar text-green-dark text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Producción mensual <span class="text-sm font-normal text-gray-500">(litros)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaLeche"></canvas></div>
            </div>
        </div>

        <!-- FILA 2: Sacrificios -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="header-brown px-5 py-3 border-b border-brown-800/30 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2">🪓 Sacrificios</h2>
                    <span class="badge-brown text-xs px-3 py-1 rounded-full font-semibold">total anual</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-gray-600">Año <?= $anioFijo ?> (fijo)</p>
                            <p class="text-2xl font-bold text-brown-dark"><?= $carneFijo['total'] ?> <span class="text-sm font-normal text-gray-500">cabezas</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Año <?= $anioComparar ?></p>
                            <p class="text-lg font-semibold text-gray-700"><?= $carneComparar['total'] ?> cab.</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2 p-2 bg-brown-light rounded-lg">
                        <?php if ($cambioCarne >= 0): ?>
                            <span class="text-green-700 text-sm font-semibold">▲ <?= abs($cambioCarne) ?>%</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">▼ <?= abs($cambioCarne) ?>%</span>
                        <?php endif; ?>
                        <span class="text-gray-500 text-xs">vs año seleccionado</span>
                    </div>
                    <div class="mt-3 bg-brown-light p-2 rounded-lg">
                        <span class="text-gray-600 text-xs">🐄 Kg carne estimados (año fijo)</span>
                        <p class="font-bold text-brown-dark"><?= number_format($desgloseCarneFijo['total_kg']) ?> kg</p>
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
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3 mb-4">
                    <div class="bg-brown-light p-2 rounded-xl"><i class="fas fa-weight-hanging text-brown-dark text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Sacrificios mensuales <span class="text-sm font-normal text-gray-500">(cabezas)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaCarne"></canvas></div>
            </div>
        </div>

        <!-- FILA 3: Huevos -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="header-amber px-5 py-3 border-b border-amber-300/30 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2">🥚 Huevos</h2>
                    <span class="badge-amber text-xs px-3 py-1 rounded-full font-semibold">total anual</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <p class="text-sm text-gray-600">Año <?= $anioFijo ?> (fijo)</p>
                            <p class="text-2xl font-bold text-amber-dark"><?= number_format($huevosFijo['total']) ?> <span class="text-sm font-normal text-gray-500">unidades</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Año <?= $anioComparar ?></p>
                            <p class="text-lg font-semibold text-gray-700"><?= number_format($huevosComparar['total']) ?> uds</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2 p-2 bg-amber-light rounded-lg">
                        <?php if ($cambioHuevos >= 0): ?>
                            <span class="text-green-700 text-sm font-semibold">▲ <?= abs($cambioHuevos) ?>%</span>
                        <?php else: ?>
                            <span class="text-red-600 text-sm font-semibold">▼ <?= abs($cambioHuevos) ?>%</span>
                        <?php endif; ?>
                        <span class="text-gray-500 text-xs">vs año seleccionado</span>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                        <div class="bg-amber-light p-2 rounded-lg">
                            <span class="text-gray-600">Promedio mensual</span>
                            <p class="font-bold text-amber-dark"><?= number_format($huevosPromedio) ?> uds</p>
                        </div>
                        <div class="bg-amber-light p-2 rounded-lg">
                            <span class="text-gray-600">Mes pico</span>
                            <p class="font-bold text-amber-dark"><?= number_format($huevosMesPico) ?> uds</p>
                        </div>
                    </div>
                    <div class="mt-3 bg-amber-light p-2 rounded-lg">
                        <span class="text-gray-600 text-xs">🐔 Gallinas (promedio anual)</span>
                        <p class="font-bold text-amber-dark"><?= number_format($promedioGallinasFijo) ?></p>
                        <span class="text-gray-500 text-xs">Eficiencia: <?= $eficiencia ?>%</span>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3 mb-4">
                    <div class="bg-amber-light p-2 rounded-xl"><i class="fas fa-chart-simple text-amber-dark text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Producción mensual <span class="text-sm font-normal text-gray-500">(huevos)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaHuevos"></canvas></div>
            </div>
        </div>

        <div class="mt-8 text-center text-white/60 text-sm border-t border-white/20 pt-6">
            Comparando año fijo <strong><?= $anioFijo ?></strong> con año <strong><?= $anioComparar ?></strong>
        </div>
    </div>

    <script>
        const meses = <?= $mesesJSON ?>;
        const lecheData = <?= $lecheJSON ?>;
        const carneData = <?= $carneJSON ?>;
        const huevosData = <?= $huevosJSON ?>;

        // Paleta campestre
        const colorVerdeOscuro = '#2d6a4f';
        const colorVerdeClaro  = '#52b788';
        const colorCafeOscuro  = '#8b5a2b';
        const colorCafeClaro   = '#c28b5e';
        const colorAmarilloOscuro = '#d4a02b';
        const colorAmarilloClaro  = '#f9c74f';

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: { size: 11, family: "'Inter', sans-serif" },
                        usePointStyle: true,
                        boxWidth: 6,
                        color: '#1e293b'
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 12 },
                    bodyFont: { size: 11 },
                    padding: 6,
                    cornerRadius: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    title: { display: true, text: 'Cantidad', font: { size: 10, color: '#475569' } },
                    ticks: { color: '#475569' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, color: '#475569' } }
                }
            },
            layout: { padding: { top: 4, bottom: 4, left: 4, right: 4 } }
        };

        // Gráfica Leche
        new Chart(document.getElementById('graficaLeche'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Año <?= $anioComparar ?>',
                        data: lecheData.comparar,
                        backgroundColor: colorVerdeClaro,
                        borderColor: colorVerdeOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Año <?= $anioFijo ?> (fijo)',
                        data: lecheData.fijo,
                        backgroundColor: colorVerdeOscuro,
                        borderColor: colorVerdeOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: baseOptions
        });

        // Gráfica Carne
        new Chart(document.getElementById('graficaCarne'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Año <?= $anioComparar ?>',
                        data: carneData.comparar,
                        backgroundColor: colorCafeClaro,
                        borderColor: colorCafeOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Año <?= $anioFijo ?> (fijo)',
                        data: carneData.fijo,
                        backgroundColor: colorCafeOscuro,
                        borderColor: colorCafeOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: baseOptions
        });

        // Gráfica Huevos
        new Chart(document.getElementById('graficaHuevos'), {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [
                    {
                        label: 'Año <?= $anioComparar ?>',
                        data: huevosData.comparar,
                        backgroundColor: colorAmarilloClaro,
                        borderColor: colorAmarilloOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Año <?= $anioFijo ?> (fijo)',
                        data: huevosData.fijo,
                        backgroundColor: colorAmarilloOscuro,
                        borderColor: colorAmarilloOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: baseOptions
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