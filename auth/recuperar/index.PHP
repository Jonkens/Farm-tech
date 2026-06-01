<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar Contraseña</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
  background-image: url("vacas.jpg");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}
</style>

</head>

<body class="min-h-screen flex flex-col font-sans">

<div class="absolute inset-0 bg-black/40"></div>

<!-- NAVBAR -->
<nav class="relative z-10 w-full bg-black/50 backdrop-blur-md px-6 py-3 flex justify-between items-center">
  <div class="text-white font-bold text-lg flex items-center gap-2">
    <i class="fa fa-cow text-green-400"></i>
    <span>Sistema Ganadero</span>
  </div>
  <div class="flex gap-4">
    <a href="Inicio_de_secion.php"
      class="flex items-center gap-2 text-white hover:text-green-300 transition text-sm">
      <i class="fa fa-arrow-left"></i> Volver al Inicio de Secion
    </a>
    <a href="Contactanos.PHP"
      class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">
      <i class="fa fa-envelope"></i> Contáctanos
    </a>
  </div>
</nav>

<!-- CONTENIDO -->
<div class="flex-1 flex items-center justify-center relative z-10">
  <div class="w-[400px] p-8 bg-white/30 backdrop-blur-lg rounded-xl shadow-2xl text-center">

    <h2 class="text-xl font-bold mb-4 text-white">Recuperar Contraseña</h2>

    <p class="text-white/90 mb-6 text-sm">
      Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    <div class="relative mb-4">
      <i class="fa fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-600"></i>
      <input type="email" id="correo" placeholder="Correo electrónico"
        class="w-full p-3 pl-12 rounded-lg bg-white/90 outline-none">
    </div>

    <button onclick="enviar()"
      class="w-full p-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-semibold transition">
      Enviar enlace
    </button>

    <button onclick="volver()"
      class="w-full mt-3 p-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
      ← Volver al Menu Principal
    </button>

  </div>
</div>

<script>
function enviar(){
  let correo = document.getElementById("correo").value;

  if(correo === ""){
    alert("Por favor ingresa tu correo");
    return;
  }

  alert("Se ha enviado un enlace de recuperación a:\n" + correo);
}

function volver(){
  window.location.href = "Inicio_de_secion.php";
}
</script>

</body>
</html>
