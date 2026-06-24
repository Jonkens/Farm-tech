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
  <title>Control de Alimentación | Ganadería</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Inter', sans-serif; }
    body {
      background: #1a4d2a;
      background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                        repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
      background-size: 30px 30px, 12px 12px;
      min-height: 100vh;
      padding: 1.5rem;
    }
    .card-ganadero {
      background: #fff9ef;
      border-radius: 1.5rem;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
      border: 1px solid #e2d4b5;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-ganadero:hover {
      transform: translateY(-2px);
      box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.25);
    }
    .tab-active {
      background-color: #2d6a4f;
      color: #fef5e6;
      border-bottom: 3px solid #f7b32b;
    }
    .tab-inactive {
      background-color: #fef5e6;
      color: #5a3e1b;
    }
    .tab-inactive:hover {
      background-color: #f0e5d2;
    }
    .btn-primary {
      background-color: #2d6a4f;
    }
    .btn-primary:hover {
      background-color: #1f4d38;
    }
    .btn-secondary {
      background-color: #b87c4f;
    }
    .btn-secondary:hover {
      background-color: #9a623b;
    }
    .alert-stock {
      background: #fee2e2;
      border-left: 4px solid #ef4444;
    }
    .badge-ganado {
      background: #e9dfc7;
      color: #5a3e1b;
      border-radius: 2rem;
      padding: 0.2rem 0.6rem;
      font-size: 0.7rem;
      font-weight: 500;
    }
    .table-ganado thead {
      background: #2d6a4f;
      color: #fef5e6;
    }
    .table-ganado tbody tr:hover {
      background-color: #fef1df;
    }
  </style>
</head>
<body>
<div class="max-w-7xl mx-auto">

  <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-3xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
        <i class="fas fa-utensils text-[#f7b32b]"></i> Control de Alimentación
      </h1>
      <p class="text-[#e2d4b5] mt-1 text-sm">Registro diario, catálogo de insumos y eficiencia nutricional</p>
    </div>
  </div>

  <?php if (isset($flash)): ?>
  <div class="mb-4 p-4 rounded-lg <?= $flash['type'] === 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' ?>">
      <?= htmlspecialchars($flash['message']) ?>
  </div>
  <?php endif; ?>

  <!-- Pestañas -->
  <div class="flex flex-wrap gap-2 mb-6 pb-1 border-b border-[#ecdbaa]/40">
      <a href="?tab=alimentacion" class="px-5 py-2 rounded-t-lg font-medium transition <?= $tab==='alimentacion' ? 'tab-active' : 'tab-inactive' ?>"> Registro Alimentación</a>
      <a href="?tab=catalogo"     class="px-5 py-2 rounded-t-lg font-medium transition <?= $tab==='catalogo' ? 'tab-active' : 'tab-inactive' ?>"> Catálogo Alimentos</a>
      <a href="?tab=eficiencia"   class="px-5 py-2 rounded-t-lg font-medium transition <?= $tab==='eficiencia' ? 'tab-active' : 'tab-inactive' ?>"> Eficiencia Nutricional</a>
  </div>

  <!-- ==================== REGISTRO DE ALIMENTACIÓN ==================== -->
  <?php if ($tab === 'alimentacion'): ?>
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Historial de Alimentación</h2>

    <!-- Formulario -->
    <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6">
        <select name="animal_id" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
            <option value="">Animal</option>
            <?php foreach ($animales as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?>
        </select>
        <select name="food_id" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
            <option value="">Alimento</option>
            <?php foreach ($alimentos as $al): ?><option value="<?= $al['id'] ?>"><?= htmlspecialchars($al['name']) ?></option><?php endforeach; ?>
        </select>
        <input type="date" name="feeding_date" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <input type="number" name="quantity_kg" step="0.01" placeholder="Kg" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <button name="agregar_alimentacion" class="btn-primary text-white py-2 px-4 rounded-lg transition"> Agregar</button>
    </form>

    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr class="bg-[#2d6a4f] text-[#fef5e6]">
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Alimento</th>
            <th class="px-3 py-2 text-left">Kg</th>
            <th class="px-3 py-2 text-left">Costo $</th>
            <th class="px-3 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($alimentacion as $f): ?>
          <tr class="hover:bg-[#fef1df] transition">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($f['feeding_date'])) ?></td>
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($f['animal_name']) ?> <span class="text-gray-500 text-xs">(<?= $f['tag'] ?>)</span></td>
            <td class="px-3 py-2"><?= htmlspecialchars($f['food_name']) ?></td>
            <td class="px-3 py-2"><?= number_format($f['quantity_kg'], 2) ?></td>
            <td class="px-3 py-2 font-semibold text-[#b87c4f]">$<?= number_format($f['costo'], 2) ?></td>
            <td class="px-3 py-2 whitespace-nowrap">
              <a href="?tab=alimentacion&eliminar_alimentacion=1&id=<?= $f['id'] ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php if ($totalPaginas > 1): ?>
    <div class="flex justify-center items-center space-x-2 mt-4">
        <?php if ($page > 1): ?><a href="?tab=alimentacion&page=<?= $page-1 ?>" class="px-3 py-1 bg-[#d4a373] rounded hover:bg-[#b87c4f] text-white transition">← Anterior</a><?php endif; ?>
        <span class="px-3 py-1 bg-[#2d6a4f] text-white rounded"><?= $page ?> / <?= $totalPaginas ?></span>
        <?php if ($page < $totalPaginas): ?><a href="?tab=alimentacion&page=<?= $page+1 ?>" class="px-3 py-1 bg-[#d4a373] rounded hover:bg-[#b87c4f] text-white transition">Siguiente →</a><?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- ==================== CATÁLOGO DE ALIMENTOS ==================== -->
  <?php if ($tab === 'catalogo'): ?>
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Catálogo de Alimentos</h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-6">
        <input type="text" name="name" placeholder="Nombre" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <input type="text" name="food_type" placeholder="Tipo" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <input type="number" name="cost_per_kg" step="0.01" placeholder="Costo/kg" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <input type="number" name="protein_pct" step="0.01" placeholder="Proteína %" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <input type="number" name="stock_kg" step="0.01" placeholder="Stock kg" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
        <button name="agregar_alimento" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
    </form>

    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr class="bg-[#2d6a4f] text-[#fef5e6]">
            <th class="px-3 py-2 text-left">Nombre</th>
            <th class="px-3 py-2 text-left">Tipo</th>
            <th class="px-3 py-2 text-left">Costo/kg</th>
            <th class="px-3 py-2 text-left">Proteína %</th>
            <th class="px-3 py-2 text-left">Stock kg</th>
            <th class="px-3 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($catalogo as $alim): ?>
          <tr class="hover:bg-[#fef1df] transition <?= $alim['stock_kg'] < $stockMinimo ? 'alert-stock' : '' ?>">
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($alim['name']) ?></td>
            <td class="px-3 py-2"><?= ucfirst($alim['food_type']) ?></td>
            <td class="px-3 py-2">$<?= number_format($alim['cost_per_kg'], 2) ?></td>
            <td class="px-3 py-2"><?= $alim['protein_pct'] ?>%</td>
            <td class="px-3 py-2 <?= $alim['stock_kg'] < $stockMinimo ? 'text-red-700 font-bold' : '' ?>">
                <?= number_format($alim['stock_kg'], 2) ?>
                <?php if ($alim['stock_kg'] < $stockMinimo): ?><i class="fas fa-exclamation-triangle text-red-500 ml-1"></i><?php endif; ?>
            </td>
            <td class="px-3 py-2 whitespace-nowrap">
              <a href="?tab=catalogo&eliminar_alimento=1&id=<?= $alim['id'] ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
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
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Eficiencia Nutricional</h2>

    <!-- Calculadora FCR -->
    <div class="bg-[#fef5e6] rounded-xl p-4 mb-6 border border-[#ecdbaa]">
        <h3 class="font-semibold mb-3 flex items-center gap-2 text-[#5a3e1b]"><i class="fas fa-calculator text-[#b87c4f]"></i> Calculadora de Conversión Alimenticia (FCR)</h3>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="hidden" name="calcular_fcr" value="1">
            <select name="animal_id" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
                <option value="">Seleccionar animal</option>
                <?php foreach ($animales as $a): ?><option value="<?= $a['id'] ?>" <?= (isset($post['animal_id']) && $post['animal_id']==$a['id'])?'selected':'' ?>><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?>
            </select>
            <input type="date" name="fecha_inicio" value="<?= $post['fecha_inicio'] ?? '' ?>" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
            <input type="date" name="fecha_fin"    value="<?= $post['fecha_fin'] ?? '' ?>" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="peso_inicial" step="0.1" placeholder="Peso inicial kg" value="<?= $post['peso_inicial'] ?? '' ?>" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="peso_final"   step="0.1" placeholder="Peso final kg"   value="<?= $post['peso_final'] ?? '' ?>" required class="border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7]">
            <button type="submit" class="btn-secondary text-white px-4 py-2 rounded-lg transition">Calcular FCR</button>
        </form>

        <?php if (isset($fcrResultado)): ?>
        <div class="mt-4 p-4 bg-white rounded-lg shadow-inner">
            <div class="grid grid-cols-3 gap-4 text-center">
                <div><span class="text-sm text-gray-500">Alimento consumido</span><p class="text-xl font-bold"><?= number_format($fcrResultado['alimento_kg'],2) ?> kg</p></div>
                <div><span class="text-sm text-gray-500">Ganancia de peso</span><p class="text-xl font-bold"><?= number_format($fcrResultado['ganancia_kg'],2) ?> kg</p></div>
                <div><span class="text-sm text-gray-500">FCR</span><p class="text-xl font-bold <?= $fcrResultado['fcr'] < 3 ? 'text-green-700' : ($fcrResultado['fcr'] > 5 ? 'text-red-700' : 'text-yellow-700') ?>"><?= $fcrResultado['fcr'] ?></p></div>
            </div>
            <form method="POST" class="mt-3 flex justify-end">
                <input type="hidden" name="guardar_fcr" value="1">
                <input type="hidden" name="animal_id" value="<?= htmlspecialchars($post['animal_id'] ?? '') ?>">
                <input type="hidden" name="measurement_date" value="<?= htmlspecialchars($post['fecha_fin'] ?? '') ?>">
                <input type="hidden" name="fcr" value="<?= $fcrResultado['fcr'] ?>">
                <input type="hidden" name="ganancia_kg" value="<?= $fcrResultado['ganancia_kg'] ?>">
                <button type="submit" class="bg-[#2d6a4f] hover:bg-[#1f4d38] text-white px-4 py-2 rounded-lg text-sm transition">💾 Guardar en historial</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <h3 class="font-semibold mb-3 text-[#5a3e1b]">Registros anteriores</h3>
    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr class="bg-[#2d6a4f] text-[#fef5e6]">
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Conversión (FCR)</th>
            <th class="px-3 py-2 text-left">Ganancia Peso (kg)</th>
            <th class="px-3 py-2 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($eficiencia as $ef): ?>
          <tr class="hover:bg-[#fef1df] transition">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($ef['measurement_date'])) ?></td>
            <td class="px-3 py-2 font-medium"><?= htmlspecialchars($ef['animal_name']) ?> (<?= $ef['tag'] ?>)</td>
            <td class="px-3 py-2"><?= number_format($ef['feed_conversion_ratio'], 2) ?></td>
            <td class="px-3 py-2"><?= number_format($ef['weight_gain_kg'], 2) ?></td>
            <td class="px-3 py-2 whitespace-nowrap">
              <a href="?tab=eficiencia&eliminar_eficiencia=1&id=<?= $ef['id'] ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
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