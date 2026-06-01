<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Ganadería</title>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Iconos -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Google -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<style>
body{
  background-image: url("vacas en HD.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  background-attachment: fixed;
}
</style>

</head>

<body class="min-h-screen flex flex-col font-sans">

<!-- Overlay oscuro -->
<div class="absolute inset-0 bg-black/40 -z-0"></div>

<!-- NAVBAR SUPERIOR -->
<nav class="relative z-10 w-full bg-black/50 backdrop-blur-md px-6 py-3 flex justify-between items-center">
  <div class="text-white font-bold text-lg flex items-center gap-2">
    <i class="fa fa-cow text-green-400"></i>
    <span>Sistema Ganadero</span>
  </div>
  <div class="flex gap-4">
    <a href="Contactanos.PHP"
      class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition text-sm font-semibold">
      <i class="fa fa-envelope"></i> Contáctanos
    </a>
  </div>
</nav>

<!-- LOGIN centrado -->
<div class="flex-1 flex items-center justify-center relative z-10">
  <div class="w-[420px] p-10 bg-white/30 backdrop-blur-lg rounded-xl shadow-2xl text-center">

    <h2 class="text-xl font-bold mb-5 text-white">Iniciar Sesión</h2>

    <!-- EMAIL -->
    <div class="relative mb-5">
      <i class="fa fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-600"></i>
      <input type="email" id="correo" placeholder="Correo electrónico"
        class="w-full p-3 pl-12 rounded-lg bg-white/90 outline-none">
    </div>

    <!-- PASSWORD -->
    <div class="relative mb-5">
      <i class="fa fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-600"></i>
      <input type="password" id="password" placeholder="Contraseña"
        class="w-full p-3 pl-12 pr-12 rounded-lg bg-white/90 outline-none">
      <i class="fa fa-eye absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-gray-600"
        id="toggleIcon" onclick="mostrarPassword()"></i>
    </div>

    <!-- BOTON INICIAR SESIÓN -->
    <button onclick="login()"
      class="w-full p-3 rounded-lg bg-gray-200 hover:bg-gray-300 transition text-lg font-semibold">
      Iniciar sesión
    </button>

    <!-- GOOGLE -->
    <div id="g_id_onload"
      data-client_id="TU_CLIENT_ID_AQUI"
      data-callback="handleCredentialResponse">
    </div>

    <div class="mt-4 flex justify-center">
      <div class="g_id_signin"
        data-type="standard"
        data-size="large">
      </div>
    </div>

    <!-- LINKS INFERIORES -->
    <div class="mt-5 flex flex-col gap-2">
      <span class="text-white cursor-pointer hover:underline text-sm" onclick="irRecuperar()">
        ¿Olvidaste tu contraseña?
      </span>
    </div>

  </div>
</div>

<script>
function mostrarPassword(){
  let pass = document.getElementById("password");
  let icon = document.getElementById("toggleIcon");
  if(pass.type === "password"){
    pass.type = "text";
    icon.classList.replace("fa-eye", "fa-eye-slash");
  } else {
    pass.type = "password";
    icon.classList.replace("fa-eye-slash", "fa-eye");
  }
}

function login(){
  let correo = document.getElementById("correo").value;
  let pass   = document.getElementById("password").value;

  if(correo === "" || pass === ""){
    alert("Por favor llena todos los campos");
    return;
  }

  alert("Bienvenido: " + correo);
  window.location.href = "animales.PHP";
}

/* GOOGLE */
function handleCredentialResponse(response){
  console.log("TOKEN:", response.credential);
  alert("Login con Google exitoso");
  window.location.href = "animales.PHP";
}

function irRecuperar(){
  window.location.href = "recuperacion.PHP";
}

function irContacto(){
  window.location.href = "Contactanos.PHP";
}
</script>

</body>
</html>
