<?php
/**
 * @var array $animales
 * @var int $animalId
 * @var array|null $animalSeleccionado
 * @var array $padres
 * @var array $crias
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genealogía | Hato</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body {
            background: #1a4d2a;
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                              repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-size: 30px 30px, 12px 12px;
            background-attachment: fixed;
            padding: 1.5rem;
        }
        .glass-card {
            background: rgba(255, 251, 240, 0.97);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            border: 1px solid #e2d4b5;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.25);
        }
        .tree-card {
            transition: all 0.2s ease;
        }
        .tree-card:hover {
            transform: scale(1.02);
        }
        .family-connection {
            position: relative;
        }
        .family-connection::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 50%;
            width: 2px;
            height: 20px;
            background: #b87c4f;
        }
        select:focus {
            outline: none;
            ring: 2px solid #b87c4f;
            border-color: #b87c4f;
        }
    </style>
</head>
<body>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-[#f9eec1] flex items-center gap-3">
                <i class="fas fa-tree text-[#f7b32b]"></i> Árbol Genealógico
            </h1>
            <p class="text-[#e2d4b5] mt-1 text-sm">Explora la ascendencia y descendencia de tus animales</p>
        </div>
        <div class="flex items-center gap-3 glass-card px-5 py-2 shadow-sm">
            <i class="fas fa-calendar-alt text-[#b87c4f]"></i>
            <span class="text-sm font-semibold text-[#5a3e1b]"><?= date('d/m/Y') ?></span>
        </div>
    </div>

    <!-- Selector de animal -->
    <div class="glass-card p-6 mb-8">
        <form method="GET" action="" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-[#5a3e1b] mb-1">Seleccionar animal</label>
                <select name="id" class="w-full border border-[#ecdbaa] rounded-xl px-4 py-3 bg-[#fffef7] focus:ring-2 focus:ring-[#b87c4f]">
                    <option value="">-- Elegir un animal --</option>
                    <?php foreach ($animales as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= ($a['id'] == $animalId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($a['name']) ?> (<?= htmlspecialchars($a['tag']) ?>)
                            - <?= htmlspecialchars($a['tipo_nombre'] ?? 'Sin tipo') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-gradient-to-r from-[#2d6a4f] to-[#1f4d38] hover:from-[#1f4d38] hover:to-[#2d6a4f] text-white font-semibold px-6 py-3 rounded-xl transition shadow-md flex items-center gap-2">
                <i class="fas fa-search"></i> Ver árbol
            </button>
        </form>
    </div>

    <?php if ($animalSeleccionado): ?>
        <!-- Animal central -->
        <div class="glass-card p-6 mb-8 text-center">
            <h2 class="text-2xl font-bold text-[#4b2e1a]">
                <?= obtenerIconoSexo($animalSeleccionado['gender']) ?>
                <?= htmlspecialchars($animalSeleccionado['name']) ?>
                <span class="text-sm font-normal text-gray-500">(<?= htmlspecialchars($animalSeleccionado['tag']) ?>)</span>
            </h2>
            <p class="text-sm text-gray-600">
                <?= htmlspecialchars($animalSeleccionado['tipo_nombre'] ?? 'Sin tipo') ?>
                <?php if ($animalSeleccionado['raza_nombre']): ?>
                    · <?= htmlspecialchars($animalSeleccionado['raza_nombre']) ?>
                <?php endif; ?>
                · Edad: <?= calcularEdad($animalSeleccionado['birth_date'] ?? null) ?>
            </p>
        </div>

        <!-- Padres -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-[#5a3e1b] mb-4 flex items-center gap-2">
                    <i class="fas fa-female text-pink-500"></i> Madre
                </h3>
                <?php if ($padres['madre']): ?>
                    <div class="tree-card p-4 border-l-4 border-pink-400 rounded-lg <?= obtenerColorSexo('F') ?>">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-[#4b2e1a]"><?= htmlspecialchars($padres['madre']['name']) ?></div>
                                <div class="text-xs text-gray-500">Arete: <?= htmlspecialchars($padres['madre']['tag']) ?></div>
                            </div>
                            <a href="?id=<?= $padres['madre']['id'] ?>" class="text-[#b87c4f] hover:text-[#9a623b] text-sm">
                                <i class="fas fa-arrow-right"></i> Ver ficha
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 text-sm">Sin registro de madre</p>
                <?php endif; ?>
            </div>

            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-[#5a3e1b] mb-4 flex items-center gap-2">
                    <i class="fas fa-mars text-blue-500"></i> Padre
                </h3>
                <?php if ($padres['padre']): ?>
                    <div class="tree-card p-4 border-l-4 border-blue-400 rounded-lg <?= obtenerColorSexo('M') ?>">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-semibold text-[#4b2e1a]"><?= htmlspecialchars($padres['padre']['name']) ?></div>
                                <div class="text-xs text-gray-500">Arete: <?= htmlspecialchars($padres['padre']['tag']) ?></div>
                            </div>
                            <a href="?id=<?= $padres['padre']['id'] ?>" class="text-[#b87c4f] hover:text-[#9a623b] text-sm">
                                <i class="fas fa-arrow-right"></i> Ver ficha
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-400 text-sm">Sin registro de padre</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Crías -->
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold text-[#5a3e1b] mb-4 flex items-center gap-2">
                <i class="fas fa-baby-carriage text-amber-500"></i> Crías (<?= count($crias) ?>)
            </h3>
            <?php if (empty($crias)): ?>
                <p class="text-gray-400 text-sm">Este animal no tiene crías registradas</p>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($crias as $cria): ?>
                        <div class="tree-card p-4 border rounded-lg <?= obtenerClaseSexo($cria['gender']) ?> <?= obtenerColorSexo($cria['gender']) ?> shadow-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-[#4b2e1a]">
                                        <?= obtenerIconoSexo($cria['gender']) ?>
                                        <?= htmlspecialchars($cria['name']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">Arete: <?= htmlspecialchars($cria['tag']) ?></div>
                                    <div class="text-xs text-gray-400">Nac: <?= htmlspecialchars($cria['birth_date'] ?? 'N/D') ?></div>
                                </div>
                                <a href="?id=<?= $cria['id'] ?>" class="text-[#b87c4f] hover:text-[#9a623b] text-sm">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif ($animalId > 0): ?>
        <!-- Animal no encontrado -->
        <div class="glass-card p-6 text-center text-red-600">
            <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
            <p>Animal no encontrado. Selecciona otro de la lista.</p>
        </div>
    <?php else: ?>
        <!-- Mensaje inicial -->
        <div class="glass-card p-6 text-center text-[#8b6946]">
            <i class="fas fa-tree text-5xl mb-4 opacity-30"></i>
            <p class="text-lg font-medium">Selecciona un animal para ver su árbol genealógico</p>
            <p class="text-sm mt-2">Podrás ver sus padres y todas sus crías registradas</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>