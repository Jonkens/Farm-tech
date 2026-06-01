<?php
// Incluir conexión a la base de datos
require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../includes/query_helper.php';

$mensaje_exito = '';
$mensaje_error = '';
$datos_formulario = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar datos
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validar campos obligatorios
    if (empty($name) || empty($email) || empty($message)) {
        $mensaje_error = 'Por favor completa todos los campos obligatorios (nombre, email y mensaje).';
        // Guardar datos para rellenar el formulario
        $datos_formulario = ['name' => $name, 'email' => $email, 'phone' => $phone, 'message' => $message];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje_error = 'El formato del correo electrónico no es válido.';
        $datos_formulario = ['name' => $name, 'email' => $email, 'phone' => $phone, 'message' => $message];
    } else {
        // Insertar en la base de datos
        try {
            $sql = "INSERT INTO contact_messages (full_name, email, phone, message, status) 
                    VALUES (:full_name, :email, :phone, :message, 'pendiente')";
            $params = [
                ':full_name' => $name,
                ':email' => $email,
                ':phone' => $phone,
                ':message' => $message
            ];
            ejecutarConsulta($pdo, $sql, $params);
            
            // Redirigir con mensaje de éxito (evitar reenvío del formulario)
            header('Location: index.php?success=1');
            exit;
        } catch (PDOException $e) {
            $mensaje_error = 'Error al guardar el mensaje. Por favor intenta más tarde.';
            error_log('Error al guardar mensaje de contacto: ' . $e->getMessage());
            $datos_formulario = ['name' => $name, 'email' => $email, 'phone' => $phone, 'message' => $message];
        }
    }
}

// Mostrar mensaje de éxito si viene por GET
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $mensaje_exito = '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Contáctanos</title>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Iconos -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
  background: url("https://images.unsplash.com/photo-1500595046743-cd271d694d30") no-repeat center/cover fixed;
  margin: 0;
}
</style>

</head>
<body class="min-h-screen flex flex-col">

<!-- Overlay -->
<div class="absolute inset-0 bg-black/50 -z-0"></div>

<!-- CONTENIDO -->
<div class="flex-1 flex flex-col items-center justify-center relative z-10 py-8">

  <!-- Formulario -->
  <div class="bg-white/20 backdrop-blur-lg p-8 rounded-xl w-[400px] text-black shadow-2xl">
    
    <h2 class="text-2xl mb-5 font-semibold text-white">Contáctanos</h2>

    <?php if ($mensaje_exito): ?>
      <div class="mb-4 p-3 bg-green-700 text-white rounded-lg text-center"><?= htmlspecialchars($mensaje_exito) ?></div>
    <?php elseif ($mensaje_error): ?>
      <div class="mb-4 p-3 bg-red-700 text-white rounded-lg text-center"><?= htmlspecialchars($mensaje_error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <!-- Nombre -->
      <div class="flex items-center bg-white/30 mb-4 p-3 rounded">
        <i class="fa-regular fa-user mr-2 text-white"></i>
        <input type="text" name="name" value="<?= htmlspecialchars($datos_formulario['name']) ?>" placeholder="Nombre Completo"
          class="w-full bg-transparent outline-none text-white placeholder-white/70" required>
      </div>

      <!-- Email -->
      <div class="flex items-center bg-white/30 mb-4 p-3 rounded">
        <i class="fa-regular fa-envelope mr-2 text-white"></i>
        <input type="email" name="email" value="<?= htmlspecialchars($datos_formulario['email']) ?>" placeholder="E-mail"
          class="w-full bg-transparent outline-none text-white placeholder-white/70" required>
      </div>

      <!-- Teléfono -->
      <div class="flex items-center bg-white/30 mb-4 p-3 rounded">
        <i class="fa-solid fa-phone mr-2 text-white"></i>
        <input type="tel" name="phone" value="<?= htmlspecialchars($datos_formulario['phone']) ?>" placeholder="Teléfono de contacto"
          class="w-full bg-transparent outline-none text-white placeholder-white/70">
      </div>

      <!-- Mensaje -->
      <div class="flex items-start bg-white/30 mb-4 p-3 rounded">
        <i class="fa-regular fa-message mr-2 mt-1 text-white"></i>
        <textarea name="message" placeholder="Escribe tu mensaje aquí..."
          class="w-full bg-transparent outline-none text-white placeholder-white/70 resize-none h-20" required><?= htmlspecialchars($datos_formulario['message']) ?></textarea>
      </div>

      <!-- Botón Enviar -->
      <button type="submit"
        class="w-full py-3 bg-green-700 hover:bg-green-900 text-white rounded-full transition font-semibold">
        Enviar Mensaje <i class="fa-solid fa-paper-plane ml-1"></i>
      </button>
    </form>
            
  </div>

  <!-- Galería -->
  <div class="flex gap-3 mt-5">
    <img src="Cruzamientos-ganado-lechero.jpg"
      class="w-[120px] h-[80px] object-cover rounded shadow-lg">
    <img src="https://images.unsplash.com/photo-1596733430284-f7437764b1a9"
      class="w-[120px] h-[80px] object-cover rounded shadow-lg">
    <img src="Magazine58a71241104fd_17022017.jpg"
      class="w-[120px] h-[80px] object-cover rounded shadow-lg">
  </div>

  <!-- Redes -->
  <div class="mt-4 flex gap-4 text-2xl text-white">
    <i class="fa-brands fa-instagram cursor-pointer hover:text-pink-400 transition"></i>
    <i class="fa-brands fa-facebook cursor-pointer hover:text-blue-400 transition"></i>
  </div>

</div>

</body>
</html>