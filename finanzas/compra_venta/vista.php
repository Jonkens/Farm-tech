<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
// Compra, Venta y Finanzas
extract($datosVista);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Compra, Venta y Finanzas | Ganadería</title>
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
    💼 Compra, Venta y Finanzas
  </h1>
  <p class="text-gray-500 mb-6">Panel de anuncios, transacciones, insumos, nómina y resumen financiero</p>

  <!-- Pestañas -->
  <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-2">
    <?php
    $tabs = [
      'resumen'    => '📊 Resumen Financiero',
      'anuncios'   => '📢 Anuncios',
      'transacciones' => '💱 Transacciones',
      'insumos'    => '📦 Insumos',
      'nomina'     => '👥 Nómina'
    ];
    $active = $_GET['tab'] ?? 'resumen';
    foreach ($tabs as $key => $label):
      $isActive = ($active === $key);
    ?>
      <a href="?tab=<?= $key ?>"
         class="px-5 py-2 rounded-t-lg font-medium transition <?= $isActive ? 'tab-active' : 'tab-inactive' ?>">
        <?= $label ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- ==================== RESUMEN FINANCIERO ==================== -->
  <?php if ($active === 'resumen'): ?>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="card p-6 bg-white text-center">
      <i class="fas fa-arrow-up text-green-500 text-3xl"></i>
      <p class="text-sm text-gray-500 mt-2">Ingresos (<?= date('M Y') ?>)</p>
      <p class="text-2xl font-bold text-green-600">$<?= number_format($ingresosMes, 2) ?></p>
    </div>
    <div class="card p-6 bg-white text-center">
      <i class="fas fa-arrow-down text-red-500 text-3xl"></i>
      <p class="text-sm text-gray-500 mt-2">Gastos (<?= date('M Y') ?>)</p>
      <p class="text-2xl font-bold text-red-600">$<?= number_format($gastosMes, 2) ?></p>
    </div>
    <div class="card p-6 bg-white text-center">
      <i class="fas fa-chart-line text-indigo-500 text-3xl"></i>
      <p class="text-sm text-gray-500 mt-2">Ganancia Neta</p>
      <p class="text-2xl font-bold text-indigo-600">$<?= number_format($gananciaMes, 2) ?></p>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== ANUNCIOS ==================== -->
  <?php if ($active === 'anuncios'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Anuncios de Compra/Venta de Animales</h2>
    <?php if (empty($anuncios)): ?>
      <p class="text-gray-400">No hay anuncios activos en este momento.</p>
    <?php else: ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead><tr><th>Tipo</th><th>Tipo Animal</th><th>Raza</th><th>Cantidad</th><th>Peso(kg)</th><th>Precio/Unidad</th><th>Usuario</th></tr></thead>
        <tbody class="divide-y">
          <?php foreach ($anuncios as $a): ?>
          <tr class="hover:bg-gray-50">
            <td><span class="px-2 py-1 rounded text-xs font-bold <?= $a['ad_type'] === 'VENT' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>"><?= $a['ad_type'] === 'VENT' ? 'Venta' : 'Compra' ?></span></td>
            <td><?= htmlspecialchars($a['animal_type']) ?></td>
            <td><?= htmlspecialchars($a['breed'] ?: '-') ?></td>
            <td><?= $a['quantity'] ?></td>
            <td><?= $a['weight_kg'] ? number_format($a['weight_kg'], 2) : '-' ?></td>
            <td>$<?= number_format($a['price_per_unit'], 2) ?></td>
            <td><?= htmlspecialchars($a['usuario']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- ==================== TRANSACCIONES ==================== -->
  <?php if ($active === 'transacciones'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Historial de Transacciones</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead><tr><th>Fecha</th><th>Vendedor</th><th>Comprador</th><th>Animal</th><th>Cant.</th><th>Monto Total</th></tr></thead>
        <tbody class="divide-y">
          <?php foreach ($transacciones as $t): ?>
          <tr class="hover:bg-gray-50">
            <td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
            <td><?= htmlspecialchars($t['seller']) ?></td>
            <td><?= htmlspecialchars($t['buyer']) ?></td>
            <td><?= htmlspecialchars($t['animal_type']) ?></td>
            <td><?= $t['quantity'] ?></td>
            <td class="font-semibold">$<?= number_format($t['total_amount'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== INSUMOS ==================== -->
  <?php if ($active === 'insumos'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Órdenes de Compra de Insumos</h2>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead><tr><th>ID</th><th>Fecha</th><th>Proveedor</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody class="divide-y">
          <?php foreach ($ordenesCompra as $o): ?>
          <tr class="hover:bg-gray-50">
            <td><?= $o['id'] ?></td>
            <td><?= date('d/m/Y', strtotime($o['order_date'])) ?></td>
            <td><?= htmlspecialchars($o['supplier']) ?></td>
            <td>$<?= number_format($o['total_amount'], 2) ?></td>
            <td><span class="px-2 py-1 rounded text-xs <?= $o['status'] === 'recibida' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>"><?= $o['status'] ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== NÓMINA ==================== -->
  <?php if ($active === 'nomina'): ?>
  <div class="card p-6">
    <h2 class="text-xl font-semibold mb-4">Última Nómina Registrada</h2>
    <p class="text-sm text-gray-500 mb-2">Total pagado: <strong>$<?= number_format($totalPlanilla, 2) ?></strong></p>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead><tr><th>Empleado</th><th>Cargo</th><th>Sueldo Bruto</th><th>Deducciones</th><th>Neto</th><th>Fecha Pago</th></tr></thead>
        <tbody class="divide-y">
          <?php foreach ($nominaUltima as $n): ?>
          <tr class="hover:bg-gray-50">
            <td><?= htmlspecialchars($n['name']) ?></td>
            <td><?= htmlspecialchars($n['role']) ?></td>
            <td>$<?= number_format($n['gross_salary'], 2) ?></td>
            <td>$<?= number_format($n['deductions'], 2) ?></td>
            <td class="font-semibold">$<?= number_format($n['net_pay'], 2) ?></td>
            <td><?= date('d/m/Y', strtotime($n['payment_date'])) ?></td>
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