<?php

/**
 * Punto de entrada del Sistema de Producción Ganadera.
 * Organización profesional por dominios (producción, salud, finanzas, etc.)
 * Menús desplegables (dropdown) superpuestos, sin desplazar el contenido.
 */

// ========== CONFIGURACIÓN DE PESTAÑAS (AGRUPADAS POR CATEGORÍA) ==========
$tabsConfig = [
    'inicio' => [
        'inicio/bienvenida' => 'Bienvenida',
        'inicio/nosotros'   => 'Nosotros',
    ],
    'produccion' => [
        'produccion/diaria'         => 'Producción diaria',
        'produccion/semanal'        => 'Producción semanal',
        'produccion/mensual'        => 'Producción mensual',
        'produccion/estimacion_carne' => 'Estimación de carne',
    ],
    'salud_y_alimentacion' => [
        'salud/animales'          => 'Salud del ganado',
        'salud/alimentacion'      => 'Control de alimentación',
        'salud/distribucion_hato' => 'Distribución de hato',
    ],
    'finanzas_y_comercio' => [
        'finanzas/compra_venta' => 'Compra y venta',
        'finanzas/Generar_pdf'     => 'Reportes medicos',
    ],
    'herramientas' => [
        'herramientas/busqueda 2' => 'Búsqueda avanzada',
        'herramientas/tareas3.0'  => 'Tareas programadas',   // usa .html
        'herramientas/mensajes'     => 'mensajes recibidos',

        'herramientas/partos'     => 'registros de partos',
    ],
    'administrativas' => [
        'administrativas/t_seccion_ADMIN' => 'Administración de datos',
    ],
];

// ========== CONSTRUCCIÓN DEL MAPA VISTA -> RUTA ==========
$viewsMap = [];
foreach ($tabsConfig as $carpeta => $tabs) {
    foreach ($tabs as $viewId => $label) {
        // Por defecto asumimos que cada vista tiene su propio index.php dentro de su carpeta
        // Ejemplo: 'produccion/diaria' -> 'produccion/diaria/index.php'
        $viewsMap[$viewId] = $viewId . '/index.php';
    }
}

// Excepciones (archivos que no son index.php o usan extensión diferente)
$viewsMap['herramientas/tareas3.0'] = 'herramientas/planificacion/tareas3.0/index.html';

// Vistas especiales para los botones del header (no aparecen en los menús desplegables)
$viewsMap['contacto']        = 'contacto/index.php';
$viewsMap['auth/login']      = 'auth/login/index.php';
$viewsMap['auth/recuperar']  = 'auth/recuperar/index.php';

// ========== PESTAÑA ACTIVA POR DEFECTO ==========
$defaultTab = 'inicio/bienvenida';
$activeTab = $defaultTab;
if (isset($_GET['tab']) && array_key_exists($_GET['tab'], $viewsMap)) {
    $activeTab = $_GET['tab'];
}
$defaultSrc = $viewsMap[$activeTab];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de gestión de producción ganadera">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Sistema de Producción Ganadera</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            height: 100vh;
            overflow: hidden;
        }

        /* Botones de categoría (dropdown trigger) */
        .dropdown-btn {
            transition: all 0.2s ease;
            border-radius: 0.5rem;
            font-weight: 600;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            color: #1f2937;
            padding: 0.5rem 1.25rem;
            cursor: pointer;
        }
        .dropdown-btn:hover {
            background-color: #eef2ff;
            border-color: #c7d2fe;
            transform: translateY(-1px);
        }
        .dropdown-btn i {
            transition: transform 0.2s;
            margin-left: 0.5rem;
            font-size: 0.75rem;
        }

        /* Contenedor del dropdown (relative) */
        .dropdown-container {
            position: relative;
        }

        /* Menú desplegable absoluto */
        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.02);
            min-width: 200px;
            max-width: 240px;
            z-index: 50;
            display: none;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        .dropdown-menu.open {
            display: block;
        }

        /* Lista de pestañas dentro del menú */
        .tabs-list {
            padding: 0.5rem;
        }
        .tab-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.5rem 0.75rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            background-color: transparent;
            color: #4b5563;
            cursor: pointer;
            white-space: normal;
            word-break: break-word;
        }
        .tab-btn:hover {
            background-color: #f3f4f6;
            transform: translateX(4px);
        }
        .tab-active {
            background-color: #2563eb;
            color: white;
        }
        .tab-active:hover {
            background-color: #1d4ed8;
        }

        /* Header */
        .header-btn {
            transition: all 0.2s;
        }
        .header-btn:hover {
            transform: translateY(-2px);
        }

        /* Contenido principal: iframe ocupa todo el espacio restante */
        .flex-grow {
            flex-grow: 1;
        }
        iframe {
            background: white;
        }
    </style>
</head>
<body class="h-screen flex flex-col">
    <!-- Header con botones de contacto/login -->
    <header class="bg-gradient-to-r from-blue-800 to-blue-900 text-white shadow-lg flex-shrink-0">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class="fas fa-tractor text-2xl text-green-300"></i>
                <h1 class="text-2xl font-bold tracking-tight">Sistema de Producción Ganadera</h1>
            </div>
            <div class="flex gap-3">
                <button id="headerContactBtn" class="header-btn flex items-center gap-2 bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-envelope"></i> Contáctanos
                </button>
                <button id="headerLoginBtn" class="header-btn flex items-center gap-2 bg-green-600 hover:bg-green-700 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                </button>
            </div>
        </div>
    </header>

    <!-- Barra de navegación con dropdowns -->
    <nav class="bg-white border-b border-gray-200 px-6 py-3 shadow-sm flex-shrink-0">
        <div class="container mx-auto flex flex-wrap gap-4">
            <?php 
            // Etiquetas legibles para cada categoría (sin emojis)
            $categoryLabels = [
                'inicio'                => 'Inicio',
                'produccion'            => 'Producción',
                'salud_y_alimentacion'  => 'Salud y Alimentación',
                'finanzas_y_comercio'   => 'Finanzas y Comercio',
                'herramientas'          => 'Herramientas',
                'administrativas'       => 'Administración'
            ];
            foreach ($tabsConfig as $key => $tabs):
                if (empty($tabs)) continue;
                $label = $categoryLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
            ?>
                <div class="dropdown-container" data-category="<?= $key ?>">
                    <button class="dropdown-btn">
                        <?= htmlspecialchars($label) ?> <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="tabs-list">
                            <?php foreach ($tabs as $viewId => $tabLabel): ?>
                                <button 
                                    data-view="<?= htmlspecialchars($viewId) ?>"
                                    class="tab-btn <?= $viewId === $activeTab ? 'tab-active' : '' ?>"
                                    aria-selected="<?= $viewId === $activeTab ? 'true' : 'false' ?>">
                                    <?= htmlspecialchars($tabLabel) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </nav>

    <!-- Contenido principal: iframe -->
    <main class="flex-1 flex bg-gray-100 overflow-hidden">
        <iframe
            id="content-frame"
            src="<?= htmlspecialchars($defaultSrc) ?>"
            title="Contenido de la sección"
            class="w-full h-full border-0">
        </iframe>
    </main>

    <!-- Footer fijo al fondo -->
    <footer class="text-center text-gray-500 text-xs py-3 border-t border-gray-200 bg-white flex-shrink-0">
        <p>Conexión a PostgreSQL activa | Sistema en tiempo real | © <?= date('Y') ?> Producción Ganadera</p>
    </footer>

    <script>
        // Mapa de rutas (inyectado desde PHP)
        const views = <?= json_encode($viewsMap) ?>;
        const iframe = document.getElementById('content-frame');

        // Función para cambiar de pestaña
        function switchTab(viewId) {
            // Actualizar estilos de todos los botones de pestaña (en todos los menús)
            document.querySelectorAll('.tab-btn').forEach(btn => {
                const isActive = btn.getAttribute('data-view') === viewId;
                btn.classList.toggle('tab-active', isActive);
                btn.setAttribute('aria-selected', isActive.toString());
            });

            const file = views[viewId];
            if (file) {
                iframe.src = file;
                history.replaceState(null, '', '#' + viewId);
            }

            // Cerrar todos los menús después de seleccionar una pestaña (opcional)
            closeAllMenus();
        }

        // Cerrar todos los dropdowns
        function closeAllMenus() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('open');
            });
        }

        // Abrir/cerrar un menú específico
        function toggleMenu(container) {
            const menu = container.querySelector('.dropdown-menu');
            const isOpen = menu.classList.contains('open');
            closeAllMenus();
            if (!isOpen) {
                menu.classList.add('open');
            }
        }

        // Configurar eventos de los botones dropdown
        const dropdownContainers = document.querySelectorAll('.dropdown-container');
        dropdownContainers.forEach(container => {
            const btn = container.querySelector('.dropdown-btn');
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMenu(container);
            });
        });

        // Asignar eventos a los botones de pestaña (existentes y futuros)
        function bindTabEvents() {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.removeEventListener('click', tabClickHandler);
                btn.addEventListener('click', tabClickHandler);
            });
        }

        function tabClickHandler(e) {
            e.stopPropagation();
            const view = e.currentTarget.getAttribute('data-view');
            if (view && views[view]) {
                switchTab(view);
            }
        }

        // Cerrar menús al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-container')) {
                closeAllMenus();
            }
        });

        // Inicializar eventos de pestañas
        bindTabEvents();

        // Botones del header (contacto e inicio de sesión)
        document.getElementById('headerContactBtn')?.addEventListener('click', () => {
            const viewId = 'contacto';
            if (views[viewId]) switchTab(viewId);
        });
        document.getElementById('headerLoginBtn')?.addEventListener('click', () => {
            const viewId = 'auth/login';
            if (views[viewId]) switchTab(viewId);
        });

        // Al cargar, restaurar pestaña activa según hash o la default (bienvenida)
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1);
            let activeView = (hash && views[hash]) ? hash : '<?= $defaultTab ?>';
            switchTab(activeView);
        });

        // Sincronizar con cambios de hash
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (hash && views[hash]) {
                switchTab(hash);
            }
        });

        // Observador para nuevas pestañas (por si se agregaran dinámicamente)
        const observer = new MutationObserver(() => bindTabEvents());
        observer.observe(document.body, { childList: true, subtree: true });
    </script>
</body>
</html>