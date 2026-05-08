<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
extract($datosVista);
?>
//control de alimentacion
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Control de Alimentación | Ganadería</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Inter', sans-serif; }
    body { background: linear-gradient(145deg, #f4f7fb 0%, #e9eef4 100%); min-height: 100vh; padding: 1.5rem; }
    .card { background: white; border-radius: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
    .tab-active { background-color: #3b82f6; color: white; }
    .tab-inactive { background-color: #f3f4f6; color: #4b5563; }
    .tab-inactive:hover { background-color: #e5e7eb; }
    table { border-collapse: separate; border-spacing: 0; }
    th { background: #f9fafb; }
  </style>
</head>
<body class="antialiased">
<div class="max-w-7xl mx-auto">

  <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-3">
    🍽️ Control de Alimentación
  </h1>
  <p class="text-gray-500 mb-6">Registro diario de alimentación, catálogo de insumos y eficiencia nutricional</p>

  <!-- Pestañas -->
  <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-2">
    <?php
    $tabs = [
      'alimentacion'   => '📋 Registro Alimentación',
      'catalogo'       => '📦 Catálogo de Alimentos',
      'eficiencia'     => '📈 Eficiencia Nutricional',
    ];
    $active = $_GET['tab'] ?? 'alimentacion';
    foreach ($tabs as $key => $label):
      $isActive = ($active === $key);
    ?>
      <a href="?tab=<?= $key ?>"
         class="px-5 py-2 rounded-t-lg font-medium transition <?= $isActive ? 'tab-active' : 'tab-inactive' ?>">
        <?= $label ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- ==================== REGISTRO DE ALIMENTACIÓN ==================== -->
  <?php if ($active === 'alimentacion'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Historial de Alimentación</h2>
    <?php if (empty($alimentacion)): ?>
      <p class="text-gray-400">No hay registros de alimentación.</p>
    <?php else: ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Raza</th>
            <th class="px-3 py-2 text-left">Alimento</th>
            <th class="px-3 py-2 text-left">Cantidad (kg)</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php foreach ($alimentacion as $f): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($f['feeding_date'])) ?></td>
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($f['animal_name']) ?> <span class="text-gray-400 text-xs">(<?= $f['tag'] ?>)</span></td>
            <td class="px-3 py-2"><?= htmlspecialchars($f['breed'] ?: '-') ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($f['food_name']) ?></td>
            <td class="px-3 py-2"><?= number_format($f['quantity_kg'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- ==================== CATÁLOGO DE ALIMENTOS ==================== -->
  <?php if ($active === 'catalogo'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Alimentos disponibles</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Nombre</th>
            <th class="px-3 py-2 text-left">Tipo</th>
            <th class="px-3 py-2 text-left">Costo/kg</th>
            <th class="px-3 py-2 text-left">Proteína (%)</th>
            <th class="px-3 py-2 text-left">Stock (kg)</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php foreach ($catalogo as $alim): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($alim['name']) ?></td>
            <td class="px-3 py-2"><?= ucfirst($alim['food_type']) ?></td>
            <td class="px-3 py-2">$<?= number_format($alim['cost_per_kg'], 2) ?></td>
            <td class="px-3 py-2"><?= $alim['protein_pct'] ?>%</td>
            <td class="px-3 py-2"><?= number_format($alim['stock_kg'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== EFICIENCIA NUTRICIONAL ==================== -->
  <?php if ($active === 'eficiencia'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Eficiencia Nutricional</h2>
    <?php if (empty($eficiencia)): ?>
      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-yellow-700">
        <i class="fas fa-info-circle mr-2"></i> No hay registros de eficiencia nutricional. Esta sección mostrará la conversión alimenticia cuando se registren datos de ganancia de peso y alimento consumido.
      </div>
    <?php else: ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Conversión (FC)</th>
            <th class="px-3 py-2 text-left">Ganancia Peso (kg)</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <?php foreach ($eficiencia as $ef): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($ef['measurement_date'])) ?></td>
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($ef['animal_name']) ?> (<?= $ef['tag'] ?>)</td>
            <td class="px-3 py-2"><?= $ef['feed_conversion_ratio'] ? number_format($ef['feed_conversion_ratio'], 2) : '-' ?></td>
            <td class="px-3 py-2"><?= $ef['weight_gain_kg'] ? number_format($ef['weight_gain_kg'], 2) : '-' ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div>
</body>
</html>