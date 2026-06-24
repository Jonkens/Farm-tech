<?php

/**
 * ====================================================================
 * SISTEMA DE PRODUCCIÓN GANADERA - PUNTO DE ENTRADA PRINCIPAL
 * ====================================================================
 * Este archivo (index.php) es el núcleo de la interfaz web.
 * Genera un layout con menús desplegables (sin cajas, estilo moderno)
 * y carga el contenido mediante un iframe según la pestaña seleccionada.
 * 
 * Las opciones de menú se definen en $tabsConfig y cada vista se resuelve
 * mediante $viewsMap, que asigna un identificador a una ruta física.
 * 
 * La navegación se maneja con JavaScript para actualizar el iframe
 * y mantener el historial (hash) sin recargar la página.
 */

// ====================================================================
// 1. CONFIGURACIÓN DE PESTAÑAS (MENÚ PRINCIPAL)
// ====================================================================
// Cada clave del array es una categoría (que aparece como botón desplegable).
// Cada categoría contiene un array asociativo: 'identificador_vista' => 'Etiqueta visible'
// El identificador se usa en el mapa de vistas y en la URL (#identificador).
$tabsConfig = [
    'produccion' => [
        'produccion/diaria'            => 'Producción diaria',
        'produccion/semanal'           => 'Producción semanal',
        'produccion/mensual'           => 'Producción mensual',
        'produccion/estimacion_carne'  => 'Estimación de carne',
    ],
    'salud_y_alimentacion' => [
        'salud/animales'           => 'Salud del ganado',
        'salud/alimentacion'       => 'Control de alimentación',
        'salud/distribucion_hato'  => 'Distribución de hato',
    ],
    'finanzas_y_comercio' => [
        'finanzas/compra_venta'  => 'Compra y venta',
        'finanzas/Generar_pdf'   => 'Reportes médicos',
    ],
    'herramientas' => [
        'herramientas/busqueda 2'  => 'Búsqueda avanzada',
        'herramientas/planicicacion' => 'Tareas programadas',
        'herramientas/mensajes'    => 'Mensajes recibidos',
        'herramientas/partos'      => 'Registros de partos',
    ],
    'administrativas' => [
        'administrativas/t_seccion_ADMIN' => 'Administración de datos',
    ],
];

// ====================================================================
// 2. MAPA VISTA -> RUTA FÍSICA
// ====================================================================
// Asocia cada identificador de vista (ej: 'produccion/diaria') con la ruta
// del archivo que debe cargarse en el iframe.
// Se construye automáticamente a partir de $tabsConfig, pero también
// se agregan vistas especiales que no están en el menú (bienvenida, contacto, login, etc.)
$viewsMap = [];
foreach ($tabsConfig as $carpeta => $tabs) {
    foreach ($tabs as $viewId => $label) {
        // Por defecto, cada vista está en una subcarpeta con su propio index.php
        $viewsMap[$viewId] = $viewId . '/index.php';
    }
}
// Vista de bienvenida (accesible al hacer clic en el título principal)
$viewsMap['inicio/bienvenida'] = 'inicio/bienvenida/index.php';
// Vistas especiales con parámetros o rutas diferentes
$viewsMap['herramientas/partos'] = 'herramientas/partos/index.php?pagina=dashboard';
$viewsMap['herramientas/tareas3.0'] = 'herramientas/planificacion/tareas3.0/index.html';
$viewsMap['contacto'] = 'contacto/index.php';
$viewsMap['auth/login'] = 'auth/login.php';
$viewsMap['auth/recuperar'] = 'auth/recuperar/index.php';

// ====================================================================
// 3. DETERMINACIÓN DE LA PESTAÑA ACTIVA
// ====================================================================
// Por defecto se muestra la bienvenida.
// Si el parámetro GET 'tab' coincide con alguna clave de $viewsMap, se usa esa.
$defaultTab = 'inicio/bienvenida';
$activeTab = $defaultTab;
if (isset($_GET['tab']) && array_key_exists($_GET['tab'], $viewsMap)) {
    $activeTab = $_GET['tab'];
}
// Ruta que se cargará inicialmente en el iframe
$defaultSrc = $viewsMap[$activeTab];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Producción Ganadera</title>
    <!-- Tailwind CSS para estilos rápidos y responsivos -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Reset básico */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            height: 100vh;
            overflow: hidden; /* Evita scroll en la página (el iframe maneja su propio scroll) */
        }

        /* Estilo de los botones desplegables: sin caja, solo texto e icono */
        .dropdown-btn {
            background: transparent;
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .dropdown-btn i {
            font-size: 0.7rem;
            transition: transform 0.2s;
        }
        .dropdown-btn:hover {
            color: #f5c542;
            transform: translateY(-1px);
        }

        /* Contenedor relativo para posicionar el menú desplegable */
        .dropdown-container {
            position: relative;
        }

        /* Menú desplegable: fondo verde claro, bordes redondeados, sombra */
        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: #e8f5e9;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            min-width: 200px;
            z-index: 50;
            display: none; /* Oculto por defecto; se abre con JavaScript */
            border: 1px solid #c8e6c9;
            overflow: hidden;
        }
        .dropdown-menu.open {
            display: block; /* Clase que activa la visibilidad */
        }

        .tabs-list {
            padding: 0.5rem;
        }

        /* Cada opción del menú desplegable */
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
            color: #1f4f1f;
            cursor: pointer;
        }
        .tab-btn:hover {
            background-color: #c8e6c9;
            transform: translateX(4px);
            color: #0f2b0f;
        }
        .tab-active {
            background-color: #2d6a2d;
            color: white;
        }
        .tab-active:hover {
            background-color: #1f4f1f;
            transform: translateX(0);
        }

        /* Cabecera y navegación unificadas con patrón decorativo de líneas y puntos */
        .header-nav {
            background-color: #194226;
            background-image:
                radial-gradient(
                    circle at 10% 20%,
                    rgba(255,215,140,0.1) 2%,
                    transparent 2.5%
                ),
                repeating-linear-gradient(
                    45deg,
                    rgba(34,85,34,0.3) 0px,
                    rgba(34,85,34,0.10) 2px,
                    transparent 2px,
                    transparent 8px
                );
            background-size: 30px 30px, 12px 12px;
        }

        /* Botones de la cabecera (Contáctanos e Iniciar sesión) */
        .header-btn {
            background-color: #3d6b3a;
            transition: all 0.2s;
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 0.5rem;
        }
        .header-btn:hover {
            background-color: #1e3a1e;
            transform: translateY(-2px);
        }

        /* Título principal convertido en botón (para volver a la bienvenida) */
        .title-btn {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: opacity 0.2s;
            padding: 0;
            font: inherit;
            color: white;
        }
        .title-btn:hover {
            opacity: 0.85;
            transform: scale(1.01);
        }

        /* El iframe ocupa todo el espacio disponible y tiene fondo blanco */
        iframe {
            background: white;
        }
    </style>
</head>
<body class="h-screen flex flex-col">

    <!-- ================================================================ -->
    <!-- CABECERA + NAVEGACIÓN (unificadas)                               -->
    <!-- ================================================================ -->
    <div class="header-nav flex-shrink-0">
        <!-- Fila superior: título y botones de contacto/login -->
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <!-- Título con icono; al hacer clic lleva a la bienvenida -->
                <button id="welcomeTitleBtn" class="title-btn">
                    <i class="fas fa-tractor text-2xl text-yellow-300"></i>
                    <h1 class="text-xl font-bold tracking-tight">Sistema de Producción Ganadera</h1>
                </button>
            </div>
            <div class="flex gap-2">
                <button id="headerContactBtn" class="header-btn flex items-center gap-2">
                    <i class="fas fa-envelope"></i> Contáctanos
                </button>
                <button id="headerLoginBtn" class="header-btn flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Iniciar sesión
                </button>
            </div>
        </div>

        <!-- Barra de navegación: genera los botones desplegables para cada categoría -->
        <nav class="border-t border-green-800/30 px-4 py-2">
            <div class="container mx-auto flex flex-wrap justify-center gap-2">
                <?php 
                // Etiquetas para mostrar en los botones (más legibles)
                $categoryLabels = [
                    'produccion'            => 'Producción',
                    'salud_y_alimentacion'  => 'Salud y Alimentación',
                    'finanzas_y_comercio'   => 'Finanzas y Comercio',
                    'herramientas'          => 'Herramientas',
                    'administrativas'       => 'Administración'
                ];
                // Recorremos cada categoría definida en $tabsConfig
                foreach ($tabsConfig as $key => $tabs):
                    if (empty($tabs)) continue; // Saltamos categorías vacías
                    $label = $categoryLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
                ?>
                    <div class="dropdown-container" data-category="<?= $key ?>">
                        <button class="dropdown-btn">
                            <?= htmlspecialchars($label) ?> <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="tabs-list">
                                <?php foreach ($tabs as $viewId => $tabLabel): ?>
                                    <!-- Cada opción del menú: al hacer clic se cambia la vista -->
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
    </div>

    <!-- ================================================================ -->
    <!-- CONTENIDO PRINCIPAL (iframe)                                    -->
    <!-- ================================================================ -->
    <main class="flex-1 flex bg-gray-100 overflow-hidden">
        <iframe
            id="content-frame"
            src="<?= htmlspecialchars($defaultSrc) ?>"
            title="Contenido de la sección"
            class="w-full h-full border-0">
        </iframe>
    </main>

    <!-- ================================================================ -->
    <!-- PIE DE PÁGINA                                                   -->
    <!-- ================================================================ -->
    <footer class="text-center text-gray-500 text-xs py-2 border-t border-gray-200 bg-white flex-shrink-0">
        <p>Conexión a PostgreSQL activa | Sistema en tiempo real | © <?= date('Y') ?> Producción Ganadera</p>
    </footer>

    <!-- ================================================================ -->
    <!-- JAVASCRIPT: MANEJO DE NAVEGACIÓN Y EVENTOS                      -->
    <!-- ================================================================ -->
    <script>
        // Mapa de vistas (id -> ruta) pasado desde PHP
        const views = <?= json_encode($viewsMap) ?>;
        const iframe = document.getElementById('content-frame');

        /**
         * Cambia la vista activa:
         * - Actualiza el estilo de los botones (clase tab-active)
         * - Carga la nueva ruta en el iframe
         * - Actualiza el hash de la URL (#viewId) sin recargar la página
         * - Cierra todos los menús desplegables
         */
        function switchTab(viewId) {
            // Marcar el botón correspondiente como activo
            document.querySelectorAll('.tab-btn').forEach(btn => {
                const isActive = btn.getAttribute('data-view') === viewId;
                btn.classList.toggle('tab-active', isActive);
                btn.setAttribute('aria-selected', isActive.toString());
            });
            // Cargar la nueva ruta
            const file = views[viewId];
            if (file) {
                iframe.src = file;
                history.replaceState(null, '', '#' + viewId);
            }
            closeAllMenus();
        }

        /** Cierra todos los menús desplegables abiertos */
        function closeAllMenus() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('open');
            });
        }

        /** Abre o cierra el menú de un contenedor (toggle) */
        function toggleMenu(container) {
            const menu = container.querySelector('.dropdown-menu');
            const isOpen = menu.classList.contains('open');
            closeAllMenus();
            if (!isOpen) {
                menu.classList.add('open');
            }
        }

        // Asignar evento click a cada botón desplegable para abrir/cerrar su menú
        document.querySelectorAll('.dropdown-container').forEach(container => {
            const btn = container.querySelector('.dropdown-btn');
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMenu(container);
            });
        });

        /** Vincula el evento click a todos los botones de pestaña (tab-btn) */
        function bindTabEvents() {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.removeEventListener('click', tabClickHandler);
                btn.addEventListener('click', tabClickHandler);
            });
        }

        /** Manejador de click en una opción del menú */
        function tabClickHandler(e) {
            e.stopPropagation();
            const view = e.currentTarget.getAttribute('data-view');
            if (view && views[view]) switchTab(view);
        }

        // Cerrar menús si se hace clic fuera de ellos
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown-container')) closeAllMenus();
        });

        bindTabEvents();

        // Botón del título: vuelve a la bienvenida
        document.getElementById('welcomeTitleBtn')?.addEventListener('click', () => {
            switchTab('inicio/bienvenida');
        });

        // Botones de cabecera: Contacto y Login
        document.getElementById('headerContactBtn')?.addEventListener('click', () => {
            if (views['contacto']) switchTab('contacto');
        });
        document.getElementById('headerLoginBtn')?.addEventListener('click', () => {
            if (views['auth/login']) switchTab('auth/login');
        });

        // Al cargar la página, si hay un hash en la URL, cargamos esa vista
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1);
            let activeView = (hash && views[hash]) ? hash : '<?= $defaultTab ?>';
            switchTab(activeView);
        });

        // Si el hash cambia (p.ej. al usar el historial del navegador), actualizar
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (hash && views[hash]) switchTab(hash);
        });

        // Observador para re-vincular eventos cuando el DOM cambie (por si se añaden nuevos botones dinámicamente)
        const observer = new MutationObserver(() => bindTabEvents());
        observer.observe(document.body, { childList: true, subtree: true });
    </script>
</body>
</html>