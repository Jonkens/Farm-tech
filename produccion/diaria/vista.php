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
    * { font-family: 'Inter', sans-serif; }
    body { background: linear-gradient(145deg, #f4f7fb 0%, #e9eef4 100%); min-height: 100vh; padding: 1.5rem; }
    .card { background: white; border-radius: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02); transition: transform 0.2s, box-shadow 0.2s; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.1); }
    .chart-container canvas { max-height: 200px; width: 100%; }
    .row-pair { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: stretch; }
    @media (max-width: 768px) {
      .row-pair { grid-template-columns: 1fr; gap: 1rem; }
      body { padding: 1rem; }
    }
    .info-card { display: flex; flex-direction: column; justify-content: space-between; }
  </style>
</head>
<body class="antialiased">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-emerald-700 to-teal-600 bg-clip-text text-transparent">
          🐄 Control de Producción
        </h1>
        <p class="text-gray-500 mt-1">
          Semana del <strong><?= date('d/m/Y', strtotime($domingo)) ?></strong> al <strong><?= date('d/m/Y', strtotime($sabado)) ?></strong>
        </p>
      </div>
      <div class="flex items-center gap-3 text-gray-600 bg-white/60 backdrop-blur-sm px-5 py-2 rounded-full shadow-sm">
        <i class="fas fa-calendar-alt text-emerald-500"></i>
        <span class="font-medium"><?= date('d/m/Y', strtotime($hoy)) ?></span>
      </div>
    </div>

    <!-- FILA 1: Vacas + Leche -->
    <div class="row-pair mb-6">
      <div class="card info-card overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 px-5 py-3 border-b border-emerald-100">
          <h2 class="font-bold text-gray-800 flex items-center gap-2">🐮 Vacas activas</h2>
        </div>
        <div class="p-4">
          <div class="flex justify-between items-end mb-3">
            <span class="text-3xl font-black text-emerald-700"><?= $totalVacas ?></span>
            <span class="text-sm text-gray-500">en producción</span>
          </div>
          <div class="bg-emerald-50 rounded-xl p-3">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><i class="fas fa-tint text-blue-500 mr-1"></i> Leche semanal</span>
              <span class="font-bold text-gray-800"><?= number_format($totalLecheSemana, 2) ?> L</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-600"><i class="fas fa-chart-line mr-1"></i> Promedio diario</span>
              <span class="font-medium"><?= number_format($promedioLecheDiario, 2) ?> L/día</span>
            </div>
          </div>
          <?php if (!empty($vacas)): ?>
            <div class="mt-3 max-h-32 overflow-y-auto text-xs">
              <?php foreach (array_slice($vacas, 0, 5) as $v): ?>
                <div class="flex justify-between py-1 border-b border-gray-100">
                  <?= htmlspecialchars($v['name']) ?> 
                  <span class="text-gray-400"><?= htmlspecialchars($v['breed']) ?></span>
                </div>
              <?php endforeach; ?>
              <?php if (count($vacas) > 5): ?><div class="text-gray-400 text-center py-1">+<?= count($vacas)-5 ?> más</div><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card p-5">
        <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-4">
          <div class="bg-blue-100 p-2 rounded-xl"><i class="fas fa-tint text-blue-500 text-xl"></i></div>
          <h2 class="text-lg font-bold text-gray-800">Producción diaria de leche <span class="text-sm font-normal text-gray-500">(litros)</span></h2>
        </div>
        <div class="chart-container"><canvas id="graficaLeche"></canvas></div>
      </div>
    </div>

    <!-- FILA 2: Sacrificios + Gráfica -->
    <div class="row-pair mb-6">
      <div class="card info-card overflow-hidden">
        <div class="bg-gradient-to-r from-rose-50 to-orange-50 px-5 py-3 border-b border-rose-100">
          <h2 class="font-bold text-gray-800 flex items-center gap-2">🪓 Sacrificios</h2>
        </div>
        <div class="p-4">
          <div class="mb-3">
            <p class="text-sm text-gray-500 mb-1">Hoy (<?= date('d/m', strtotime($hoy)) ?>)</p>
            <?php if ($totalSacrificiosHoy > 0): ?>
              <span class="text-2xl font-bold text-rose-700"><?= $totalSacrificiosHoy ?></span> animales
              <div class="text-xs text-gray-500 mt-1">
                <?php foreach ($sacrificiosHoy as $s): ?><?= $s['quantity'].' '.$s['animal_type'] ?> <?php endforeach; ?>
              </div>
            <?php else: ?>
              <span class="text-gray-400">Sin sacrificios hoy</span>
            <?php endif; ?>
          </div>

          <div class="bg-rose-50 rounded-xl p-3">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><i class="fas fa-calendar-week mr-1"></i> Total semana</span>
              <span class="font-bold"><?= $totalAnimalesSemana ?> animales</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-600"><i class="fas fa-weight-scale mr-1"></i> Carne estimada</span>
              <span class="font-bold text-rose-700"><?= number_format($totalKgCarneSemana) ?> kg</span>
            </div>
          </div>

          <?php if (!empty($vacasSacrificadas)): ?>
          <div class="mt-3 border-t border-rose-100 pt-3">
            <p class="text-sm font-medium text-gray-700 mb-2"><i class="fas fa-skull mr-1 text-rose-500"></i> Vacas sacrificadas recientemente</p>
            <ul class="space-y-1 text-xs">
              <?php foreach ($vacasSacrificadas as $vs): ?>
              <li class="flex justify-between">
                <span class="font-medium"><?= htmlspecialchars($vs['name'] ?: $vs['tag']) ?></span>
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
        <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-4">
          <div class="bg-rose-100 p-2 rounded-xl"><i class="fas fa-chart-bar text-rose-500 text-xl"></i></div>
          <h2 class="text-lg font-bold text-gray-800">Sacrificios diarios <span class="text-sm font-normal text-gray-500">(cantidad de animales)</span></h2>
        </div>
        <div class="chart-container"><canvas id="graficaCarne"></canvas></div>
      </div>
    </div>

    <!-- FILA 3: Gallinero + Huevos -->
    <div class="row-pair mb-6">
      <div class="card info-card overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-50 to-amber-50 px-5 py-3 border-b border-yellow-100">
          <h2 class="font-bold text-gray-800 flex items-center gap-2">🥚 Gallinero</h2>
        </div>
        <div class="p-4">
          <div class="flex justify-between items-end mb-3">
            <span class="text-3xl font-black text-amber-600"><?= number_format($totalGallinas) ?></span>
            <span class="text-sm text-gray-500">gallinas activas</span>
          </div>
          <div class="bg-amber-50 rounded-xl p-3">
            <div class="flex justify-between text-sm">
              <span class="text-gray-600"><i class="fas fa-egg mr-1"></i> Huevos/semana</span>
              <span class="font-bold"><?= number_format($totalHuevosSemana) ?> uds</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-600"><i class="fas fa-calculator mr-1"></i> Promedio diario</span>
              <span class="font-medium"><?= round($promedioHuevosDiario) ?> huevos/día</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
              <span class="text-gray-600"><i class="fas fa-percent mr-1"></i> Eficiencia</span>
              <span class="font-medium"><?= $eficienciaGallinas ?>%</span>
            </div>
          </div>
        </div>
      </div>

      <div class="card p-5">
        <div class="flex items-center gap-3 border-b border-gray-100 pb-3 mb-4">
          <div class="bg-yellow-100 p-2 rounded-xl"><i class="fas fa-egg text-yellow-600 text-xl"></i></div>
          <h2 class="text-lg font-bold text-gray-800">Producción diaria de huevos <span class="text-sm font-normal text-gray-500">(unidades)</span></h2>
        </div>
        <div class="chart-container"><canvas id="graficaHuevos"></canvas></div>
      </div>
    </div>

    <div class="mt-8 text-center text-gray-400 text-sm border-t border-gray-200 pt-6">
      <i class="fas fa-chart-line mr-1"></i> Datos actualizados · Semana del <?= date('d/m/Y', strtotime($domingo)) ?> al <?= date('d/m/Y', strtotime($sabado)) ?>
    </div>
  </div>

  <script>
    const dias = <?= json_encode($diasEtiquetas) ?>;
    const lecheData = <?= json_encode($lecheData) ?>;
    const carneData = <?= json_encode($carneData) ?>;
    const huevosData = <?= json_encode($huevosData) ?>;

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
      }
    };

    new Chart(document.getElementById('graficaLeche'), {
      type: 'bar',
      data: { labels: dias, datasets: [{ label: 'Litros', data: lecheData, backgroundColor: 'rgba(56, 189, 248, 0.7)', borderColor: '#0ea5e9', borderWidth: 1, borderRadius: 6 }] },
      options: baseOptions
    });

    new Chart(document.getElementById('graficaCarne'), {
      type: 'bar',
      data: { labels: dias, datasets: [{ label: 'Animales sacrificados', data: carneData, backgroundColor: 'rgba(244, 114, 182, 0.7)', borderColor: '#ec4899', borderWidth: 1, borderRadius: 6 }] },
      options: baseOptions
    });

    new Chart(document.getElementById('graficaHuevos'), {
      type: 'bar',
      data: { labels: dias, datasets: [{ label: 'Huevos', data: huevosData, backgroundColor: 'rgba(250, 204, 21, 0.7)', borderColor: '#eab308', borderWidth: 1, borderRadius: 6 }] },
      options: baseOptions
    });
  </script>
</body>
</html>