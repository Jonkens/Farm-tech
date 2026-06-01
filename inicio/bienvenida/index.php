<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Ganadero Premium</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

    html{
        scroll-behavior: smooth;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
    }

    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
        box-shadow: 0 20px 25px -12px rgba(0,0,0,0.15);
    }
     <a href="#informacion">Ver información</a>
     <section id="informacion" class="max-w-7xl mx-auto px-6 py-20">
     <section>

        body{
            background:
            radial-gradient(circle at top left,#dff7e3 0%, transparent 30%),
            radial-gradient(circle at bottom right,#dbeafe 0%, transparent 30%),
            #f5f7fa;
        }

        .glass{
            backdrop-filter: blur(12px);
            background: rgba(255,255,255,0.75);
            border:1px solid rgba(255,255,255,0.3);
        }

        .card{
            transition: all .35s ease;
        }

        .card:hover{
            transform: translateY(-10px);
            box-shadow: 0 25px 40px rgba(0,0,0,.12);
        }

        .hero{
            background:
            linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),
            url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

    </style>
</head>

<body class="font-sans">

    <!-- NAVBAR -->
    

    <!-- HERO -->
    <section class="hero min-h-screen flex items-center justify-center text-white text-center px-6">

        <div class="max-w-4xl">

            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                Innovación y Control
                <span class="text-green-400">Ganadero</span>
            </h1>

            <p class="text-xl md:text-2xl text-gray-200 mb-8">
                Gestiona tu producción con tecnología inteligente,
                monitoreo en tiempo real y máxima calidad.
            </p>

    </section>

    <!-- CARDS -->
    <section id="informacion" class="max-w-7xl mx-auto px-6 py-20">

        <div class="text-center mb-16">

            <h2 class="text-5xl font-bold text-gray-800 mb-4">
                Calidad Premium
            </h2>

            <p class="text-gray-600 text-xl">
                Producción responsable y sostenible
            </p>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            <!-- CARD -->
            <div class="card bg-white rounded-3xl overflow-hidden shadow-lg">

                <div class="bg-gradient-to-r from-blue-500 to-cyan-400 p-8 text-center">
                    <i class="fas fa-tint text-6xl text-white"></i>
                </div>

                <div class="p-8">

                    <h3 class="text-3xl font-bold text-gray-800 mb-4">
                        Leche Premium
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Producción láctea con estándares internacionales,
                        monitoreo veterinario y trazabilidad completa.
                    </p>

                </div>

            </div>

            <!-- CARD -->
            <div class="card bg-white rounded-3xl overflow-hidden shadow-lg">

                <div class="bg-gradient-to-r from-red-500 to-orange-400 p-8 text-center">
                    <i class="fas fa-drumstick-bite text-6xl text-white"></i>
                </div>

                <div class="p-8">

                    <h3 class="text-3xl font-bold text-gray-800 mb-4">
                        Carne Selecta
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Animales criados con alimentación balanceada,
                        bienestar animal y procesos sostenibles.
                    </p>

                </div>

            </div>

            <!-- CARD -->
            <div class="card bg-white rounded-3xl overflow-hidden shadow-lg">

                <div class="bg-gradient-to-r from-yellow-400 to-amber-500 p-8 text-center">
                    <i class="fas fa-egg text-6xl text-white"></i>
                </div>

                <div class="p-8">

                    <h3 class="text-3xl font-bold text-gray-800 mb-4">
                        Huevos Naturales
                    </h3>

                    <p class="text-gray-600 leading-relaxed">
                        Gallinas de corral con alimentación natural
                        y productos frescos de alta calidad.
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- BENEFICIOS -->
    <section class="py-20">

        <div class="max-w-6xl mx-auto px-6">

            <div class="glass rounded-[40px] p-12 shadow-2xl">

                <div class="text-center mb-14">

                    <h2 class="text-5xl font-bold text-gray-800 mb-4">
                        ¿Por qué elegirnos?
                    </h2>

                    <p class="text-gray-600 text-xl">
                        Tecnología + Calidad + Sostenibilidad
                    </p>

                </div>

                <div class="grid md:grid-cols-2 gap-10">

                    <div class="flex gap-5">
                        <div>
                            <i class="fas fa-chart-line text-4xl text-blue-600"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold mb-2">
                                Monitoreo Inteligente
                            </h3>

                            <p class="text-gray-600">
                                Sensores y análisis en tiempo real para mejorar la producción.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div>
                            <i class="fas fa-leaf text-4xl text-green-600"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold mb-2">
                                Producción Sostenible
                            </h3>

                            <p class="text-gray-600">
                                Cuidado ambiental y uso eficiente de recursos.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div>
                            <i class="fas fa-shield-heart text-4xl text-red-500"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold mb-2">
                                Bienestar Animal
                            </h3>

                            <p class="text-gray-600">
                                Espacios adecuados y alimentación saludable.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-5">
                        <div>
                            <i class="fas fa-award text-4xl text-yellow-500"></i>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold mb-2">
                                Calidad Certificada
                            </h3>

                            <p class="text-gray-600">
                                Reconocidos por excelencia en productos ganaderos.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white py-10 mt-16">

        <div class="max-w-7xl mx-auto px-6 text-center">

            <i class="fa-solid fa-cow text-5xl text-green-500 mb-4"></i>

            <h3 class="text-3xl font-bold mb-3">
                Sistema de Producción Ganadera
            </h3>

            <p class="text-gray-400">
                Tecnología inteligente para una producción eficiente y moderna.
            </p>

        </div>

    </footer>

</body>
</html>