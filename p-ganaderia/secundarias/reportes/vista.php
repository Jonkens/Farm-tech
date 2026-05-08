<?php
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
extract($datosVista);
//vista de reportes generales del sistema
function totalDistribucion($array) {
    return array_sum(array_column($array, 'total'));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes del Sistema | Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(145deg, #f4f7fb 0%, #e9eef4 100%); padding: 1.5rem; }
        .card { background: white; border-radius: 1.2rem; box-shadow: 0 8px 20px rgba(0,0,0,0.06); padding: 1.5rem; margin-bottom: 1.5rem; }
        .btn-pdf { background-color: #2563eb; color: white; padding: 0.75rem 2rem; border-radius: 0.75rem; font-weight: 600; transition: background 0.2s; }
        .btn-pdf:hover { background-color: #1d4ed8; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; color: #475569; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
        td, th { padding: 0.65rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }

        /* Ocultar emojis al imprimir */
        .emoji { }
        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .card { box-shadow: none; border: 1px solid #e2e8f0; page-break-inside: avoid; }
            .emoji { display: none; }
        }
    </style>
</head>
<body class="antialiased">
<div class="max-w-7xl mx-auto">

    <!-- Encabezado con selector de período -->
    <div class="flex flex-wrap items-center justify-between mb-6 no-print">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <span class="emoji">📋</span> Reportes del Sistema
        </h1>
        <form method="GET" class="flex items-center gap-3 bg-white rounded-xl px-4 py-2 shadow-sm">
            <input type="hidden" name="tab" value="reportes">
            <select name="periodo" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500">
                <option value="dia"   <?= $periodo === 'dia'   ? 'selected' : '' ?>>Día actual</option>
                <option value="semana"<?= $periodo === 'semana'? 'selected' : '' ?>>Semana actual</option>
                <option value="mes"   <?= $periodo === 'mes'   ? 'selected' : '' ?>>Mes actual</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                🔄 Actualizar
            </button>
        </form>
    </div>

    <!-- Resumen rápido -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card text-center"><p class="text-sm text-gray-500"><span class="emoji">🐄</span> Vacas activas</p><p class="text-2xl font-bold"><?= count($crecimiento) ?></p></div>
        <div class="card text-center"><p class="text-sm text-gray-500"><span class="emoji">🥛</span> Leche (<?= $periodo ?>)</p><p class="text-2xl font-bold"><?= number_format($lecheTotal, 2) ?> L</p></div>
        <div class="card text-center"><p class="text-sm text-gray-500"><span class="emoji">🪓</span> Sacrificios</p><p class="text-2xl font-bold"><?= array_sum(array_column($sacrificios, 'cantidad')) ?></p></div>
        <div class="card text-center"><p class="text-sm text-gray-500"><span class="emoji">🥚</span> Huevos</p><p class="text-2xl font-bold"><?= number_format($huevosTotal) ?> uds</p></div>
    </div>

    <!-- 1. Producción de Leche -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">🥛</span> Producción de Leche</h2>
        <p class="text-sm text-gray-500 mb-2">Período: <?= date('d/m/Y', strtotime($inicio)) ?> al <?= date('d/m/Y', strtotime($fin)) ?></p>
        <table>
            <thead><tr><th>Animal</th><th>Litros</th></tr></thead>
            <tbody>
                <?php foreach ($lecheDetalle as $l): ?>
                <tr><td><?= htmlspecialchars($l['animal']) ?></td><td><?= number_format($l['litros'], 2) ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($lecheDetalle)): ?><tr><td colspan="2" class="text-center">Sin registros</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 2. Sacrificios -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">🪓</span> Sacrificios</h2>
        <table>
            <thead><tr><th>Tipo</th><th>Cantidad</th><th>Kg estimados</th></tr></thead>
            <tbody>
                <?php foreach ($sacrificios as $s): ?>
                <tr><td><?= htmlspecialchars($s['tipo']) ?></td><td><?= $s['cantidad'] ?></td><td><?= number_format($s['kg_estimados']) ?> kg</td></tr>
                <?php endforeach; ?>
                <?php if (empty($sacrificios)): ?><tr><td colspan="3" class="text-center">Sin sacrificios</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 3. Distribución del Hato -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">🐄</span> Distribución del Hato</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Raza -->
            <div>
                <h3 class="font-medium text-gray-600 mb-2">Por Raza</h3>
                <table>
                    <thead><tr><th>Raza</th><th>Total</th><th>%</th></tr></thead>
                    <tbody>
                        <?php $totalR = totalDistribucion($razas); foreach ($razas as $r): ?>
                        <tr><td><?= $r['breed'] ?></td><td><?= $r['total'] ?></td><td><?= $totalR ? round($r['total']/$totalR*100,1) : 0 ?>%</td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Sexo -->
            <div>
                <h3 class="font-medium text-gray-600 mb-2">Por Sexo</h3>
                <table>
                    <thead><tr><th>Sexo</th><th>Total</th><th>%</th></tr></thead>
                    <tbody>
                        <?php $totalS = totalDistribucion($sexos); foreach ($sexos as $s): ?>
                        <tr><td><?= $s['gender'] === 'H' ? 'Hembra' : 'Macho' ?></td><td><?= $s['total'] ?></td><td><?= $totalS ? round($s['total']/$totalS*100,1) : 0 ?>%</td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Especie -->
            <div>
                <h3 class="font-medium text-gray-600 mb-2">Por Especie</h3>
                <table>
                    <thead><tr><th>Especie</th><th>Total</th><th>%</th></tr></thead>
                    <tbody>
                        <?php $totalE = totalDistribucion($especies); foreach ($especies as $e): ?>
                        <tr><td><?= $e['tipo'] ?></td><td><?= $e['total'] ?></td><td><?= $totalE ? round($e['total']/$totalE*100,1) : 0 ?>%</td></tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- 4. Crecimiento -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">📈</span> Crecimiento (20 mayores pesos)</h2>
        <table>
            <thead><tr><th>Nombre</th><th>Raza</th><th>Peso (kg)</th></tr></thead>
            <tbody>
                <?php foreach ($crecimiento as $c): ?>
                <tr><td><?= htmlspecialchars($c['name']) ?></td><td><?= htmlspecialchars($c['breed']) ?></td><td><?= number_format($c['weight_kg'], 1) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 5. Historial de Salud -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">💊</span> Historial de Salud</h2>
        <table>
            <thead><tr><th>Fecha</th><th>Tipo</th><th>Producto</th><th>Dosis</th><th>Animal</th></tr></thead>
            <tbody>
                <?php foreach ($historialSalud as $h): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($h['event_date'])) ?></td>
                    <td><?= htmlspecialchars($h['event_type']) ?></td>
                    <td><?= htmlspecialchars($h['product_used'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($h['dosage'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($h['animal_name'] ?: 'Lote') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($historialSalud)): ?><tr><td colspan="5" class="text-center">Sin registros de salud</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- 6. Resumen Financiero -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">💰</span> Resumen Financiero</h2>
        <p class="text-sm text-gray-500 mb-2">Ingresos: $<?= number_format($ingresos,2) ?> | Gastos: $<?= number_format($gastos,2) ?> | <strong>Ganancia: $<?= number_format($ganancia,2) ?></strong></p>

        <h3 class="font-medium mt-4 mb-2">Transacciones</h3>
        <table>
            <thead><tr><th>Fecha</th><th>Tipo</th><th>Cant.</th><th>Monto</th></tr></thead>
            <tbody>
                <?php foreach ($transacciones as $t): ?>
                <tr><td><?= date('d/m/Y', strtotime($t['transaction_date'])) ?></td><td><?= $t['tipo'] ?></td><td><?= $t['quantity'] ?></td><td>$<?= number_format($t['total_amount'],2) ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($transacciones)): ?><tr><td colspan="4" class="text-center">Sin transacciones</td></tr><?php endif; ?>
            </tbody>
        </table>

        <h3 class="font-medium mt-4 mb-2">Nómina Reciente</h3>
        <table>
            <thead><tr><th>Empleado</th><th>Cargo</th><th>Neto</th></tr></thead>
            <tbody>
                <?php foreach ($nomina as $n): ?>
                <tr><td><?= $n['name'] ?></td><td><?= $n['role'] ?></td><td>$<?= number_format($n['net_pay'],2) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="font-medium mt-4 mb-2">Ventas de Productos</h3>
        <table>
            <thead><tr><th>Producto</th><th>Cantidad</th><th>Total $</th></tr></thead>
            <tbody>
                <?php foreach ($resumenVentas as $v): ?>
                <tr><td><?= htmlspecialchars($v['product_type']) ?></td><td><?= number_format($v['cantidad'],2) ?></td><td>$<?= number_format($v['total'],2) ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($resumenVentas)): ?><tr><td colspan="3" class="text-center">Sin ventas en el período</td></tr><?php endif; ?>
            </tbody>
        </table>

        <h3 class="font-medium mt-4 mb-2">Órdenes de Compra de Insumos</h3>
        <table>
            <thead><tr><th>Fecha</th><th>Proveedor</th><th>Total</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($ordenesCompra as $o): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($o['order_date'])) ?></td>
                    <td><?= htmlspecialchars($o['supplier']) ?></td>
                    <td>$<?= number_format($o['total_amount'],2) ?></td>
                    <td><?= $o['status'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- 7. Control de Alimentación -->
    <div class="card">
        <h2 class="text-xl font-semibold mb-4"><span class="emoji">🍽️</span> Alimentación</h2>
        <h3 class="font-medium mb-2">Registros de Alimentación</h3>
        <table>
            <thead><tr><th>Fecha</th><th>Animal</th><th>Alimento</th><th>Kg</th></tr></thead>
            <tbody>
                <?php foreach ($alimentacion as $a): ?>
                <tr><td><?= date('d/m/Y', strtotime($a['feeding_date'])) ?></td><td><?= htmlspecialchars($a['animal']) ?></td><td><?= htmlspecialchars($a['alimento']) ?></td><td><?= number_format($a['quantity_kg'],2) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="font-medium mt-4 mb-2">Catálogo de Alimentos</h3>
        <table>
            <thead><tr><th>Nombre</th><th>Tipo</th><th>Costo/kg</th><th>Proteína %</th><th>Stock kg</th></tr></thead>
            <tbody>
                <?php foreach ($catalogo as $alim): ?>
                <tr><td><?= htmlspecialchars($alim['name']) ?></td><td><?= ucfirst($alim['food_type']) ?></td><td>$<?= number_format($alim['cost_per_kg'],2) ?></td><td><?= $alim['protein_pct'] ?>%</td><td><?= number_format($alim['stock_kg'],2) ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3 class="font-medium mt-4 mb-2">Eficiencia Nutricional</h3>
        <table>
            <thead><tr><th>Fecha</th><th>Animal</th><th>Conversión</th><th>Ganancia kg</th></tr></thead>
            <tbody>
                <?php foreach ($eficiencia as $ef): ?>
                <tr><td><?= date('d/m/Y', strtotime($ef['measurement_date'])) ?></td><td><?= htmlspecialchars($ef['animal']) ?></td><td><?= $ef['feed_conversion_ratio'] ?></td><td><?= $ef['weight_gain_kg'] ?></td></tr>
                <?php endforeach; ?>
                <?php if (empty($eficiencia)): ?><tr><td colspan="4" class="text-center">Sin datos de eficiencia</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Botón Crear PDF -->
    <div class="flex justify-end mt-8 no-print">
        <button onclick="window.print()" class="btn-pdf flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Crear PDF
        </button>
    </div>

</div>
</body>
</html>