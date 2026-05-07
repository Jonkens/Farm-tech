<?php
/**
 * Punto de entrada del Sistema de Producción Ganadera.
 * Carga dinámica de módulos vía iframe mediante pestañas.
 *
 * Estructura de carpetas:
 *   principales/  -> módulos terminados (producción diaria, comparativas)
 *   secundarias/  -> módulos en desarrollo (estimación, hato, compra/venta, alimentación, reportes)
 *   administrativas/ -> administración, pruebas
 *
 * Cada módulo reside en su propia subcarpeta con un index.php (ej. principales/seccion_dias/index.php)
 */

// ========== CONFIGURACIÓN DE PESTAÑAS ==========
$tabsConfig = [
    'principales' => [
        'seccion_dias'     => 'comparativa diaria',
        'seccion_semanas'  => 'Comparativa semanal',
        'seccion_meses'    => 'Comparativa mensual',
    ],
    'secundarias' => [
        'estimacion_carne'     => 'Estimación de carne',
        'distribucion_de_hato' => 'Distribución de hato',
        'compra_venta'         => 'Compra y venta',
        'control_alimentacion' => 'Control de alimentación',
        'reportes'             => 'Reportes',
    ],
    'administrativas' => [
        't_seccion_ADMIN' => 'Administración de datos',
        // 'prueba'       => 'Pruebas / Reportes', // descomentar cuando se necesite
    ],
];

// Construir el mapa completo vista => ruta
$viewsMap = [];
foreach ($tabsConfig as $carpeta => $tabs) {
    foreach ($tabs as $viewId => $label) {
        // Todas las rutas ahora apuntan a subcarpeta/index.php
        $viewsMap[$viewId] = $carpeta . '/' . $viewId . '/index.php';
    }
}

// Pestaña activa por defecto
$defaultTab = 'seccion_dias';
$activeTab = $defaultTab;
if (isset($_GET['tab']) && array_key_exists($_GET['tab'], $viewsMap)) {
    $activeTab = $_GET['tab'];
}
$defaultSrc = $viewsMap[$activeTab];
?>
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de producción ganadera">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sistema de Producción Ganadera</title>
    <style>
        .tab-active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        .tab-inactive {
            background-color: #f3f4f6;
            color: #4b5563;
            border-color: #e5e7eb;
        }
        .tab-inactive:hover {
            background-color: #e5e7eb;
        }
    </style>
</head>
<body class="h-full bg-gray-100 font-sans flex flex-col">
    <!-- Barra superior fija -->
    <header class="bg-gradient-to-r from-blue-700 to-blue-900 text-white px-6 py-4 shadow-md flex-shrink-0">
        <h1 class="text-2xl font-bold" role="banner"> Sistema de Producción Ganadera</h1>
    </header>

    <!-- Pestañas de navegación -->
    <nav class="bg-white border-b border-gray-200 px-4 pt-2 flex flex-wrap gap-2 flex-shrink-0" aria-label="Navegación principal">
        <?php foreach ($tabsConfig as $carpeta => $tabs): ?>
            <?php foreach ($tabs as $viewId => $label): ?>
                <button 
                    data-view="<?= htmlspecialchars($viewId) ?>" 
                    class="tab-btn px-5 py-2 rounded-t-lg font-medium transition-colors focus:outline-none <?= $viewId === $activeTab ? 'tab-active' : 'tab-inactive' ?>"
                    aria-selected="<?= $viewId === $activeTab ? 'true' : 'false' ?>"
                    role="tab"
                >
                    <?= htmlspecialchars($label) ?>
                </button>
            <?php endforeach; ?>
            <?php if ($carpeta !== array_key_last($tabsConfig)): ?>
                <!-- separador visual opcional -->
                <span class="border-r border-gray-300 mx-1" aria-hidden="true"></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <!-- Contenedor del iframe -->
    <main class="flex-1 p-4 bg-gray-100 min-h-0">
        <iframe 
            id="content-frame" 
            src="<?= htmlspecialchars($defaultSrc) ?>" 
            title="Contenido de la sección"
            class="w-full h-full rounded-lg border border-gray-200 shadow-inner bg-white"
        ></iframe>
    </main>

    <!-- Pie de página -->
    <footer class="text-center text-gray-500 text-xs py-2 flex-shrink-0">
        Conexión a PostgreSQL activa | Sistema en tiempo real
    </footer>

    <script>
        /**
         * Navegación por pestañas con carga en iframe.
         * La configuración de vistas se inyecta desde PHP para mantener coherencia.
         */
        const views = <?= json_encode($viewsMap) ?>;
        const iframe = document.getElementById('content-frame');
        const buttons = document.querySelectorAll('.tab-btn');

        function switchTab(viewId) {
            // Actualizar estilos de pestañas
            buttons.forEach(btn => {
                const isActive = btn.getAttribute('data-view') === viewId;
                btn.classList.toggle('tab-active', isActive);
                btn.classList.toggle('tab-inactive', !isActive);
                btn.setAttribute('aria-selected', isActive.toString());
            });

            // Cargar la vista correspondiente
            const file = views[viewId];
            if (file) {
                iframe.src = file;
                // Actualizar hash sin recargar la página
                history.replaceState(null, '', '#' + viewId);
            }
        }

        // Asignar eventos de clic
        buttons.forEach(btn => {
            btn.addEventListener('click', () => {
                const view = btn.getAttribute('data-view');
                if (view && views[view]) switchTab(view);
            });
        });

        // Al cargar, si hay hash en la URL, activar esa pestaña
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1);
            if (hash && views[hash]) {
                switchTab(hash);
            }
        });

        // Sincronizar si el usuario navega con los botones del navegador (hashchange)
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (hash && views[hash]) {
                switchTab(hash);
            }
        });
    </script>
</body>
</html>