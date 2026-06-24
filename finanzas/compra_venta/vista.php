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
  <title>Compra, Venta y Finanzas | Ganadería</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
  <style>
    * { font-family: 'Inter', sans-serif; }
    body {
      font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
      background: #1a4d2a;
      background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                        repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
      background-size: 30px 30px, 12px 12px;
      background-attachment: fixed;
      min-height: 100vh;
      padding: 1.5rem;
    }
    .card-ganadero {
      background: rgba(255, 251, 240, 0.97);
      backdrop-filter: blur(8px);
      border-radius: 1.5rem;
      border: 1px solid #e2d4b5;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
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
    .badge-venta {
      background-color: #dcfce7;
      color: #15803d;
    }
    .badge-compra {
      background-color: #dbeafe;
      color: #1d4ed8;
    }
    .badge-recibida {
      background-color: #dcfce7;
      color: #15803d;
    }
    .badge-pendiente {
      background-color: #fef9c3;
      color: #92400e;
    }
    table {
      border-collapse: separate;
      border-spacing: 0;
    }
    th {
      background-color: #e9dfc7;
      color: #4a3b22;
      font-weight: 600;
    }
    .table-ganado tbody tr:hover {
      background-color: #fef1df;
    }
  </style>
</head>
<body>
<div class="max-w-7xl mx-auto">

  <div class="mb-6">
    <h1 class="text-3xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
      <i class="fas fa-chart-line text-[#f7b32b]"></i> Compra, Venta y Finanzas
    </h1>
    <p class="text-[#e2d4b5] mt-1 text-sm">Panel de anuncios, transacciones, insumos, nómina y resumen financiero</p>
  </div>

  <!-- Pestañas -->
  <div class="flex flex-wrap gap-2 mb-6 pb-1 border-b border-[#ecdbaa]/40">
    <?php
    $tabs = [
      'resumen'    => ' Resumen Financiero',
      'anuncios'   => ' Anuncios',
      'transacciones' => ' Transacciones',
      'insumos'    => ' Insumos',
      'nomina'     => ' Nómina'
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
    <div class="card-ganadero p-6 text-center">
      <i class="fas fa-arrow-up text-green-600 text-3xl"></i>
      <p class="text-sm text-gray-600 mt-2">Ingresos (<?= date('M Y') ?>)</p>
      <p class="text-2xl font-bold text-green-700">$<?= number_format($ingresosMes, 2) ?></p>
    </div>
    <div class="card-ganadero p-6 text-center">
      <i class="fas fa-arrow-down text-red-600 text-3xl"></i>
      <p class="text-sm text-gray-600 mt-2">Gastos (<?= date('M Y') ?>)</p>
      <p class="text-2xl font-bold text-red-700">$<?= number_format($gastosMes, 2) ?></p>
    </div>
    <div class="card-ganadero p-6 text-center">
      <i class="fas fa-chart-line text-indigo-600 text-3xl"></i>
      <p class="text-sm text-gray-600 mt-2">Ganancia Neta</p>
      <p class="text-2xl font-bold text-indigo-700">$<?= number_format($gananciaMes, 2) ?></p>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== ANUNCIOS ==================== -->
  <?php if ($active === 'anuncios'): ?>
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Anuncios de Compra/Venta de Animales</h2>
    <?php if (empty($anuncios)): ?>
      <p class="text-gray-500">No hay anuncios activos en este momento.</p>
    <?php else: ?>
    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Tipo</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Raza</th>
            <th class="px-3 py-2 text-left">Cant.</th>
            <th class="px-3 py-2 text-left">Peso(kg)</th>
            <th class="px-3 py-2 text-left">Precio/u</th>
            <th class="px-3 py-2 text-left">Usuario</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($anuncios as $a): ?>
          <tr class="hover:bg-[#fef1df] transition">
            <td class="px-3 py-2">
              <span class="inline-block px-2 py-1 rounded text-xs font-bold <?= $a['ad_type'] === 'VENT' ? 'badge-venta' : 'badge-compra' ?>">
                <?= $a['ad_type'] === 'VENT' ? 'Venta' : 'Compra' ?>
              </span>
            <td>
            <td class="px-3 py-2"><?= htmlspecialchars($a['animal_type']) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($a['breed'] ?: '-') ?></td>
            <td class="px-3 py-2"><?= $a['quantity'] ?></td>
            <td class="px-3 py-2"><?= $a['weight_kg'] ? number_format($a['weight_kg'], 2) : '-' ?></td>
            <td class="px-3 py-2">$<?= number_format($a['price_per_unit'], 2) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($a['usuario']) ?></td>
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
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Historial de Transacciones</h2>
    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Vendedor</th>
            <th class="px-3 py-2 text-left">Comprador</th>
            <th class="px-3 py-2 text-left">Animal</th>
            <th class="px-3 py-2 text-left">Cant.</th>
            <th class="px-3 py-2 text-left">Monto Total</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($transacciones as $t): ?>
          <tr class="hover:bg-[#fef1df] transition">
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($t['seller']) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($t['buyer']) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($t['animal_type']) ?></td>
            <td class="px-3 py-2"><?= $t['quantity'] ?></td>
            <td class="px-3 py-2 font-semibold text-[#2d6a4f]">$<?= number_format($t['total_amount'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== INSUMOS (Órdenes de compra) ==================== -->
  <?php if ($active === 'insumos'): ?>
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Órdenes de Compra de Insumos</h2>
    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">ID</th>
            <th class="px-3 py-2 text-left">Fecha</th>
            <th class="px-3 py-2 text-left">Proveedor</th>
            <th class="px-3 py-2 text-left">Total</th>
            <th class="px-3 py-2 text-left">Estado</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($ordenesCompra as $o): ?>
          <tr class="hover:bg-[#fef1df] transition">
            <td class="px-3 py-2"><?= $o['id'] ?></td>
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($o['order_date'])) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($o['supplier']) ?></td>
            <td class="px-3 py-2 font-semibold">$<?= number_format($o['total_amount'], 2) ?></td>
            <td class="px-3 py-2">
              <span class="inline-block px-2 py-1 rounded text-xs font-bold <?= $o['status'] === 'recibida' ? 'badge-venta' : 'badge-pendiente' ?>">
                <?= ucfirst($o['status']) ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php endif; ?>

  <!-- ==================== NÓMINA ==================== -->
  <?php if ($active === 'nomina'): ?>
  <div class="card-ganadero p-6">
    <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Última Nómina Registrada</h2>
    <p class="text-sm text-gray-600 mb-2">Total pagado: <strong class="text-[#2d6a4f]">$<?= number_format($totalPlanilla, 2) ?></strong></p>
    <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
      <table class="min-w-full text-sm table-ganado">
        <thead>
          <tr>
            <th class="px-3 py-2 text-left">Empleado</th>
            <th class="px-3 py-2 text-left">Cargo</th>
            <th class="px-3 py-2 text-left">Sueldo Bruto</th>
            <th class="px-3 py-2 text-left">Deducciones</th>
            <th class="px-3 py-2 text-left">Neto</th>
            <th class="px-3 py-2 text-left">Fecha Pago</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-[#f0e5d2]">
          <?php foreach ($nominaUltima as $n): ?>
          <tr class="hover:bg-[#fef1df] transition">
            <td class="px-3 py-2"><?= htmlspecialchars($n['name']) ?></td>
            <td class="px-3 py-2"><?= htmlspecialchars($n['role']) ?></td>
            <td class="px-3 py-2">$<?= number_format($n['gross_salary'], 2) ?></td>
            <td class="px-3 py-2">$<?= number_format($n['deductions'], 2) ?></td>
            <td class="px-3 py-2 font-semibold text-[#2d6a4f]">$<?= number_format($n['net_pay'], 2) ?></td>
            <td class="px-3 py-2"><?= date('d/m/Y', strtotime($n['payment_date'])) ?></td>
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