<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nosotros | Sistema Ganadero</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>

        html{
            scroll-behavior: smooth;
        }

        body{
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .glass{
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.4);
        }

        /* HERO */
        .hero{
            background:
            linear-gradient(rgba(0,0,0,.65),rgba(0,0,0,.65)),
            url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?q=80&w=1600&auto=format&fit=crop');

            background-size: cover;
            background-position: center;
        }

        /* EFECTOS */
        .card-hover{
            transition: all .4s ease;
        }

        .card-hover:hover{
            transform: translateY(-12px);
            box-shadow: 0 35px 60px rgba(0,0,0,.15);
        }

        .team-card:hover .profile-img{
            transform: scale(1.08);
        }

        .profile-img{
            transition: all .4s ease;
        }

        /* FONDO SECCIÓN */
        .section-bg{
            background:
            linear-gradient(rgba(255,255,255,.93),rgba(255,255,255,.93)),
            url('https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=1600&auto=format&fit=crop');

            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

    </style>

</head>

<body>

    <!-- NAVBAR -->
    <

    <!-- HERO -->
    <section class="hero h-[70vh] flex items-center justify-center text-center text-white px-6">

        <div>

            <h1 class="text-6xl md:text-8xl font-extrabold mb-6">
                Sobre Nosotros
            </h1>

            <p class="text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto leading-relaxed">
                Somos un equipo comprometido con el desarrollo
                de soluciones tecnológicas modernas e innovadoras
                para transformar la producción ganadera.
            </p>

        </div>

    </section>

    <!-- EMPRESA -->
    <section class="section-bg relative py-28 overflow-hidden">

        <!-- DECORACIONES -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-green-300 opacity-20 blur-3xl rounded-full"></div>

        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-300 opacity-20 blur-3xl rounded-full"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">

            <!-- TITULO -->
            <div class="text-center mb-24">

                <span class="px-6 py-2 bg-green-100 text-green-700 rounded-full font-semibold shadow">
                    SISTEMA GANADERO
                </span>

                <h2 class="text-6xl font-extrabold text-gray-800 mt-6 mb-6">
                    Nuestra Empresa
                </h2>

                <p class="text-gray-600 text-xl max-w-4xl mx-auto leading-relaxed">
                    Innovamos mediante herramientas digitales inteligentes
                    para optimizar la gestión ganadera y mejorar la productividad.
                </p>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                <!-- MISIÓN -->
                <div class="bg-white rounded-[40px] p-12 shadow-2xl card-hover relative overflow-hidden">

                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-cyan-400"></div>

                    <div class="w-24 h-24 rounded-[30px] bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center shadow-2xl mb-10">

                        <i class="fas fa-bullseye text-5xl text-white"></i>

                    </div>

                    <h3 class="text-5xl font-extrabold text-gray-800 mb-5">
                        Misión
                    </h3>

                    <div class="w-24 h-1 bg-blue-500 rounded-full mb-8"></div>

                    <p class="text-gray-600 text-lg leading-relaxed">
                        Brindamos soluciones tecnológicas innovadoras y accesibles
                        que permiten optimizar procesos, mejorar la productividad
                        y facilitar la administración eficiente del sector ganadero.
                    </p>

                </div>

                <!-- VISIÓN -->
                <div class="bg-white rounded-[40px] p-12 shadow-2xl card-hover relative overflow-hidden">

                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-green-500 to-emerald-400"></div>

                    <div class="w-24 h-24 rounded-[30px] bg-gradient-to-r from-green-500 to-emerald-400 flex items-center justify-center shadow-2xl mb-10">

                        <i class="fas fa-eye text-5xl text-white"></i>

                    </div>

                    <h3 class="text-5xl font-extrabold text-gray-800 mb-5">
                        Visión
                    </h3>

                    <div class="w-24 h-1 bg-green-500 rounded-full mb-8"></div>

                    <p class="text-gray-600 text-lg leading-relaxed">
                        Convertirnos en una empresa líder en innovación tecnológica,
                        reconocida por desarrollar sistemas modernos e inteligentes
                        que impulsen el crecimiento digital.
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- EQUIPO -->
    <section class="relative py-28 overflow-hidden">

        <!-- FONDO -->
        <div class="absolute inset-0 bg-gradient-to-b from-white to-gray-100"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">

            <!-- TITULO -->
            <div class="text-center mb-24">

                <span class="px-6 py-2 bg-blue-100 text-blue-700 rounded-full font-semibold shadow">
                    TEAM WORK
                </span>

                <h2 class="text-6xl font-extrabold text-gray-800 mt-6 mb-6">
                    Nuestro Equipo
                </h2>

                <p class="text-gray-600 text-xl max-w-3xl mx-auto">
                    Profesionales comprometidos con la innovación,
                    la creatividad y el desarrollo tecnológico.
                </p>

            </div>

            <!-- GRID -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                <!-- CARD 1 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-blue-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        Isaí Enoc Arana Zepeda
                    </h3>

                    <div class="w-20 h-1 bg-blue-500 rounded-full mx-auto mt-5"></div>

                </div>

                <!-- CARD 2 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-green-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        Oscar Francisco Arévalo Palma
                    </h3>

                    <div class="w-20 h-1 bg-green-500 rounded-full mx-auto mt-5"></div>

                </div>

                <!-- CARD 3 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-orange-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        Jonathan Josué Barrera Martínez
                    </h3>

                    <div class="w-20 h-1 bg-orange-500 rounded-full mx-auto mt-5"></div>

                </div>

                <!-- CARD 4 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-pink-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        Yoselin Abigail Contreras Linares
                    </h3>
                    <div class="w-20 h-1 bg-pink-500 rounded-full mx-auto mt-5"></div>

                </div>

                <!-- CARD 5 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-cyan-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        José Mario Mendoza Ramírez
                    </h3>

                    <div class="w-20 h-1 bg-cyan-500 rounded-full mx-auto mt-5"></div>

                </div>

                <!-- CARD 6 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-purple-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        Tania Carolina Segura López
                    </h3>

                    <div class="w-20 h-1 bg-purple-500 rounded-full mx-auto mt-5"></div>

                </div>

                <!-- CARD 7 -->
                <div class="team-card bg-white rounded-[40px] p-8 text-center shadow-2xl card-hover">

                    <div class="w-32 h-32 mx-auto mb-8 overflow-hidden rounded-full border-[6px] border-yellow-500 shadow-2xl">

                        <img src="https://i.imgur.com/6VBx3io.png"
                        class="profile-img w-full h-full object-cover">

                    </div>

                    <h3 class="text-2xl font-bold text-gray-800">
                        Lourdes Carolina Contreras Tacón
                    </h3>

                    <div class="w-20 h-1 bg-yellow-500 rounded-full mx-auto mt-5"></div>

                </div>

            </div>

        </div>

    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-white py-16 relative overflow-hidden">

        <div class="absolute inset-0 opacity-10">

            <img src="https://images.unsplash.com/photo-1516467508483-a7212febe31a?q=80&w=1600&auto=format&fit=crop"
            class="w-full h-full object-cover">

        </div>

        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">

            <i class="fa-solid fa-cow text-6xl text-green-500 mb-6"></i>

            <h3 class="text-4xl font-bold mb-4">
                Sistema Ganadero
            </h3>

            <p class="text-gray-300 text-lg max-w-2xl mx-auto leading-relaxed">
                Innovación tecnológica para una producción moderna,
                eficiente y sostenible.
            </p>

            <div class="flex justify-center gap-8 mt-10 text-3xl">

                <a href="#" class="hover:text-blue-500 transition">
                    <i class="fab fa-facebook"></i>
                </a>

                <a href="#" class="hover:text-pink-500 transition">
                    <i class="fab fa-instagram"></i>
                </a>

                <a href="#" class="hover:text-blue-400 transition">
                    <i class="fab fa-linkedin"></i>
                </a>

                <a href="#" class="hover:text-white transition">
                    <i class="fab fa-github"></i>
                </a>

            </div>

        </div>

    </footer>

</body>
</html>