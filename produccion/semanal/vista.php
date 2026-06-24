<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
extract($datosVista);

$diasJSON    = json_encode($diasEtiquetas);
$lecheJSON   = json_encode(['fija' => $lecheFija['diario'], 'comparar' => $lecheComparar['diario']]);
$carneJSON   = json_encode(['fija' => $carneFija['diario'], 'comparar' => $carneComparar['diario']]);
$huevosJSON  = json_encode(['fija' => $huevosFija['diario'], 'comparar' => $huevosComparar['diario']]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparativa Semanal | Ganadería</title>
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
            .row-pair {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            body {
                padding: 1rem;
            }
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
    </style>
</head>
<body class="antialiased">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-2">
                    <span class="bg-white/20 p-2 rounded-full text-amber-300"></span>
                    Comparativa Semanal
                </h1>
                <p class="text-amber-100/80 mt-1">
                    Semana actual (fija): <strong><?= date('d/m/Y', strtotime($semanaFijaStart)) ?> - <?= date('d/m/Y', strtotime($semanaFijaEnd)) ?></strong>
                </p>
            </div>
            <div class="flex items-center gap-3 bg-white/20 backdrop-blur-sm px-5 py-2 rounded-full shadow-md border border-white/30">
                <span class="text-white/80 text-sm">Comparar con:</span>
                <form method="GET" id="weekForm" class="flex items-center gap-2">
                    <input type="date" name="week_start" value="<?= $semanaCompararStart ?>"
                           max="<?= date('Y-m-d', strtotime($semanaFijaStart . ' -1 day')) ?>"
                           class="border border-white/30 bg-white/10 text-white placeholder-white/50 rounded-lg px-3 py-1 text-sm focus:ring-amber-400 focus:border-amber-400">
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
                    <span class="badge-green text-xs px-3 py-1 rounded-full font-semibold">semana fija</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm text-gray-600">Total semana fija</p>
                            <p class="text-3xl font-bold text-green-dark"><?= number_format($lecheFija['total'], 1) ?> <span class="text-base font-normal text-gray-500">L</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Semana a comparar</p>
                            <p class="text-xl font-semibold text-gray-700"><?= number_format($lecheComparar['total'], 1) ?> L</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-green-light rounded-lg p-2">
                            <span class="text-xs text-gray-600">Promedio diario</span>
                            <span class="block font-bold text-green-dark"><?= number_format($promedioLecheFija, 1) ?> L</span>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-2">
                            <span class="text-xs text-gray-600">Variación</span>
                            <span class="block font-bold <?= $cambioLeche >= 0 ? 'text-green-700' : 'text-red-600' ?>">
                                <?= $cambioLeche >= 0 ? '▲' : '▼' ?> <?= abs($cambioLeche) ?>%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3 mb-4">
                    <div class="bg-green-light p-2 rounded-xl"><i class="fas fa-chart-bar text-green-dark text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Comparación diaria <span class="text-sm font-normal text-gray-500">(litros)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaLeche"></canvas></div>
            </div>
        </div>

        <!-- FILA 2: Sacrificios -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="header-brown px-5 py-3 border-b border-brown-800/30 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2">🪓 Sacrificios</h2>
                    <span class="badge-brown text-xs px-3 py-1 rounded-full font-semibold">semana fija</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm text-gray-600">Total semana fija</p>
                            <p class="text-3xl font-bold text-brown-dark"><?= $carneFija['total'] ?> <span class="text-base font-normal text-gray-500">cab.</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Semana a comparar</p>
                            <p class="text-xl font-semibold text-gray-700"><?= $carneComparar['total'] ?> cab.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-brown-light rounded-lg p-2">
                            <span class="text-xs text-gray-600">Carne estimada</span>
                            <span class="block font-bold text-brown-dark"><?= number_format($kgCarneFija) ?> kg</span>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-2">
                            <span class="text-xs text-gray-600">Variación</span>
                            <span class="block font-bold <?= $cambioCarne >= 0 ? 'text-green-700' : 'text-red-600' ?>">
                                <?= $cambioCarne >= 0 ? '▲' : '▼' ?> <?= abs($cambioCarne) ?>%
                            </span>
                        </div>
                    </div>
                    <?php if (!empty($detalleSacrificiosFija)): ?>
                    <div class="mt-2 text-xs">
                        <p class="text-gray-500 mb-1">Desglose:</p>
                        <?php foreach ($detalleSacrificiosFija as $t): ?>
                        <div class="flex justify-between">
                            <span><?= htmlspecialchars($t['animal_type']) ?></span>
                            <span class="font-medium"><?= $t['total'] ?> (<?= number_format($t['total'] * ($pesosPromedio[$t['animal_type']]??0)) ?> kg)</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3 mb-4">
                    <div class="bg-brown-light p-2 rounded-xl"><i class="fas fa-weight-hanging text-brown-dark text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Comparación diaria <span class="text-sm font-normal text-gray-500">(cabezas)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaCarne"></canvas></div>
            </div>
        </div>

        <!-- FILA 3: Huevos -->
        <div class="row-pair mb-6">
            <div class="card info-card overflow-hidden">
                <div class="header-amber px-5 py-3 border-b border-amber-300/30 flex items-center justify-between">
                    <h2 class="font-bold flex items-center gap-2">🥚 Huevos</h2>
                    <span class="badge-amber text-xs px-3 py-1 rounded-full font-semibold">semana fija</span>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-sm text-gray-600">Total semana fija</p>
                            <p class="text-3xl font-bold text-amber-dark"><?= $huevosFija['total'] ?> <span class="text-base font-normal text-gray-500">uds</span></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Semana a comparar</p>
                            <p class="text-xl font-semibold text-gray-700"><?= $huevosComparar['total'] ?> uds</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <div class="bg-amber-light rounded-lg p-2">
                            <span class="text-xs text-gray-600">Promedio diario</span>
                            <span class="block font-bold text-amber-dark"><?= round($promedioHuevosFija) ?> uds</span>
                        </div>
                        <div class="bg-gray-100 rounded-lg p-2">
                            <span class="text-xs text-gray-600">Variación</span>
                            <span class="block font-bold <?= $cambioHuevos >= 0 ? 'text-green-700' : 'text-red-600' ?>">
                                <?= $cambioHuevos >= 0 ? '▲' : '▼' ?> <?= abs($cambioHuevos) ?>%
                            </span>
                        </div>
                    </div>
                    <div class="bg-amber-light rounded-lg p-3 mt-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600"><i class="fas fa-feather-alt mr-1"></i> Gallinas</span>
                            <span class="font-bold"><?= number_format($totalGallinas) ?></span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600"><i class="fas fa-percent mr-1"></i> Eficiencia</span>
                            <span class="font-bold"><?= $eficienciaHuevos ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card p-5">
                <div class="flex items-center gap-3 border-b border-gray-200 pb-3 mb-4">
                    <div class="bg-amber-light p-2 rounded-xl"><i class="fas fa-chart-simple text-amber-dark text-xl"></i></div>
                    <h2 class="text-lg font-bold text-gray-800">Comparación diaria <span class="text-sm font-normal text-gray-500">(huevos)</span></h2>
                </div>
                <div class="chart-container"><canvas id="graficaHuevos"></canvas></div>
            </div>
        </div>

        <div class="mt-8 text-center text-white/60 text-sm border-t border-white/20 pt-6">
            Comparando con semana del <strong><?= date('d/m/Y', strtotime($semanaCompararStart)) ?></strong> al <strong><?= date('d/m/Y', strtotime($semanaCompararEnd)) ?></strong>
        </div>
    </div>

    <script>
        const dias = <?= $diasJSON ?>;
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
                labels: dias,
                datasets: [
                    {
                        label: 'Semana a comparar',
                        data: lecheData.comparar,
                        backgroundColor: colorVerdeClaro,
                        borderColor: colorVerdeOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Semana actual',
                        data: lecheData.fija,
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
                labels: dias,
                datasets: [
                    {
                        label: 'Semana a comparar',
                        data: carneData.comparar,
                        backgroundColor: colorCafeClaro,
                        borderColor: colorCafeOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Semana actual',
                        data: carneData.fija,
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
                labels: dias,
                datasets: [
                    {
                        label: 'Semana a comparar',
                        data: huevosData.comparar,
                        backgroundColor: colorAmarilloClaro,
                        borderColor: colorAmarilloOscuro,
                        borderWidth: 1,
                        borderRadius: 6,
                        barPercentage: 0.4,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Semana actual',
                        data: huevosData.fija,
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

        // Control del botón de envío del formulario (sin modo oscuro)
        document.getElementById('weekForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '⏳ Cargando...';
            btn.disabled = true;
        });
    </script>
</body>
</html>