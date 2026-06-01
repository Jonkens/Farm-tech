<?php
// Protección: si no se recibieron datos, inicializar como array vacío
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
extract($datosVista);
// Ahora están disponibles $tab, $page, $flash, $alimentacion, etc.
?>
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
    .alert-stock { background: #fee2e2; border-left: 4px solid #ef4444; }
    .alert-warning { background: #fef9c3; border-left: 4px solid #f59e0b; }
  </style>
</head>
<body class="antialiased">
<div class="max-w-7xl mx-auto">

  <h1 class="text-3xl font-bold text-gray-800 mb-2 flex items-center gap-3">
    🍽️ Control de Alimentación
  </h1>
  <p class="text-gray-500 mb-6">Registro diario, catálogo de insumos y eficiencia nutricional</p>

  <?php if (isset($flash)): ?>
  <div class="mb-4 p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' ?>">
      <?= htmlspecialchars($flash['message']) ?>
  </div>
  <?php endif; ?>

  <!-- Pestañas -->
  <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-2 no-print">
      <a href="?tab=alimentacion" class="px-5 py-2 rounded-t-lg font-medium transition <?= $tab==='alimentacion'?'tab-active':'tab-inactive' ?>">📋 Registro Alimentación</a>
      <a href="?tab=catalogo"     class="px-5 py-2 rounded-t-lg font-medium transition <?= $tab==='catalogo'?'tab-active':'tab-inactive' ?>">📦 Catálogo Alimentos</a>
      <a href="?tab=eficiencia"   class="px-5 py-2 rounded-t-lg font-medium transition <?= $tab==='eficiencia'?'tab-active':'tab-inactive' ?>">📈 Eficiencia Nutricional</a>
  </div>

  <!-- ==================== REGISTRO DE ALIMENTACIÓN ==================== -->
  <?php if ($tab === 'alimentacion'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Historial de Alimentación</h2>

    <!-- Formulario de inserción -->
    <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
        <select name="animal_id" required class="border rounded-lg p-2">
            <option value="">Animal</option>
            <?php foreach ($animales as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?>
        </select>
        <select name="food_id" required class="border rounded-lg p-2">
            <option value="">Alimento</option>
            <?php foreach ($alimentos as $al): ?><option value="<?= $al['id'] ?>"><?= htmlspecialchars($al['name']) ?></option><?php endforeach; ?>
        </select>
        <input type="date" name="feeding_date" required class="border rounded-lg p-2">
        <input type="number" name="quantity_kg" step="0.01" placeholder="Kg" required class="border rounded-lg p-2">
        <button name="agregar_alimentacion" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
    </form>

    <!-- Tabla con columna costo -->
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Alimento</th>
            <th class="px-3 py-2 text-left">Kg</th>
            <th class="px-3 py-2 text-left">Costo $</th>
            <th class="px-3 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($alimentacion as $f): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($f['feeding_date'])) ?></td>
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($f['animal_name']) ?> <span class="text-gray-400 text-xs">(<?= $f['tag'] ?>)</span></td>
            <td class="px-3 py-2"><?= htmlspecialchars($f['food_name']) ?></td>
            <td class="px-3 py-2"><?= number_format($f['quantity_kg'], 2) ?></td>
            <td class="px-3 py-2 font-semibold">$<?= number_format($f['costo'], 2) ?></td>
            <td class="px-3 py-2 whitespace-nowrap">
              <a href="?tab=alimentacion&eliminar_alimentacion=1&id=<?= $f['id'] ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if ($totalPaginas > 1): ?>
    <div class="flex justify-center items-center space-x-2 mt-4">
        <?php if ($page > 1): ?><a href="?tab=alimentacion&page=<?= $page-1 ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">← Anterior</a><?php endif; ?>
        <span class="px-3 py-1 bg-blue-600 text-white rounded"><?= $page ?> / <?= $totalPaginas ?></span>
        <?php if ($page < $totalPaginas): ?><a href="?tab=alimentacion&page=<?= $page+1 ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Siguiente →</a><?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- ==================== CATÁLOGO DE ALIMENTOS ==================== -->
  <?php if ($tab === 'catalogo'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Catálogo de Alimentos</h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-6">
        <input type="text" name="name" placeholder="Nombre" required class="border rounded-lg p-2">
        <input type="text" name="food_type" placeholder="Tipo" required class="border rounded-lg p-2">
        <input type="number" name="cost_per_kg" step="0.01" placeholder="Costo/kg" required class="border rounded-lg p-2">
        <input type="number" name="protein_pct" step="0.01" placeholder="Proteína %" required class="border rounded-lg p-2">
        <input type="number" name="stock_kg" step="0.01" placeholder="Stock kg" required class="border rounded-lg p-2">
        <button name="agregar_alimento" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
    </form>

    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Nombre</th>
            <th class="px-3 py-2 text-left">Tipo</th>
            <th class="px-3 py-2 text-left">Costo/kg</th>
            <th class="px-3 py-2 text-left">Proteína %</th>
            <th class="px-3 py-2 text-left">Stock kg</th>
            <th class="px-3 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($catalogo as $alim): ?>
          <tr class="hover:bg-gray-50 <?= $alim['stock_kg'] < $stockMinimo ? 'alert-stock' : '' ?>">
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($alim['name']) ?></td>
            <td class="px-3 py-2"><?= ucfirst($alim['food_type']) ?></td>
            <td class="px-3 py-2">$<?= number_format($alim['cost_per_kg'], 2) ?></td>
            <td class="px-3 py-2"><?= $alim['protein_pct'] ?>%</td>
            <td class="px-3 py-2 <?= $alim['stock_kg'] < $stockMinimo ? 'text-red-600 font-bold' : '' ?>">
                <?= number_format($alim['stock_kg'], 2) ?>
                <?php if ($alim['stock_kg'] < $stockMinimo): ?><i class="fas fa-exclamation-triangle text-red-500 ml-1"></i><?php endif; ?>
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
              <a href="?tab=catalogo&eliminar_alimento=1&id=<?= $alim['id'] ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== EFICIENCIA NUTRICIONAL ==================== -->
  <?php if ($tab === 'eficiencia'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Eficiencia Nutricional</h2>

    <!-- Calculadora de FCR -->
    <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
        <h3 class="font-semibold mb-3 flex items-center gap-2"><i class="fas fa-calculator text-blue-600"></i> Calculadora de Conversión Alimenticia (FCR)</h3>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="hidden" name="calcular_fcr" value="1">
            <select name="animal_id" required class="border rounded-lg p-2">
                <option value="">Seleccionar animal</option>
                <?php foreach ($animales as $a): ?><option value="<?= $a['id'] ?>" <?= (isset($post['animal_id']) && $post['animal_id']==$a['id'])?'selected':'' ?>><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?>
            </select>
            <input type="date" name="fecha_inicio" value="<?= $post['fecha_inicio'] ?? '' ?>" required class="border rounded-lg p-2">
            <input type="date" name="fecha_fin"    value="<?= $post['fecha_fin'] ?? '' ?>" required class="border rounded-lg p-2">
            <div class="grid grid-cols-2 gap-2">
                <input type="number" name="peso_inicial" step="0.1" placeholder="Peso inicial kg" value="<?= $post['peso_inicial'] ?? '' ?>" required class="border rounded-lg p-2">
                <input type="number" name="peso_final"   step="0.1" placeholder="Peso final kg"   value="<?= $post['peso_final'] ?? '' ?>" required class="border rounded-lg p-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">Calcular FCR</button>
            </div>
        </form>

        <?php if (isset($fcrResultado)): ?>
        <div class="mt-4 p-4 bg-white rounded-lg shadow-inner">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div><span class="text-sm text-gray-500">Alimento consumido</span><p class="text-xl font-bold"><?= number_format($fcrResultado['alimento_kg'],2) ?> kg</p></div>
                <div><span class="text-sm text-gray-500">Ganancia de peso</span><p class="text-xl font-bold"><?= number_format($fcrResultado['ganancia_kg'],2) ?> kg</p></div>
                <div><span class="text-sm text-gray-500">FCR</span><p class="text-xl font-bold <?= $fcrResultado['fcr'] < 3 ? 'text-green-600' : ($fcrResultado['fcr'] > 5 ? 'text-red-600' : 'text-yellow-600') ?>"><?= $fcrResultado['fcr'] ?></p></div>
            </div>
            <form method="POST" class="mt-3 flex justify-end">
                <input type="hidden" name="guardar_fcr" value="1">
                <input type="hidden" name="animal_id" value="<?= htmlspecialchars($post['animal_id'] ?? '') ?>">
                <input type="hidden" name="measurement_date" value="<?= htmlspecialchars($post['fecha_fin'] ?? '') ?>">
                <input type="hidden" name="fcr" value="<?= $fcrResultado['fcr'] ?>">
                <input type="hidden" name="ganancia_kg" value="<?= $fcrResultado['ganancia_kg'] ?>">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">💾 Guardar en historial</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Historial de eficiencia registrada -->
    <h3 class="font-semibold mb-3">Registros anteriores</h3>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Conversión (FCR)</th>
            <th class="px-3 py-2 text-left">Ganancia Peso (kg)</th>
            <th class="px-3 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($eficiencia as $ef): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($ef['measurement_date'])) ?></td>
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($ef['animal_name']) ?> (<?= $ef['tag'] ?>)</td>
            <td class="px-3 py-2"><?= number_format($ef['feed_conversion_ratio'], 2) ?></td>
            <td class="px-3 py-2"><?= number_format($ef['weight_gain_kg'], 2) ?></td>
            <td class="px-3 py-2 whitespace-nowrap">
              <a href="?tab=eficiencia&eliminar_eficiencia=1&id=<?= $ef['id'] ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

</div>
</body>
</html>