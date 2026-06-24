<?php
/**
 * ========================================================================
 * FUNCIONES DE AUTENTICACIÓN - SISTEMA GANADERO
 * ========================================================================
 * Este archivo contiene funciones reutilizables para:
 * - Iniciar/cerrar sesión
 * - Verificar permisos de usuario
 * - Manejo de sesiones
 * - Funciones auxiliares para login, registro, recuperación, etc.
 *
 * Todas las funciones utilizan la conexión PDO definida en ../conexion.php
 * y requieren que el autoload de Composer esté cargado (para PHPMailer).
 */

// ========================================================================
// 1. INCLUIR DEPENDENCIAS
// ========================================================================
// Cargar el autoload de Composer (PHPMailer, etc.)
require_once __DIR__ . '/../vendor/autoload.php';
// Cargar la conexión a la base de datos (PDO)
require_once __DIR__ . '/../conexion.php';

// Iniciar sesión si no está iniciada (para todas las funciones)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ========================================================================
// 2. FUNCIONES DE SESIÓN Y AUTENTICACIÓN BÁSICA
// ========================================================================

/**
 * Verifica si el usuario ha iniciado sesión.
 * Opcionalmente, verifica que tenga un rol permitido.
 *
 * @param string|array|null $rolesPermitidos  Rol o array de roles permitidos.
 *                                            Si es null, solo verifica que esté logueado.
 * @return bool  True si cumple, False si no.
 */
function verificarSesion($rolesPermitidos = null): bool {
    // Si no existe usuario en sesión, retorna false
    if (!isset($_SESSION['usuario_id'])) {
        return false;
    }

    // Si no se especifican roles, solo requiere estar logueado
    if ($rolesPermitidos === null) {
        return true;
    }

    // Si se especifica un solo rol como string, lo convertimos a array
    if (is_string($rolesPermitidos)) {
        $rolesPermitidos = [$rolesPermitidos];
    }

    // Verificar que el rol del usuario esté en la lista de permitidos
    return in_array($_SESSION['rol'], $rolesPermitidos);
}

/**
 * Redirige al login si el usuario no está autenticado o no tiene el rol adecuado.
 * Esta función se usa al inicio de cada página protegida.
 *
 * @param string|array|null $rolesPermitidos  Rol(es) requeridos.
 * @param string $urlRedireccion  URL a la que redirigir si falla (por defecto /auth/login.php).
 */
function protegerPagina($rolesPermitidos = null, $urlRedireccion = '/auth/login.php') {
    if (!verificarSesion($rolesPermitidos)) {
        header('Location: ' . $urlRedireccion);
        exit;
    }
}

/**
 * Inicia la sesión de un usuario.
 *
 * @param array $usuario  Datos del usuario (debe contener id, full_name, email, role).
 */
function iniciarSesionUsuario(array $usuario) {
    $_SESSION['usuario_id']  = $usuario['id'];
    $_SESSION['full_name']   = $usuario['full_name'];
    $_SESSION['email']       = $usuario['email'];
    $_SESSION['rol']         = $usuario['role'];
    // Opcional: guardar la foto de perfil si existe (para Google)
    $_SESSION['picture']     = $usuario['picture'] ?? null;
    // Actualizar last_login en la base de datos
    actualizarUltimoLogin($usuario['id']);
}

/**
 * Cierra la sesión del usuario.
 */
function cerrarSesion() {
    $_SESSION = [];
    session_destroy();
}

/**
 * Actualiza el campo last_login de un usuario.
 *
 * @param int $usuarioId
 */
function actualizarUltimoLogin(int $usuarioId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE usuarios SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$usuarioId]);
}

// ========================================================================
// 3. FUNCIONES PARA LOGIN Y REGISTRO
// ========================================================================

/**
 * Intenta autenticar a un usuario con email y contraseña.
 *
 * @param string $email
 * @param string $password
 * @return array|false  Devuelve los datos del usuario si es correcto, o false.
 */
function loginUsuario(string $email, string $password) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT id, username, full_name, email, phone, role, password_hash, estado, picture
        FROM usuarios
        WHERE email = ?
    ");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        return false; // Usuario no encontrado
    }

    // Verificar si la cuenta está activa
    if (!$usuario['estado']) {
        return false; // Cuenta inactiva
    }

    // Verificar la contraseña usando password_verify
    if (!password_verify($password, $usuario['password_hash'])) {
        return false; // Contraseña incorrecta
    }

    // Eliminar datos sensibles antes de devolver
    unset($usuario['password_hash']);
    return $usuario;
}

/**
 * Registra un nuevo usuario en la base de datos.
 *
 * @param string $username
 * @param string $fullName
 * @param string $email
 * @param string $phone (opcional)
 * @param string $password
 * @param string $role  (por defecto 'cliente')
 * @param int|null $createdBy  ID del usuario que crea esta cuenta (opcional)
 * @return int|false  ID del nuevo usuario o false si falla.
 */
function registrarUsuario(string $username, string $fullName, string $email, ?string $phone, string $password, string $role = 'cliente', ?int $createdBy = null) {
    global $pdo;
    // Verificar si el email ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return false; // Email ya registrado
    }

    // Verificar si el username ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return false; // Username ya registrado
    }

    // Hash de la contraseña
    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar usuario
    $sql = "INSERT INTO usuarios (username, full_name, email, phone, role, password_hash, estado, created_by)
            VALUES (?, ?, ?, ?, ?, ?, true, ?) RETURNING id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $fullName, $email, $phone, $role, $hash, $createdBy]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int) $row['id'] : false;
}

// ========================================================================
// 4. FUNCIONES PARA RECUPERACIÓN DE CONTRASEÑA
// ========================================================================

/**
 * Genera un token de recuperación para un usuario y lo guarda en la tabla usuarios_reset.
 *
 * @param string $email
 * @return string|false  El token generado, o false si el email no existe.
 */
function generarTokenRecuperacion(string $email) {
    global $pdo;
    // Buscar el usuario por email
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND estado = true");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$usuario) {
        return false;
    }

    // Generar token aleatorio
    $token = bin2hex(random_bytes(32));

    // Insertar o actualizar token en usuarios_reset
    $sql = "INSERT INTO usuarios_reset (user_id, reset_token, token_expira)
            VALUES (?, ?, NOW() + INTERVAL '1 hour')
            ON CONFLICT (user_id) DO UPDATE
            SET reset_token = EXCLUDED.reset_token,
                token_expira = EXCLUDED.token_expira";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario['id'], $token]);

    return $token;
}

/**
 * Verifica si un token de recuperación es válido y no ha expirado.
 *
 * @param string $token
 * @return int|false  ID del usuario asociado, o false si es inválido.
 */
function verificarTokenRecuperacion(string $token) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT user_id FROM usuarios_reset
        WHERE reset_token = ? AND token_expira > NOW()
    ");
    $stmt->execute([$token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? (int) $row['user_id'] : false;
}

/**
 * Cambia la contraseña de un usuario y elimina el token usado.
 *
 * @param int $usuarioId
 * @param string $nuevaPassword
 * @return bool
 */
function cambiarPassword(int $usuarioId, string $nuevaPassword) {
    global $pdo;
    $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);

    // Actualizar contraseña
    $stmt = $pdo->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
    $ok = $stmt->execute([$hash, $usuarioId]);

    if ($ok) {
        // Eliminar tokens de recuperación para este usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios_reset WHERE user_id = ?");
        $stmt->execute([$usuarioId]);
    }

    return $ok;
}

/**
 * Envía un correo con el enlace de recuperación usando PHPMailer.
 *
 * @param string $email  Destinatario
 * @param string $token  Token de recuperación
 * @return bool  True si se envió correctamente.
 */
function enviarCorreoRecuperacion(string $email, string $token) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'farm.tech2342@gmail.com';   // Cambiar por tu correo
        $mail->Password   = 'phrx uaxw abgh fckj';        // Cambiar por tu contraseña de aplicación
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente y destinatario
        $mail->setFrom('farm.tech2342@gmail.com', 'Sistema Ganadero');
        $mail->addAddress($email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperación de contraseña';
        $enlace = "http://" . $_SERVER['HTTP_HOST'] . "/auth/change_password.php?token=" . $token;
        $mail->Body = "
            <h2>Recuperación de contraseña</h2>
            <p>Haz clic en el siguiente enlace para cambiar tu contraseña (válido por 1 hora):</p>
            <p><a href='$enlace'>$enlace</a></p>
            <p>Si no solicitaste este cambio, ignora este mensaje.</p>
        ";
        $mail->AltBody = "Recupera tu contraseña visitando: $enlace";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Registrar el error (puedes usar error_log)
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        return false;
    }
}
// ========================================================================
// 5. FUNCIONES PARA LOGIN CON GOOGLE (opcional, para más adelante)
// ========================================================================

/**
 * Busca o crea un usuario a partir de los datos de Google.
 * Esta función se usará cuando implementemos el login con Google.
 *
 * @param array $googleData  Datos de Google: email, name, picture, etc.
 * @return array|false  Datos del usuario (id, full_name, email, role) o false si falla.
 */
function loginGoogle(array $googleData) {
    global $pdo;
    $email = $googleData['email'] ?? null;
    if (!$email) return false;

    // Buscar si el email ya está registrado
    $stmt = $pdo->prepare("SELECT id, full_name, email, role, picture FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Si existe, actualizar la foto si es necesario
        if (isset($googleData['picture']) && !$usuario['picture']) {
            $stmt = $pdo->prepare("UPDATE usuarios SET picture = ? WHERE id = ?");
            $stmt->execute([$googleData['picture'], $usuario['id']]);
            $usuario['picture'] = $googleData['picture'];
        }
        return $usuario;
    }

    // Si no existe, crear nuevo usuario (rol cliente por defecto)
    $fullName = $googleData['name'] ?? $googleData['email'];
    $username = explode('@', $email)[0]; // Usar parte del email como username
    // Verificar si el username ya existe, y si no, usar el email
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        $username = $email; // Usar todo el email como username
    }

    $sql = "INSERT INTO usuarios (username, full_name, email, role, password_hash, estado, picture)
            VALUES (?, ?, ?, 'cliente', NULL, true, ?) RETURNING id, full_name, email, role, picture";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $fullName, $email, $googleData['picture'] ?? null]);
    $nuevo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Guardar también en google_users para referencia
    if ($nuevo && isset($googleData['oauth_uid'])) {
        $stmt = $pdo->prepare("
            INSERT INTO google_users (user_id, oauth_provider, oauth_uid, name, email, picture)
            VALUES (?, 'google', ?, ?, ?, ?)
        ");
        $stmt->execute([
            $nuevo['id'],
            $googleData['oauth_uid'],
            $googleData['name'] ?? $googleData['email'],
            $email,
            $googleData['picture'] ?? null
        ]);
    }

    return $nuevo;
}

// ========================================================================
// 6. FUNCIONES PARA SOLICITUDES DE ROL (clientes que piden ascenso)
// ========================================================================

/**
 * Guarda una solicitud de cambio de rol.
 *
 * @param int $usuarioId
 * @param string $rolSolicitado  'ganadero', 'operador', 'administrador'
 * @param string $mensaje  (opcional) justificación
 * @return bool
 */
function solicitarCambioRol(int $usuarioId, string $rolSolicitado, string $mensaje = '') {
    global $pdo;
    $rolSolicitado = strtolower($rolSolicitado);
    // Validar que el rol sea permitido
    if (!in_array($rolSolicitado, ['ganadero', 'operador', 'administrador'])) {
        return false;
    }

    $sql = "INSERT INTO solicitudes_rol (usuario_id, rol_solicitado, mensaje)
            VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$usuarioId, $rolSolicitado, $mensaje]);
}

/**
 * Obtiene todas las solicitudes de rol pendientes.
 *
 * @return array
 */
function obtenerSolicitudesPendientes() {
    global $pdo;
    $stmt = $pdo->query("
        SELECT sr.*, u.full_name, u.email, u.role as rol_actual
        FROM solicitudes_rol sr
        JOIN usuarios u ON sr.usuario_id = u.id
        WHERE sr.estado = 'pendiente'
        ORDER BY sr.fecha_solicitud ASC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Aprueba o deniega una solicitud de rol.
 *
 * @param int $solicitudId
 * @param string $estado  'aprobado' o 'denegado'
 * @param int $adminId  ID del administrador que procesa
 * @return bool
 */
function procesarSolicitudRol(int $solicitudId, string $estado, int $adminId) {
    global $pdo;
    $estado = strtolower($estado);
    if (!in_array($estado, ['aprobado', 'denegado'])) {
        return false;
    }

    // Iniciar transacción
    $pdo->beginTransaction();
    try {
        // Obtener la solicitud
        $stmt = $pdo->prepare("SELECT usuario_id, rol_solicitado FROM solicitudes_rol WHERE id = ? AND estado = 'pendiente'");
        $stmt->execute([$solicitudId]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$solicitud) {
            throw new Exception("Solicitud no encontrada o ya procesada");
        }

        // Actualizar el estado de la solicitud
        $stmt = $pdo->prepare("
            UPDATE solicitudes_rol
            SET estado = ?, admin_id = ?, fecha_respuesta = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([$estado, $adminId, $solicitudId]);

        // Si se aprueba, actualizar el rol del usuario
        if ($estado === 'aprobado') {
            $stmt = $pdo->prepare("UPDATE usuarios SET role = ? WHERE id = ?");
            $stmt->execute([$solicitud['rol_solicitado'], $solicitud['usuario_id']]);
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error al procesar solicitud de rol: " . $e->getMessage());
        return false;
    }
}