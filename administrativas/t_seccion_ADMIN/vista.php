administrativas/
<?php
if (!isset($data) || !is_array($data)) $data = [];
extract($data);
$pag = $pages[$tab] ?? 1;
$tabLabel = [
    'vacas'=>'Animales','leche'=>'Leche','sacrificios'=>'Sacrificios','huevos'=>'Huevos','gallinas'=>'Gallinas',
    'anuncios'=>'Anuncios','transacciones'=>'Transacciones','ordenes'=>'Órdenes','empleados'=>'Empleados/Nómina',
    'alimentacion'=>'Alimentación','catalogo'=>'Catálogo Alimentos','eficiencia'=>'Eficiencia Nutricional'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Panel de Administración · Ganadería</title>
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
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #1f4d38;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #b87c4f;
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background-color: #9a623b;
        }
        .table-ganado thead {
            background: #2d6a4f;
            color: #fef5e6;
        }
        .table-ganado tbody tr:hover {
            background-color: #fef1df;
        }
        .badge-ganado {
            background: #e9dfc7;
            color: #5a3e1b;
            border-radius: 2rem;
            padding: 0.2rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 500;
        }
        input, select, textarea {
            background-color: #fffef7;
            border: 1px solid #ecdbaa;
            border-radius: 0.75rem;
            transition: all 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #b87c4f;
            box-shadow: 0 0 0 3px rgba(184, 124, 79, 0.2);
        }
    </style>
</head>
<body>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
            <i class="fas fa-tools text-[#f7b32b]"></i> Panel de Administración
        </h1>
        <p class="text-[#e2d4b5] mt-1 text-sm">Gestión completa de animales, producción, finanzas y alimentación</p>
    </div>

    <?php if (isset($flash)): ?>
    <div class="mb-4 p-4 rounded-xl <?= $flash['type']==='success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' ?>">
        <?= htmlspecialchars($flash['message']) ?>
    </div>
    <?php endif; ?>

    <!-- PESTAÑAS -->
    <div class="flex flex-wrap gap-2 mb-6 pb-1 border-b border-[#ecdbaa]/40">
        <?php foreach ($tabLabel as $key => $label):
            $active = ($tab === $key);
            $emoji = ['vacas'=>'','leche'=>'','sacrificios'=>'','huevos'=>'','gallinas'=>'','anuncios'=>'','transacciones'=>'','ordenes'=>'','empleados'=>'','alimentacion'=>'','catalogo'=>'','eficiencia'=>''][$key]??'';
        ?>
        <a href="?tab=<?= $key ?>&page_<?= $key ?>=<?= $pages[$key] ?? 1 ?>"
           class="px-5 py-2 rounded-t-lg font-medium transition <?= $active ? 'tab-active' : 'tab-inactive' ?>">
           <?= $emoji ?> <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>

    <?php 
    $renderPaginacion = function($totalPaginas, $page, $tab) {
        if ($totalPaginas <= 1) return '';
        $html = '<div class="flex justify-center items-center space-x-2 mt-4">';
        if ($page > 1) $html .= "<a href='?tab=$tab&page_$tab=".($page-1)."' class='px-3 py-1 bg-[#d4a373] rounded hover:bg-[#b87c4f] text-white transition'>← Anterior</a>";
        $html .= "<span class='px-3 py-1 bg-[#2d6a4f] text-white rounded'>$page / $totalPaginas</span>";
        if ($page < $totalPaginas) $html .= "<a href='?tab=$tab&page_$tab=".($page+1)."' class='px-3 py-1 bg-[#d4a373] rounded hover:bg-[#b87c4f] text-white transition'>Siguiente →</a>";
        $html .= '</div>';
        return $html;
    };
    ?>

    <!-- ==================== PESTAÑA ANIMALES ==================== -->
    <?php if ($tab === 'vacas'): ?>
    <div class="card-ganadero p-6">
        <h2 class="text-xl font-bold text-[#4b2e1a] mb-4"><i class="fas fa-cow text-[#b87c4f]"></i> Gestión de Animales</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-7 gap-3 mb-6">
            <select name="gender" id="genderSelect" required class="border rounded-lg p-2 bg-[#fffef7]">
                <option value="">Género</option>
                <?php foreach ($generos as $v => $l): ?>
                    <option value="<?= $v ?>"><?= $l ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="tag" id="tagInput" placeholder="Código" required readonly class="border rounded-lg p-2 bg-gray-100">
            <select name="animal_type_id" required class="border rounded-lg p-2 bg-[#fffef7]">
                <option value="">Tipo Animal</option>
                <?php foreach ($tiposAnimales as $tipo): ?>
                    <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="breed_id" required class="border rounded-lg p-2 bg-[#fffef7]">
                <option value="">Raza</option>
                <?php foreach ($razas as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="name" placeholder="Nombre" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="weight" placeholder="Peso (kg)" step="0.01" required class="border rounded-lg p-2 bg-[#fffef7]">
            <select name="status" required class="border rounded-lg p-2 bg-[#fffef7]">
                <?php foreach ($estadosAnimal as $v => $l): ?>
                    <option value="<?= $v ?>"><?= $l ?></option>
                <?php endforeach; ?>
            </select>
            <button name="agregar_vaca" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
        </form>
        <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
            <table class="min-w-full divide-y divide-[#f0e5d2] table-ganado">
                <thead class="bg-[#2d6a4f] text-[#fef5e6]">
                    <tr><th class="px-3 py-2 text-left">ID</th><th class="px-3 py-2 text-left">Tag</th><th class="px-3 py-2 text-left">Nombre</th><th class="px-3 py-2 text-left">Raza</th><th class="px-3 py-2 text-left">Peso</th><th class="px-3 py-2 text-left">Estado</th><th class="px-3 py-2 text-left">Género</th><th class="px-3 py-2 text-left">Acciones</th></tr>
                </thead>
                <tbody class="divide-y divide-[#f0e5d2]">
                    <?php foreach ($registros as $a): ?>
                    <tr class="hover:bg-[#fef1df] transition">
                        <form method="POST" class="contents">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <td class="px-3 py-2 text-sm"><?= $a['id'] ?></td>
                            <td class="px-3 py-2"><input type="text" name="tag" value="<?= htmlspecialchars($a['tag']) ?>" class="border rounded px-2 py-1 w-20 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="text" name="name" value="<?= htmlspecialchars($a['name']) ?>" class="border rounded px-2 py-1 w-28 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><select name="breed_id" class="border rounded px-2 py-1 bg-[#fffef7]">
                                <?php foreach ($razas as $r): ?><option value="<?= $r['id'] ?>" <?= $r['name']===$a['breed']?'selected':'' ?>><?= htmlspecialchars($r['name']) ?></option><?php endforeach; ?>
                            </select></td>
                            <td class="px-3 py-2"><input type="number" name="weight" value="<?= $a['weight_kg'] ?>" class="border rounded px-2 py-1 w-20 bg-[#fffef7]" step="0.01"></td>
                            <td class="px-3 py-2"><select name="status" class="border rounded px-2 py-1 bg-[#fffef7]">
                                <?php foreach ($estadosAnimal as $v=>$l): ?><option value="<?= $v ?>" <?= $a['status']===$v?'selected':'' ?>><?= $l ?></option><?php endforeach; ?>
                            </select></td>
                            <td class="px-3 py-2"><select name="gender" class="border rounded px-2 py-1 bg-[#fffef7]">
                                <?php foreach ($generos as $v=>$l): ?><option value="<?= $v ?>" <?= $a['gender']===$v?'selected':'' ?>><?= $l ?></option><?php endforeach; ?>
                            </select></td>
                            <td class="px-3 py-2 whitespace-nowrap"><div class="flex gap-1">
                                <button type="submit" name="actualizar_vaca" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-2 py-1 rounded text-xs transition">✏️ Actualizar</button>
                                <a href="?tab=vacas&eliminar_vaca=1&id=<?= $a['id'] ?>&page_vacas=<?= $pag ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
                            </div></td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= $renderPaginacion($totalPaginas, $pag, 'vacas') ?>
    </div>
    <?php endif; ?>

    <!-- ==================== OTRAS PESTAÑAS (leche, huevos, etc.) ==================== -->
    <?php if (in_array($tab, ['leche','huevos','gallinas','anuncios','transacciones','ordenes','alimentacion','eficiencia'])): ?>
        <?php
        $cols = [
            'leche'=>[['Animal','animal_name'],['Fecha','production_date'],['Litros','quantity_liters']],
            'huevos'=>[['Fecha','production_date'],['Cantidad','quantity']],
            'gallinas'=>[['Fecha','inventory_date'],['Cantidad','quantity']],
            'anuncios'=>[['Tipo','ad_type'],['Tipo Animal','animal_type'],['Raza','breed'],['Cant.','quantity'],['Peso','weight_kg'],['Precio/u','price_per_unit'],['Usuario','username']],
            'transacciones'=>[['Fecha','transaction_date'],['Vendedor','seller'],['Comprador','buyer'],['Animal','animal_type'],['Cant.','quantity'],['Monto','total_amount']],
            'ordenes'=>[['Fecha','order_date'],['Proveedor','supplier'],['Entrega','expected_delivery'],['Total','total_amount'],['Estado','status']],
            'alimentacion'=>[['Fecha','feeding_date'],['Animal','animal_name'],['Alimento','food_name'],['Kg','quantity_kg']],
            'eficiencia'=>[['Fecha','measurement_date'],['Animal','animal_name'],['Conversión','feed_conversion_ratio'],['Ganancia kg','weight_gain_kg']],
        ][$tab]??[];
        $hasForm = in_array($tab, ['leche','huevos','gallinas','ordenes','alimentacion']);
        ?>
    <div class="card-ganadero p-6">
        <h2 class="text-xl font-bold text-[#4b2e1a] mb-4"><?= $tabLabel[$tab] ?></h2>
        <?php if ($hasForm): ?>
        <form method="POST" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
            <?php if ($tab==='leche'): ?>
                <select name="animal_id" required class="border rounded-lg p-2 bg-[#fffef7]">
                    <option value="">Animal</option>
                    <?php foreach ($animalesSelect as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?>
                </select>
                <input type="date" name="production_date" required class="border rounded-lg p-2 bg-[#fffef7]">
                <input type="number" name="quantity_liters" step="0.01" placeholder="Litros" required class="border rounded-lg p-2 bg-[#fffef7]">
                <button name="agregar_leche" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
            <?php elseif ($tab==='huevos'): ?>
                <input type="date" name="production_date" required class="border rounded-lg p-2 bg-[#fffef7]">
                <input type="number" name="quantity" placeholder="Cantidad" required class="border rounded-lg p-2 bg-[#fffef7]">
                <button name="agregar_huevos" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
            <?php elseif ($tab==='gallinas'): ?>
                <input type="date" name="inventory_date" required class="border rounded-lg p-2 bg-[#fffef7]">
                <input type="number" name="quantity" placeholder="Cantidad" required class="border rounded-lg p-2 bg-[#fffef7]">
                <button name="agregar_gallinas" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
            <?php elseif ($tab==='ordenes'): ?>
                <select name="supplier_id" required class="border rounded-lg p-2 bg-[#fffef7]">
                    <option value="">Proveedor</option>
                    <?php foreach ($proveedores as $p): ?><option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option><?php endforeach; ?>
                </select>
                <input type="date" name="order_date" required class="border rounded-lg p-2 bg-[#fffef7]">
                <input type="date" name="expected_delivery" class="border rounded-lg p-2 bg-[#fffef7]">
                <input type="number" name="total_amount" step="0.01" placeholder="Total $" required class="border rounded-lg p-2 bg-[#fffef7]">
                <button name="agregar_orden" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
            <?php elseif ($tab==='alimentacion'): ?>
                <select name="animal_id" required class="border rounded-lg p-2 bg-[#fffef7]">
                    <option value="">Animal</option>
                    <?php foreach ($animalesSelect as $a): ?><option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['name']) ?></option><?php endforeach; ?>
                </select>
                <select name="food_id" required class="border rounded-lg p-2 bg-[#fffef7]">
                    <option value="">Alimento</option>
                    <?php foreach ($alimentosSelect as $al): ?><option value="<?= $al['id'] ?>"><?= htmlspecialchars($al['name']) ?></option><?php endforeach; ?>
                </select>
                <input type="date" name="feeding_date" required class="border rounded-lg p-2 bg-[#fffef7]">
                <input type="number" name="quantity_kg" step="0.01" placeholder="Kg" required class="border rounded-lg p-2 bg-[#fffef7]">
                <button name="agregar_alimentacion" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
            <?php endif; ?>
        </form>
        <?php endif; ?>
        <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
            <table class="min-w-full divide-y divide-[#f0e5d2] table-ganado">
                <thead class="bg-[#2d6a4f] text-[#fef5e6]">
                    <tr><?php foreach ($cols as $c): ?><th class="px-3 py-2 text-left"><?= $c[0] ?></th><?php endforeach; ?><th class="px-3 py-2 text-left">Acciones</th></tr>
                </thead>
                <tbody class="divide-y divide-[#f0e5d2]">
                    <?php foreach ($registros as $r): ?>
                    <tr class="hover:bg-[#fef1df] transition">
                        <?php if ($hasForm): ?>
                        <form method="POST" class="contents">
                            <input type="hidden" name="id" value="<?= $r['id'] ?? '' ?>">
                            <?php foreach ($cols as $c): $field = $c[1]; ?>
                                <td class="px-3 py-2"><input type="text" name="<?= $field ?>" value="<?= htmlspecialchars($r[$field] ?? '') ?>" class="border rounded px-2 py-1 w-24 bg-[#fffef7]"></td>
                            <?php endforeach; ?>
                            <td class="px-3 py-2 whitespace-nowrap"><div class="flex gap-1">
                                <button type="submit" name="actualizar_<?= $tab ?>" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-2 py-1 rounded text-xs transition">✏️</button>
                                <a href="?tab=<?= $tab ?>&eliminar_<?= $tab ?>=1&id=<?= $r['id'] ?>&page_<?= $tab ?>=<?= $pag ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️</a>
                            </div></td>
                        </form>
                        <?php else: ?>
                            <?php foreach ($cols as $c): ?><td class="px-3 py-2"><?= htmlspecialchars($r[$c[1]] ?? '') ?></td><?php endforeach; ?>
                            <td class="px-3 py-2 whitespace-nowrap">
                                <a href="?tab=<?= $tab ?>&eliminar_<?= $tab ?>=1&id=<?= $r['id'] ?>&page_<?= $tab ?>=<?= $pag ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️ Eliminar</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?= $renderPaginacion($totalPaginas??1, $pag, $tab) ?>
    </div>
    <?php endif; ?>

    <!-- ==================== SACRIFICIOS (pestaña especial) ==================== -->
    <?php if ($tab === 'sacrificios'): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card-ganadero p-5">
            <h2 class="text-lg font-bold text-[#4b2e1a] mb-3 flex items-center gap-2"><i class="fas fa-skull text-red-600"></i> Animales sacrificados</h2>
            <?php if (empty($disponibles)): ?>
                <p class="text-gray-500 italic">No hay animales marcados como sacrificados</p>
            <?php else: ?>
                <ul class="divide-y divide-[#f0e5d2]">
                    <?php foreach ($disponibles as $a): ?>
                    <li class="py-2 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-[#4b2e1a]"><?= htmlspecialchars($a['name']) ?> <span class="text-xs text-gray-500">(<?= htmlspecialchars($a['tag']) ?>)</span></p>
                            <p class="text-xs text-gray-500"><?= $a['breed'] ?> | <?= $a['weight_kg'] ?> kg</p>
                        </div>
                        <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Sacrificado</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <div class="card-ganadero p-5">
            <h2 class="text-lg font-bold text-[#4b2e1a] mb-3 flex items-center gap-2"><i class="fas fa-plus-circle text-green-700"></i> Registrar nuevo sacrificio</h2>
            <form method="POST" id="formSacrificio" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de animal</label>
                    <select name="animal_type_id" id="tipoAnimal" required class="border rounded-lg p-2 w-full bg-[#fffef7]">
                        <option value="">Seleccionar tipo</option>
                        <?php foreach ($tiposAnimal as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Animal</label>
                    <select name="animal_id" id="animalSelect" required class="border rounded-lg p-2 w-full bg-[#fffef7]">
                        <option value="">Primero seleccione un tipo</option>
                    </select>
                </div>
                <button name="registrar_sacrificio" class="bg-red-700 hover:bg-red-800 text-white py-2 px-4 rounded-lg w-full transition">
                    <i class="fas fa-gavel mr-2"></i> Registrar sacrificio
                </button>
            </form>
        </div>
    </div>
    <div class="card-ganadero p-5 mt-6">
        <h2 class="text-lg font-bold text-[#4b2e1a] mb-3">Historial de sacrificios</h2>
        <?php foreach ($historial as $s): ?>
            <div class="p-2 border-b border-[#f0e5d2]">
                <p class="text-sm font-medium"><?= htmlspecialchars($s['slaughter_date']) ?></p>
                <p class="text-xs text-gray-500"><?= htmlspecialchars($s['animal_type']) ?> - <?= $s['quantity'] ?> ud.</p>
            </div>
        <?php endforeach; ?>
        <?php if (empty($historial)): ?>
            <p class="text-gray-500 italic">No hay registros aún</p>
        <?php endif; ?>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const animales = <?= json_encode(array_map(function($a) {
                return ['id' => $a['id'], 'name' => $a['name'], 'tipo_id' => $a['animal_type_id'] ?? null];
            }, $animalesSelect)) ?>;
            const tipoSelect = document.getElementById('tipoAnimal');
            const animalSelect = document.getElementById('animalSelect');
            function filtrarAnimales() {
                const tipoId = tipoSelect.value;
                animalSelect.innerHTML = '<option value="">Seleccionar animal</option>';
                if (!tipoId) return;
                const filtrados = animales.filter(a => a.tipo_id == tipoId);
                if (filtrados.length === 0) {
                    animalSelect.innerHTML += '<option disabled>No hay animales disponibles de este tipo</option>';
                    return;
                }
                filtrados.forEach(animal => {
                    const option = document.createElement('option');
                    option.value = animal.id;
                    option.textContent = animal.name;
                    animalSelect.appendChild(option);
                });
            }
            tipoSelect.addEventListener('change', filtrarAnimales);
        });
    </script>
    <?php endif; ?>

    <!-- ==================== EMPLEADOS ==================== -->
    <?php if ($tab === 'empleados'): ?>
    <div class="card-ganadero p-6">
        <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Empleados</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
            <input type="text" name="name" placeholder="Nombre" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="text" name="role" placeholder="Cargo" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="monthly_salary" step="0.01" placeholder="Salario" required class="border rounded-lg p-2 bg-[#fffef7]">
            <button name="agregar_empleado" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
        </form>
        <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
            <table class="min-w-full divide-y divide-[#f0e5d2] table-ganado">
                <thead class="bg-[#2d6a4f] text-[#fef5e6]"><tr><th class="px-3 py-2 text-left">Nombre</th><th class="px-3 py-2 text-left">Cargo</th><th class="px-3 py-2 text-left">Salario</th><th class="px-3 py-2 text-left">Acciones</th></tr></thead>
                <tbody class="divide-y divide-[#f0e5d2]">
                    <?php foreach ($empleados as $e): ?>
                    <tr class="hover:bg-[#fef1df] transition">
                        <form method="POST" class="contents"><input type="hidden" name="id" value="<?= $e['id'] ?>">
                            <td class="px-3 py-2"><input type="text" name="name" value="<?= htmlspecialchars($e['name']) ?>" class="border rounded px-2 py-1 w-28 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="text" name="role" value="<?= htmlspecialchars($e['role']) ?>" class="border rounded px-2 py-1 w-28 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="number" name="monthly_salary" step="0.01" value="<?= $e['monthly_salary'] ?>" class="border rounded px-2 py-1 w-24 bg-[#fffef7]"></td>
                            <td class="px-3 py-2 whitespace-nowrap"><div class="flex gap-1">
                                <button type="submit" name="actualizar_empleado" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-2 py-1 rounded text-xs transition">✏️</button>
                                <a href="?tab=empleados&eliminar_empleado=1&id=<?= $e['id'] ?>&page_empleados=<?= $pag ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️</a>
                            </div></td>
                        </form>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h2 class="text-xl font-bold text-[#4b2e1a] mt-8 mb-4">Última Nómina</h2>
        <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
            <table class="min-w-full divide-y divide-[#f0e5d2] table-ganado">
                <thead class="bg-[#2d6a4f] text-[#fef5e6]"><tr><th class="px-3 py-2 text-left">Empleado</th><th class="px-3 py-2 text-left">Cargo</th><th class="px-3 py-2 text-left">Bruto</th><th class="px-3 py-2 text-left">Deducc.</th><th class="px-3 py-2 text-left">Neto</th><th class="px-3 py-2 text-left">Fecha Pago</th></tr></thead>
                <tbody class="divide-y divide-[#f0e5d2]">
                    <?php foreach ($nomina as $n): ?>
                    <tr class="hover:bg-[#fef1df] transition">
                        <td class="px-3 py-2"><?= htmlspecialchars($n['name']) ?></td>
                        <td class="px-3 py-2"><?= htmlspecialchars($n['role']) ?></td>
                        <td class="px-3 py-2">$<?= number_format($n['gross_salary'],2) ?></td>
                        <td class="px-3 py-2">$<?= number_format($n['deductions'],2) ?></td>
                        <td class="px-3 py-2 font-semibold text-[#2d6a4f]">$<?= number_format($n['net_pay'],2) ?></td>
                        <td class="px-3 py-2"><?= date('d/m/Y', strtotime($n['payment_date'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- ==================== CATÁLOGO DE ALIMENTOS ==================== -->
    <?php if ($tab === 'catalogo'): ?>
    <div class="card-ganadero p-6">
        <h2 class="text-xl font-bold text-[#4b2e1a] mb-4">Catálogo de Alimentos</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-6">
            <input type="text" name="name" placeholder="Nombre" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="text" name="food_type" placeholder="Tipo" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="cost_per_kg" step="0.01" placeholder="Costo/kg" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="protein_pct" step="0.01" placeholder="Proteína %" required class="border rounded-lg p-2 bg-[#fffef7]">
            <input type="number" name="stock_kg" step="0.01" placeholder="Stock kg" required class="border rounded-lg p-2 bg-[#fffef7]">
            <button name="agregar_alimento" class="btn-primary text-white py-2 px-4 rounded-lg transition">➕ Agregar</button>
        </form>
        <div class="overflow-x-auto rounded-xl border border-[#e2d4b5]">
            <table class="min-w-full divide-y divide-[#f0e5d2] table-ganado">
                <thead class="bg-[#2d6a4f] text-[#fef5e6]"><tr><th>Nombre</th><th>Tipo</th><th>Costo/kg</th><th>Proteína%</th><th>Stock</th><th>Acciones</th></tr></thead>
                <tbody class="divide-y divide-[#f0e5d2]">
                    <?php foreach ($registros as $al): ?>
                    <tr class="hover:bg-[#fef1df] transition">
                        <form method="POST" class="contents"><input type="hidden" name="id" value="<?= $al['id'] ?>">
                            <td class="px-3 py-2"><input type="text" name="name" value="<?= htmlspecialchars($al['name']) ?>" class="border rounded px-2 py-1 w-32 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="text" name="food_type" value="<?= htmlspecialchars($al['food_type']) ?>" class="border rounded px-2 py-1 w-20 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="number" name="cost_per_kg" step="0.01" value="<?= $al['cost_per_kg'] ?>" class="border rounded px-2 py-1 w-20 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="number" name="protein_pct" step="0.01" value="<?= $al['protein_pct'] ?>" class="border rounded px-2 py-1 w-16 bg-[#fffef7]"></td>
                            <td class="px-3 py-2"><input type="number" name="stock_kg" step="0.01" value="<?= $al['stock_kg'] ?>" class="border rounded px-2 py-1 w-20 bg-[#fffef7]"></td>
                            <td class="px-3 py-2 whitespace-nowrap"><div class="flex gap-1">
                                <button type="submit" name="actualizar_alimento" class="bg-[#b87c4f] hover:bg-[#9a623b] text-white px-2 py-1 rounded text-xs transition">✏️</button>
                                <a href="?tab=catalogo&eliminar_alimento=1&id=<?= $al['id'] ?>&page_catalogo=<?= $pag ?>" onclick="return confirm('¿Eliminar?')" class="bg-red-700 hover:bg-red-800 text-white px-2 py-1 rounded text-xs inline-block">🗑️</a>
                            </div></td>
                        </form>
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
/**
 * archivo dentro de la carpeta de 
 * 
 * administrativas/t_seccion_ADMIN
 */