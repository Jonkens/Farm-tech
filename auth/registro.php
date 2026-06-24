<?php
/**
 * ========================================================================
 * REGISTRO DE USUARIOS - SISTEMA GANADERO
 * ========================================================================
 * Permite a nuevos usuarios crear una cuenta.
 * Por defecto, todos los registros obtienen el rol 'cliente'.
 * 
 * REDIRECCIONES CLAVE (VERIFICAR QUE COINCIDAN CON TU ESTRUCTURA):
 * ----------------------------------------------------------------
 * 1. Después de registro exitoso → auth/login.php?registro=ok
 * 2. Enlace "Iniciar sesión" (si ya tienes cuenta) → auth/login.php
 * 3. Enlace "¿Olvidaste tu contraseña?" → auth/recovery.php
 */

// ========================================================================
// 1. INCLUIR DEPENDENCIAS Y FUNCIONES DE AUTENTICACIÓN
// ========================================================================
require_once __DIR__ . '/funciones_auth.php';

// ========================================================================
// 2. VARIABLES PARA MENSAJES Y DATOS DEL FORMULARIO
// ========================================================================
$error = null;
$exito = false;
$nombre    = '';
$apellido  = '';
$username  = '';
$email     = '';
$telefono  = '';

// Si el usuario ya está logueado, redirigir al inicio (no debería estar en registro)
if (isset($_SESSION['usuario_id'])) {
    header('Location: /index.php');
    exit;
}

// ========================================================================
// 3. PROCESAR EL FORMULARIO DE REGISTRO
// ========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y limpiar datos
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellido  = trim($_POST['apellido'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $password  = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($username) || empty($email) || empty($password)) {
        $error = 'Todos los campos obligatorios deben estar completos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        // Construir nombre completo
        $fullName = $nombre . ' ' . $apellido;

        // Intentar registrar (rol 'cliente' por defecto)
        $id = registrarUsuario($username, $fullName, $email, $telefono, $password, 'cliente', null);

        if ($id === false) {
            $error = 'El correo o el nombre de usuario ya están registrados.';
        } else {
            // Registro exitoso -> redirige al login con mensajef de éxito
            $exito = true;
            // 🔴 REDIRECCIÓN CLAVE 1: Después de registro exitoso
            header('Location: login.php?registro=ok');
            exit;
        }
    }
}

// ========================================================================
// 4. HTML DEL FORMULARIO DE REGISTRO
// ========================================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Ganadero</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
            padding: 1rem;
        }
        .register-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 2rem;
            padding: 2rem 1.8rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            max-height: 95vh;
            overflow-y: auto;
        }
        .register-card::-webkit-scrollbar {
            width: 4px;
        }
        .register-card::-webkit-scrollbar-thumb {
            background: #4ade80;
            border-radius: 10px;
        }
        .input-group {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s;
        }
        .input-group:focus-within {
            background: rgba(255, 255, 255, 0.25);
            border-color: #4ade80;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.2);
        }
        .input-group input {
            background: transparent;
            border: none;
            outline: none;
            width: 100%;
            color: white;
            font-weight: 500;
        }
        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .input-group i {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            min-width: 1.2rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #16a34a, #15803d);
            transition: all 0.2s;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(22, 163, 74, 0.5);
        }
        .link {
            color: #86efac;
            text-decoration: none;
            font-weight: 500;
        }
        .link:hover {
            text-decoration: underline;
            color: #bbf7d0;
        }
        .error-message {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            text-align: center;
            font-weight: 500;
        }
        .text-muted {
            color: rgba(255, 255, 255, 0.6);
        }
        /* Grid para nombre y apellido */
        .name-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        @media (max-width: 480px) {
            .name-grid {
                grid-template-columns: 1fr;
            }
            .register-card {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="register-card">
        <!-- Logo / título -->
        <div class="text-center mb-6">
            <i class="fas fa-user-plus text-4xl text-green-300 mb-2"></i>
            <h1 class="text-2xl font-bold text-white tracking-tight">Crear Cuenta</h1>
            <p class="text-green-100 text-sm mt-1">Regístrate en el sistema ganadero</p>
        </div>

        <!-- Mostrar error si existe -->
        <?php if ($error): ?>
            <div class="error-message mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de registro -->
        <form method="POST" action="" class="space-y-4">
            <!-- Nombre y Apellido (grid) -->
            <div class="name-grid">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($nombre) ?>" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($apellido) ?>" required>
                </div>
            </div>

            <!-- Nombre de usuario -->
            <div class="input-group">
                <i class="fas fa-at"></i>
                <input type="text" name="username" placeholder="Nombre de usuario (ej: juan123)" value="<?= htmlspecialchars($username) ?>" required>
            </div>

            <!-- Correo electrónico -->
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Correo electrónico" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <!-- Teléfono (opcional) -->
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="telefono" placeholder="Teléfono (opcional)" value="<?= htmlspecialchars($telefono) ?>">
            </div>

            <!-- Contraseña -->
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña (mínimo 6 caracteres)" required minlength="6">
                <button type="button" onclick="togglePassword(this)" class="text-white/60 hover:text-white">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <!-- Confirmar contraseña -->
            <div class="input-group">
                <i class="fas fa-check-circle"></i>
                <input type="password" name="password_confirm" placeholder="Confirmar contraseña" required minlength="6">
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="btn-primary w-full text-white py-3 rounded-xl transition">
                <i class="fas fa-user-plus mr-2"></i> Registrarse
            </button>
        </form>

        <!-- Enlaces adicionales -->
        <div class="mt-6 text-center text-sm text-white/80 space-y-2">
            <p>
                ¿Ya tienes cuenta?
                <!-- 🔴 REDIRECCIÓN CLAVE 2: Enlace "Iniciar sesión" -->
                <a href="login.php" class="link">Inicia sesión aquí</a>
            </p>
            <p>
                <a href="recovery.php" class="link">
                    <i class="fas fa-key mr-1"></i> ¿Olvidaste tu contraseña?
                </a>
            </p>
        </div>

        <!-- Mensaje de privacidad -->
        <p class="mt-4 text-xs text-white/30 text-center">
            Al registrarte, aceptas nuestros términos de uso y política de privacidad.
        </p>
    </div>

    <!-- ======================================================================== -->
    <!-- JAVASCRIPT: Mostrar/ocultar contraseña                                  -->
    <!-- ======================================================================== -->
    <script>
        function togglePassword(btn) {
            const input = btn.parentElement.querySelector('input');
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>
</html>