<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganadería Premium | Innovación y Tradición</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {


            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #1a4d2a; /* verde oscuro base */
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
            repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-size: 30px 30px, 12px 12px;
        }


        .grass-pattern {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%233d8c40" opacity="0.15"><path d="M12,2 L14,7 L19,7 L15,10 L17,15 L12,12 L7,15 L9,10 L5,7 L10,7 Z"/></svg>');
            background-repeat: repeat;
            background-size: 40px;
        }
        .card-ganadero {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            border-radius: 2rem;
            overflow: hidden;
            border: 1px solid rgba(255,215,140,0.5);
        }
        .card-ganadero:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 35px -12px rgba(0,0,0,0.3);
        }
        .hero-index, .hero-nosotros {
            position: relative;
            background-blend-mode: overlay;
        }
        .hero-index::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1;
        }
        .hero-nosotros::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1;
        }
        .hero-index > *, .hero-nosotros > * {
            position: relative;
            z-index: 2;
        }
        .btn-ganadero {
            background: #c47a2e;
            transition: all 0.3s ease;
        }
        .btn-ganadero:hover {
            background: #9b5e1f;
            transform: translateY(-2px);
        }
        .bg-crema {
            background-color: #fef3e2;
        }
        .text-cafe {
            color: #5d3a1a;
        }
        .deco-leaf {
            position: absolute;
            opacity: 0.1;
            pointer-events: none;
        }
        /* Nuevos estilos para sección nosotros en verde claro */
        .bg-verde-claro {
            background: #c8e6d9; /* verde muy claro */
        }
        .card-mision-vision {
            background: #faf7ed; /* crema suave */
            border: 1px solid #9bbb7a;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-mision-vision:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 30px -12px rgba(0,0,0,0.2);
        }
        .team-card {
            background: #fffaf0; /* fondo cálido */
            border: 1px solid #c7b57b;
        }
        .section-equipo {
            background: #d9e6b3; /* verde pasto claro */
        }
    </style>
</head>
<body>

<!-- ==================== SECCIÓN BIENVENIDA (INICIO) ==================== -->
<section class="hero-index min-h-screen flex items-center justify-center text-white text-center px-6 relative overflow-hidden"
         style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center;">
    <div class="max-w-4xl">
        <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
            Innovación y Control <span class="text-yellow-400">Ganadero</span>
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 mb-8">
            Gestiona tu producción con tecnología inteligente, monitoreo en tiempo real y máxima calidad.
        </p>
        <a href="#productos" class="inline-block btn-ganadero text-white font-bold py-3 px-8 rounded-full transition shadow-lg">
            Descubre más <i class="fas fa-arrow-down ml-2"></i>
        </a>
    </div>
    <i class="fas fa-leaf deco-leaf text-8xl absolute bottom-10 left-5 rotate-45"></i>
    <i class="fas fa-tractor deco-leaf text-7xl absolute top-20 right-10 opacity-20"></i>
</section>

<!-- CARDS DE PRODUCTOS (Calidad Premium) -->
<section id="productos" class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-16">
        <span class="inline-block bg-yellow-200 text-cafe px-5 py-2 rounded-full font-semibold text-sm shadow">Productos estrella</span>
        <h2 class="text-5xl font-bold text-white mt-4 mb-3 drop-shadow">Calidad Premium</h2>
        <p class="text-yellow-100 text-xl">Producción responsable y sostenible</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <!-- Leche -->
        <div class="card-ganadero shadow-lg">
            <div class="bg-gradient-to-r from-blue-800 to-cyan-700 p-8 text-center">
                <i class="fas fa-tint text-6xl text-white"></i>
            </div>
            <div class="p-8 bg-crema">
                <h3 class="text-3xl font-bold text-cafe mb-4">Leche Premium</h3>
                <p class="text-gray-700 leading-relaxed">Producción láctea con estándares internacionales, monitoreo veterinario y trazabilidad completa.</p>
                <div class="mt-4 text-amber-700"><i class="fas fa-cow"></i> <i class="fas fa-flask ml-2"></i></div>
            </div>
        </div>
        <!-- Carne -->
        <div class="card-ganadero shadow-lg">
            <div class="bg-gradient-to-r from-red-800 to-orange-700 p-8 text-center">
                <i class="fas fa-drumstick-bite text-6xl text-white"></i>
            </div>
            <div class="p-8 bg-crema">
                <h3 class="text-3xl font-bold text-cafe mb-4">Carne Selecta</h3>
                <p class="text-gray-700 leading-relaxed">Animales criados con alimentación balanceada, bienestar animal y procesos sostenibles.</p>
                <div class="mt-4 text-amber-700"><i class="fas fa-utensils"></i> <i class="fas fa-chart-line ml-2"></i></div>
            </div>
        </div>
        <!-- Huevos -->
        <div class="card-ganadero shadow-lg">
            <div class="bg-gradient-to-r from-yellow-700 to-amber-700 p-8 text-center">
                <i class="fas fa-egg text-6xl text-white"></i>
            </div>
            <div class="p-8 bg-crema">
                <h3 class="text-3xl font-bold text-cafe mb-4">Huevos Naturales</h3>
                <p class="text-gray-700 leading-relaxed">Gallinas de corral con alimentación natural y productos frescos de alta calidad.</p>
                <div class="mt-4 text-amber-700"><i class="fas fa-chicken"></i> <i class="fas fa-leaf ml-2"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- BENEFICIOS (Por qué elegirnos) -->
<section class="py-16 relative overflow-hidden">
    <div class="absolute inset-0 grass-pattern opacity-20"></div>
    <div class="max-w-6xl mx-auto px-6 relative z-10">
        <div class="bg-white/90 backdrop-blur-sm rounded-[3rem] p-12 shadow-2xl border border-amber-200">
            <div class="text-center mb-14">
                <h2 class="text-5xl font-bold text-cafe mb-4">¿Por qué elegirnos?</h2>
                <p class="text-gray-700 text-xl">Tecnología + Calidad + Sostenibilidad</p>
            </div>
            <div class="grid md:grid-cols-2 gap-10">
                <div class="flex gap-5 items-start">
                    <i class="fas fa-chart-line text-4xl text-green-700"></i>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Monitoreo Inteligente</h3>
                        <p class="text-gray-600">Sensores y análisis en tiempo real para mejorar la producción.</p>
                    </div>
                </div>
                <div class="flex gap-5 items-start">
                    <i class="fas fa-leaf text-4xl text-green-700"></i>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Producción Sostenible</h3>
                        <p class="text-gray-600">Cuidado ambiental y uso eficiente de recursos.</p>
                    </div>
                </div>
                <div class="flex gap-5 items-start">
                    <i class="fas fa-shield-heart text-4xl text-amber-700"></i>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Bienestar Animal</h3>
                        <p class="text-gray-600">Espacios adecuados y alimentación saludable.</p>
                    </div>
                </div>
                <div class="flex gap-5 items-start">
                    <i class="fas fa-award text-4xl text-yellow-600"></i>
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Calidad Certificada</h3>
                        <p class="text-gray-600">Reconocidos por excelencia en productos ganaderos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SECCIÓN NOSOTROS CON FONDO VERDE CLARO ==================== -->
<section class="hero-nosotros h-[60vh] flex items-center justify-center text-center text-white px-6 relative"
         style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=1600&auto=format&fit=crop'); background-size: cover; background-position: center;">
    <div>
        <h1 class="text-6xl md:text-8xl font-extrabold mb-6">Sobre Nosotros</h1>
        <p class="text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto leading-relaxed">
            Somos un equipo comprometido con el desarrollo de soluciones tecnológicas modernas e innovadoras para transformar la producción ganadera.
        </p>
    </div>
</section>

<!-- MISIÓN Y VISIÓN con fondo verde claro y tarjetas en tonos tierra -->
<section class="relative py-28 overflow-hidden bg-verde-claro">
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-24">
            <span class="px-6 py-2 bg-green-800 text-white rounded-full font-semibold shadow">NUESTRO PROPÓSITO</span>
            <h2 class="text-5xl font-extrabold text-green-900 mt-6 mb-4">Empresa Ganadera</h2>
            <p class="text-green-800 text-xl max-w-4xl mx-auto">Innovamos mediante herramientas digitales inteligentes para optimizar la gestión ganadera y mejorar la productividad.</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Misión -->
            <div class="card-mision-vision rounded-[3rem] p-12 shadow-xl">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-amber-700 to-yellow-700 flex items-center justify-center shadow-lg mb-8">
                    <i class="fas fa-bullseye text-5xl text-white"></i>
                </div>
                <h3 class="text-4xl font-extrabold text-cafe mb-3">Misión</h3>
                <div class="w-20 h-1 bg-amber-600 rounded-full mb-6"></div>
                <p class="text-gray-700 text-lg leading-relaxed">
                    Brindamos soluciones tecnológicas innovadoras y accesibles que permiten optimizar procesos, mejorar la productividad y facilitar la administración eficiente del sector ganadero.
                </p>
            </div>
            <!-- Visión -->
            <div class="card-mision-vision rounded-[3rem] p-12 shadow-xl">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-green-700 to-emerald-700 flex items-center justify-center shadow-lg mb-8">
                    <i class="fas fa-eye text-5xl text-white"></i>
                </div>
                <h3 class="text-4xl font-extrabold text-cafe mb-3">Visión</h3>
                <div class="w-20 h-1 bg-green-700 rounded-full mb-6"></div>
                <p class="text-gray-700 text-lg leading-relaxed">
                    Convertirnos en una empresa líder en innovación tecnológica, reconocida por desarrollar sistemas modernos e inteligentes que impulsen el crecimiento digital del sector ganadero.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- EQUIPO DE TRABAJO con fondo verde claro y tarjetas en tonos cálidos -->
<section class="relative py-28 section-equipo">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-20">
            <span class="px-6 py-2 bg-green-800 text-white rounded-full font-semibold">TEAM WORK</span>
            <h2 class="text-5xl font-extrabold text-green-900 mt-6 mb-4">Nuestro Equipo</h2>
            <p class="text-green-800 text-xl max-w-3xl mx-auto">Profesionales comprometidos con la innovación, la creatividad y el desarrollo tecnológico.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Integrante 1 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-amber-600 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">Isaí Enoc Arana Zepeda</h3>
                <div class="w-16 h-0.5 bg-amber-500 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
            <!-- Integrante 2 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-green-700 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">Oscar Francisco Arévalo Palma</h3>
                <div class="w-16 h-0.5 bg-green-700 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
            <!-- Integrante 3 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-orange-600 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">Jonathan Josué Barrera Martínez</h3>
                <div class="w-16 h-0.5 bg-orange-600 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
            <!-- Integrante 4 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-pink-600 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">Yoselin Abigail Contreras Linares</h3>
                <div class="w-16 h-0.5 bg-pink-600 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
            <!-- Integrante 5 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-cyan-600 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">José Mario Mendoza Ramírez</h3>
                <div class="w-16 h-0.5 bg-cyan-600 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
            <!-- Integrante 6 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-purple-600 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">Tania Carolina Segura López</h3>
                <div class="w-16 h-0.5 bg-purple-600 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
            <!-- Integrante 7 -->
            <div class="team-card rounded-3xl p-6 text-center shadow-xl transition hover:-translate-y-2 hover:shadow-2xl">
                <div class="w-32 h-32 mx-auto mb-5 overflow-hidden rounded-full border-4 border-yellow-600 shadow-md">
                    <img src="https://i.imgur.com/6VBx3io.png" class="w-full h-full object-cover" alt="integrante">
                </div>
                <h3 class="text-xl font-bold text-gray-800">Lourdes Carolina Contreras Tacón</h3>
                <div class="w-16 h-0.5 bg-yellow-600 mx-auto my-3"></div>
                <p class="text-gray-500 text-sm"></p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="relative overflow-hidden py-12 bg-green-900 text-white">
    <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
        <i class="fa-solid fa-cow text-5xl text-yellow-300 mb-5"></i>
        <h3 class="text-3xl font-bold mb-3">Sistema de Producción Ganadera</h3>
        <p class="text-amber-100 text-lg max-w-2xl mx-auto">Tecnología inteligente para una producción eficiente, sostenible y moderna.</p>
        <div class="flex justify-center gap-8 mt-8 text-2xl">
            <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-facebook"></i></a>
            <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-instagram"></i></a>
            <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-linkedin"></i></a>
            <a href="#" class="hover:text-yellow-300 transition"><i class="fab fa-github"></i></a>
        </div>
        <div class="border-t border-amber-800/30 mt-8 pt-6 text-amber-200 text-sm">
            © 2025 Ganadería Premium - Comprometidos con el campo y la tecnología
        </div>
    </div>
</footer>

<!-- Botón volver arriba -->
<a href="#" class="fixed bottom-6 right-6 bg-amber-700 hover:bg-amber-800 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition z-50">
    <i class="fas fa-arrow-up"></i>
</a>

</body>
</html>