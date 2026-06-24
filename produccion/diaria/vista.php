<?php
/**
 * Vista del panel diario.
 * Recibe $datosVista con todas las variables necesarias.
 */

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
  <title>Dashboard Ganadero | Control de Producción</title>
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
    .chart-container canvas {
      max-height: 200px;
      width: 100%;
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
    /* Acentos con colores de la paleta */
    .bg-green-soft { background: #2d6a4f; }
    .text-green-soft { color: #2d6a4f; }
    .border-green-soft { border-color: #2d6a4f; }
    .bg-amber-soft { background: #d4a02b; }
    .text-amber-soft { color: #d4a02b; }
    .bg-brown-soft { background: #8b5a2b; }
    .text-brown-soft { color: #8b5a2b; }
    /* gradient headers */
    .header-gradient {
      background: linear-gradient(135deg, #2d6a4f 0%, #52b788 100%);
      color: white;
    }
    .header-amber {
      background: linear-gradient(135deg, #d4a02b 0%, #f9c74f 100%);
      color: #1a2e1a;
    }
    .header-brown {
      background: linear-gradient(135deg, #8b5a2b 0%, #c28b5e 100%);
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
  </style>
</head>
<body class="antialiased">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-md flex items-center gap-2">
          <span class="bg-white/20 p-2 rounded-full text-amber-300"></span>
          Control de Producción
        </h1>
        <p class="text-amber-100/80 mt-1">
          Semana del <strong><?= date('d/m/Y', strtotime($domingo)) ?></strong> al <strong><?= date('d/m/Y', strtotime($sabado)) ?></strong>
        </p>
      </div>
      <div class="flex items-center gap-3 text-white bg-white/20 backdrop-blur-sm px-5 py-2 rounded-full shadow-md border border-white/30">
        <i class="fas fa-calendar-alt text-amber-300"></i>
        <span class="font-medium"><?= date('d/m/Y', strtotime($hoy)) ?></span>
      </div>
    </div>

    <!-- FILA 1: Vacas + Leche -->
    <div class="row-pair mb-6">
      <div class="card info-card overflow-hidden">
        <div class="header-gradient px-5 py-3 border-b border-green-700/30 flex items-center gap-2">
          <i class="fas fa-cow text-amber-200 text-xl"></i>
          <h2 class="font-bold text-white">Vacas activas</h2>
        </div>
        <div class="p-4">
          <div class="flex justify-between items-end mb-3">
            <span class="text-3xl font-black text-green-800"><?= $totalVacas ?></span>
            <span class="text-sm text-gray-600">en producción</span>
          </div>
          <div class="bg-green-50 rounded-xl p-3 border border-green-200">
            <div class="flex justify-between text-sm">
              <span class="text-gray-700"><i class="fas fa-tint text-green-600 mr-1"></i> Leche semanal</span>
              <span class="font-bold text-green-800"><?= number_format($totalLecheSemana, 2) ?> L</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-700"><i class="fas fa-chart-line mr-1"></i> Promedio diario</span>
              <span class="font-medium text-green-700"><?= number_format($promedioLecheDiario, 2) ?> L/día</span>
            </div>
          </div>
          <?php if (!empty($vacas)): ?>
            <div class="mt-3 max-h-32 overflow-y-auto text-xs">
              <?php foreach (array_slice($vacas, 0, 5) as $v): ?>
                <div class="flex justify-between py-1 border-b border-gray-100">
                  <span class="font-medium text-gray-800"><?= htmlspecialchars($v['name']) ?></span>
                  <span class="text-gray-400"><?= htmlspecialchars($v['breed']) ?></span>
                </div>
              <?php endforeach; ?>
              <?php if (count($vacas) > 5): ?><div class="text-gray-400 text-center py-1">+<?= count($vacas)-5 ?> más</div><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card p-5">
        <div class="flex items-center gap-3 border-b border-green-200 pb-3 mb-4">
          <div class="bg-green-100 p-2 rounded-xl"><i class="fas fa-tint text-green-700 text-xl"></i></div>
          <h2 class="text-lg font-bold text-gray-800">Producción diaria de leche <span class="text-sm font-normal text-gray-500">(litros)</span></h2>
        </div>
        <div class="chart-container"><canvas id="graficaLeche"></canvas></div>
      </div>
    </div>

    <!-- FILA 2: Sacrificios + Gráfica -->
    <div class="row-pair mb-6">
      <div class="card info-card overflow-hidden">
        <div class="header-brown px-5 py-3 border-b border-brown-800/30 flex items-center gap-2">
          <i class="fas fa-khanda text-amber-200 text-xl"></i>
          <h2 class="font-bold text-white">Sacrificios</h2>
        </div>
        <div class="p-4">
          <div class="mb-3">
            <p class="text-sm text-gray-500 mb-1">Hoy (<?= date('d/m', strtotime($hoy)) ?>)</p>
            <?php if ($totalSacrificiosHoy > 0): ?>
              <span class="text-2xl font-bold text-brown-700"><?= $totalSacrificiosHoy ?></span> animales
              <div class="text-xs text-gray-500 mt-1">
                <?php foreach ($sacrificiosHoy as $s): ?><?= $s['quantity'].' '.$s['animal_type'] ?> <?php endforeach; ?>
              </div>
            <?php else: ?>
              <span class="text-gray-400">Sin sacrificios hoy</span>
            <?php endif; ?>
          </div>

          <div class="bg-amber-50 rounded-xl p-3 border border-amber-200">
            <div class="flex justify-between text-sm">
              <span class="text-gray-700"><i class="fas fa-calendar-week mr-1"></i> Total semana</span>
              <span class="font-bold text-brown-800"><?= $totalAnimalesSemana ?> animales</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-700"><i class="fas fa-weight-scale mr-1"></i> Carne estimada</span>
              <span class="font-bold text-brown-700"><?= number_format($totalKgCarneSemana) ?> kg</span>
            </div>
          </div>

          <?php if (!empty($vacasSacrificadas)): ?>
          <div class="mt-3 border-t border-brown-100 pt-3">
            <p class="text-sm font-medium text-gray-700 mb-2"><i class="fas fa-skull mr-1 text-brown-500"></i> Vacas sacrificadas recientemente</p>
            <ul class="space-y-1 text-xs">
              <?php foreach ($vacasSacrificadas as $vs): ?>
              <li class="flex justify-between">
                <span class="font-medium text-gray-800"><?= htmlspecialchars($vs['name'] ?: $vs['tag']) ?></span>
                <span class="text-gray-400"><?= htmlspecialchars($vs['breed']) ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php else: ?>
          <div class="mt-3 text-xs text-gray-400 text-center py-2">No hay vacas sacrificadas registradas</div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card p-5">
        <div class="flex items-center gap-3 border-b border-brown-200 pb-3 mb-4">
          <div class="bg-brown-100 p-2 rounded-xl"><i class="fas fa-chart-bar text-brown-700 text-xl"></i></div>
          <h2 class="text-lg font-bold text-gray-800">Sacrificios diarios <span class="text-sm font-normal text-gray-500">(cantidad de animales)</span></h2>
        </div>
        <div class="chart-container"><canvas id="graficaCarne"></canvas></div>
      </div>
    </div>

    <!-- FILA 3: Gallinero + Huevos -->
    <div class="row-pair mb-6">
      <div class="card info-card overflow-hidden">
        <div class="header-amber px-5 py-3 border-b border-amber-300/30 flex items-center gap-2">
          <i class="fas fa-egg text-brown-700 text-xl"></i>
          <h2 class="font-bold text-brown-800">Gallinero</h2>
        </div>
        <div class="p-4">
          <div class="flex justify-between items-end mb-3">
            <span class="text-3xl font-black text-amber-700"><?= number_format($totalGallinas) ?></span>
            <span class="text-sm text-gray-600">gallinas activas</span>
          </div>
          <div class="bg-amber-50 rounded-xl p-3 border border-amber-200">
            <div class="flex justify-between text-sm">
              <span class="text-gray-700"><i class="fas fa-egg mr-1"></i> Huevos/semana</span>
              <span class="font-bold text-amber-800"><?= number_format($totalHuevosSemana) ?> uds</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-700"><i class="fas fa-calculator mr-1"></i> Promedio diario</span>
              <span class="font-medium text-amber-700"><?= round($promedioHuevosDiario) ?> huevos/día</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-700"><i class="fas fa-percent mr-1"></i> Eficiencia</span>
              <span class="font-medium text-amber-700"><?= $eficienciaGallinas ?>%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card p-5">
        <div class="flex items-center gap-3 border-b border-amber-200 pb-3 mb-4">
          <div class="bg-amber-100 p-2 rounded-xl"><i class="fas fa-egg text-amber-600 text-xl"></i></div>
          <h2 class="text-lg font-bold text-gray-800">Producción diaria de huevos <span class="text-sm font-normal text-gray-500">(unidades)</span></h2>
        </div>
        <div class="chart-container"><canvas id="graficaHuevos"></canvas></div>
      </div>
    </div>

    <div class="mt-8 text-center text-white/60 text-sm border-t border-white/20 pt-6">
      <i class="fas fa-chart-line mr-1"></i> Datos actualizados · Semana del <?= date('d/m/Y', strtotime($domingo)) ?> al <?= date('d/m/Y', strtotime($sabado)) ?>
    </div>
  </div>

  <script>
    const dias = <?= json_encode($diasEtiquetas) ?>;
    const lecheData = <?= json_encode($lecheData) ?>;
    const carneData = <?= json_encode($carneData) ?>;
    const huevosData = <?= json_encode($huevosData) ?>;

    // Paleta de colores: verde, café, amarillo
    const colorLeche = '#2d6a4f';
    const colorLecheBg = 'rgba(45,106,79,0.7)';
    const colorCarne = '#8b5a2b';
    const colorCarneBg = 'rgba(139,90,43,0.7)';
    const colorHuevo = '#d4a02b';
    const colorHuevoBg = 'rgba(212,160,43,0.7)';

    const baseOptions = {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { 
          position: 'top', 
          labels: { font: { size: 11, family: "'Inter', sans-serif" }, usePointStyle: true, boxWidth: 6, color: '#1e293b' } 
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
      }
    };

    new Chart(document.getElementById('graficaLeche'), {
      type: 'bar',
      data: { 
        labels: dias, 
        datasets: [{ 
          label: 'Litros', 
          data: lecheData, 
          backgroundColor: colorLecheBg, 
          borderColor: colorLeche, 
          borderWidth: 1, 
          borderRadius: 6 
        }] 
      },
      options: baseOptions
    });

    new Chart(document.getElementById('graficaCarne'), {
      type: 'bar',
      data: { 
        labels: dias, 
        datasets: [{ 
          label: 'Animales sacrificados', 
          data: carneData, 
          backgroundColor: colorCarneBg, 
          borderColor: colorCarne, 
          borderWidth: 1, 
          borderRadius: 6 
        }] 
      },
      options: baseOptions
    });

    new Chart(document.getElementById('graficaHuevos'), {
      type: 'bar',
      data: { 
        labels: dias, 
        datasets: [{ 
          label: 'Huevos', 
          data: huevosData, 
          backgroundColor: colorHuevoBg, 
          borderColor: colorHuevo, 
          borderWidth: 1, 
          borderRadius: 6 
        }] 
      },
      options: baseOptions
    });
  </script>
</body>
</html>