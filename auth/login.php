<?php
/**
 * ========================================================================
 * LOGIN - SISTEMA GANADERO
 * ========================================================================
 * Página de inicio de sesión con correo y contraseña.
 * Redirige según el rol del usuario:
 *   - administrador → administrativas/t_seccion_ADMIN/
 *   - ganadero     → herramientas/partos/
 *   - operador     → produccion/diaria/
 *   - cliente      → inicio/bienvenida/
 */

// ========================================================================
// 1. INCLUIR DEPENDENCIAS Y FUNCIONES DE AUTENTICACIÓN
// ========================================================================
// Incluir las funciones de autenticación (que ya incluyen conexión PDO y PHPMailer)
require_once __DIR__ . '/funciones_auth.php';

// ========================================================================
// 2. VARIABLE PARA MENSAJES DE ERROR
// ========================================================================
$error = null;

// ========================================================================
// 3. PROCESAR EL FORMULARIO DE LOGIN
// ========================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validar que no estén vacíos
    if (empty($email) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        // Intentar autenticar con la función de login
        $usuario = loginUsuario($email, $password);

        if ($usuario === false) {
            $error = 'Email o contraseña incorrectos, o cuenta inactiva.';
        } else {
            // Iniciar sesión (guarda datos en $_SESSION y actualiza last_login)
            iniciarSesionUsuario($usuario);

            // Redirigir según el rol
            $rol = $usuario['role'];
            switch ($rol) {
                case 'administrador':
                    $destino = '/administrativas/t_seccion_ADMIN/';
                    break;
                case 'ganadero':
                    $destino = '/herramientas/partos/';
                    break;
                case 'operador':
                    $destino = '/produccion/diaria/';
                    break;
                case 'cliente':
                default:
                    $destino = '/inicio/bienvenida/';
                    break;
            }

            // Redirigir al panel correspondiente
            header('Location: ' . $destino);
            exit;
        }
    }
}

// ========================================================================
// 4. HTML DEL FORMULARIO DE LOGIN
// ========================================================================
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema Ganadero</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-image: url('https://images.unsplash.com/photo-1500595046743-cd271d694d30?auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 2rem;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }
        .login-card:hover {
            transform: scale(1.01);
        }
        .input-group {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
        }
        .input-group:focus-within {
            background: rgba(255, 255, 255, 0.3);
            border-color: #4ade80;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.3);
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
        }
        .btn-primary {
            background: linear-gradient(135deg, #16a34a, #15803d);
            transition: all 0.2s;
            font-weight: 600;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(22, 163, 74, 0.4);
        }
        .btn-google {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s;
            color: white;
            font-weight: 500;
        }
        .btn-google:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
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
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Logo / título -->
        <div class="text-center mb-8">
            <i class="fas fa-tractor text-5xl text-green-300 mb-3"></i>
            <h1 class="text-2xl font-bold text-white tracking-tight">Iniciar Sesión</h1>
            <p class="text-green-100 text-sm mt-1">Sistema de Producción Ganadera</p>
        </div>

        <!-- Mostrar error si existe -->
        <?php if ($error): ?>
            <div class="error-message mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de login -->
        <form method="POST" action="" class="space-y-5">
            <!-- Email -->
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Correo electrónico" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
            </div>

            <!-- Contraseña -->
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="button" onclick="togglePassword(this)" class="text-white/70 hover:text-white">
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <!-- Botón de envío -->
            <button type="submit" class="btn-primary w-full text-white py-3 rounded-xl transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Iniciar sesión
            </button>
        </form>

        <!-- Enlaces adicionales -->
        <div class="mt-6 text-center text-sm text-white/80 space-y-3">
            <p>
                ¿No tienes cuenta?
                <a href="registro.php" class="link">Regístrate aquí</a>
            </p>
            <p>
                <a href="recovery.php" class="link">
                    <i class="fas fa-key mr-1"></i> ¿Olvidaste tu contraseña?
                </a>
            </p>
        </div>

        <!-- Separador -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-white/20"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-transparent text-white/60">O continúa con</span>
            </div>
        </div>

        <!-- Botón Google (aún no implementado, pero dejamos el enlace) -->
        <a href="google_login.php" class="btn-google w-full flex items-center justify-center gap-3 py-3 rounded-xl transition">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="w-5 h-5">
            <span>Continuar con Google</span>
        </a>

        <!-- Mensaje de privacidad (opcional) -->
        <p class="mt-6 text-xs text-white/40 text-center">
            Al iniciar sesión, aceptas nuestros términos y políticas de privacidad.
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