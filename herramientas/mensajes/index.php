<?php
// Incluir conexión a la base de datos
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../includes/query_helper.php';

// =====================================================
// Verificar y crear columnas adicionales si no existen
// =====================================================
try {
    // Verificar si existe columna admin_response
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns 
                         WHERE table_name='mensajes_contacto' AND column_name='admin_response'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN admin_response TEXT");
    }
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns 
                         WHERE table_name='mensajes_contacto' AND column_name='responded_at'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE mensajes_contacto ADD COLUMN responded_at TIMESTAMP NULL");
    }
} catch (PDOException $e) {
    // Si hay error (ej. PostgreSQL no usa information_schema igual), se intenta con método alternativo
    // Se asume que las columnas ya existen o se omiten.
}

// Procesar acciones POST
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cambiar estado
    if (isset($_POST['action']) && $_POST['action'] === 'cambiar_estado' && isset($_POST['id']) && isset($_POST['estado'])) {
        $id = intval($_POST['id']);
        $estado = $_POST['estado'];
        $stmt = $pdo->prepare("UPDATE mensajes_contacto SET status = :estado WHERE id = :id");
        $stmt->execute([':estado' => $estado, ':id' => $id]);
        $mensaje = "Estado actualizado correctamente.";
    }

    // Responder mensaje
    if (isset($_POST['action']) && $_POST['action'] === 'responder' && isset($_POST['id']) && isset($_POST['respuesta'])) {
        $id = intval($_POST['id']);
        $respuesta = trim($_POST['respuesta']);
        if (!empty($respuesta)) {
            $stmt = $pdo->prepare("UPDATE mensajes_contacto SET admin_response = :respuesta, status = 'respondido', responded_at = NOW() WHERE id = :id");
            $stmt->execute([':respuesta' => $respuesta, ':id' => $id]);
            $mensaje = "Respuesta enviada y mensaje marcado como respondido.";
        } else {
            $error = "La respuesta no puede estar vacía.";
        }
    }

    // Eliminar mensaje
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $mensaje = "Mensaje eliminado permanentemente.";
    }

    // Redirigir para evitar reenvío y mostrar mensaje
    if ($mensaje) {
        header("Location: index.php?mensaje=" . urlencode($mensaje));
        exit;
    }
    if ($error) {
        header("Location: index.php?error=" . urlencode($error));
        exit;
    }
}

// Mostrar mensajes desde la URL
if (isset($_GET['mensaje'])) {
    $mensaje = htmlspecialchars($_GET['mensaje']);
}
if (isset($_GET['error'])) {
    $error = htmlspecialchars($_GET['error']);
}

// Obtener mensajes según filtro
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
$sql = "SELECT * FROM mensajes_contacto";
if ($filtro !== 'todos') {
    $sql .= " WHERE status = :status";
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
if ($filtro !== 'todos') {
    $stmt->execute([':status' => $filtro]);
} else {
    $stmt->execute();
}
$mensajes = $stmt->fetchAll();

// Contar mensajes por estado
$estados = ['pendiente', 'leído', 'respondido', 'archivado'];
$counts = [];
foreach ($estados as $est) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM mensajes_contacto WHERE status = :status");
    $stmt->execute([':status' => $est]);
    $counts[$est] = $stmt->fetchColumn();
}
$total = array_sum($counts);

// Ya no es necesaria la importación porque trabajamos directamente con mensajes_contacto
$pendientes_importar = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Mensajes | Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #1a4d2a; /* verde oscuro base */
            background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
                              repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
            background-size: 30px 30px, 12px 12px;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .card {
            background: rgba(255, 251, 240, 0.97);
            backdrop-filter: blur(8px);
            border-radius: 1.5rem;
            border: 1px solid #e2d4b5;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.25);
        }
        .btn-primary {
            background-color: #2d6a4f;
            color: white;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #1f4d38;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #b87c4f;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #9a623b;
        }
        .tab-active {
            background-color: #2d6a4f;
            color: #fef5e6;
            border-bottom: 3px solid #f7b32b;
        }
        .tab-inactive {
            background-color: #fef5e6;
            color: #5a3e1b;
        }
        .tab-inactive:hover {
            background-color: #f0e5d2;
        }
        .badge-pendiente { background-color: #fef9c3; color: #92400e; }
        .badge-leído { background-color: #dbeafe; color: #1d4ed8; }
        .badge-respondido { background-color: #dcfce7; color: #15803d; }
        .badge-archivado { background-color: #f3e8ff; color: #7e22ce; }
        .table-ganado thead {
            background: #2d6a4f;
            color: #fef5e6;
        }
        .table-ganado tbody tr:hover {
            background-color: #fef1df;
        }
        .modal-content {
            background: #fffef7;
            border-radius: 1.25rem;
            border: 1px solid #ecdbaa;
        }
    </style>
</head>
<body>
<div class="max-w-7xl mx-auto">

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-[#f9eec1] flex items-center gap-3 drop-shadow-sm">
            <i class="fa-regular fa-envelope text-[#f7b32b]"></i> Buzón de Mensajes
        </h1>
        <p class="text-[#e2d4b5] mt-1 text-sm">Gestión de consultas y respuestas</p>
    </div>

    <?php if ($mensaje): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-xl border border-green-300 flex justify-between items-center">
            <span><i class="fa-regular fa-circle-check"></i> <?= $mensaje ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-green-600">&times;</button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-xl border border-red-300 flex justify-between items-center">
            <span><i class="fa-regular fa-circle-exclamation"></i> <?= $error ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-red-600">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Filtros con diseño de pestañas -->
    <div class="flex flex-wrap gap-2 mb-6 pb-1 border-b border-[#ecdbaa]/40">
        <a href="?filtro=todos" class="px-5 py-2 rounded-t-lg font-medium transition <?= $filtro == 'todos' ? 'tab-active' : 'tab-inactive' ?>">Todos (<?= $total ?>)</a>
        <?php foreach ($estados as $est): ?>
            <a href="?filtro=<?= $est ?>" class="px-5 py-2 rounded-t-lg font-medium transition <?= $filtro == $est ? 'tab-active' : 'tab-inactive' ?>"><?= ucfirst($est) ?> (<?= $counts[$est] ?>)</a>
        <?php endforeach; ?>
    </div>

    <!-- Tabla de mensajes -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#f0e5d2] table-ganado">
                <thead class="bg-[#2d6a4f] text-[#fef5e6]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Remitente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mensaje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Respuesta</th>
                        <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0e5d2]">
                    <?php if (empty($mensajes)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#8b6946]">
                                <i class="fa-regular fa-inbox text-4xl mb-2 block"></i>
                                No hay mensajes en esta categoría.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mensajes as $msg): ?>
                            <tr class="hover:bg-[#fef1df] transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($msg['full_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($msg['email']) ?></div>
                                    <?php if (!empty($msg['phone'])): ?>
                                        <div class="text-xs text-gray-400">📞 <?= htmlspecialchars($msg['phone']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 max-w-md truncate" title="<?= htmlspecialchars($msg['message']) ?>">
                                    <?= htmlspecialchars(mb_substr($msg['message'], 0, 80)) . (strlen($msg['message']) > 80 ? '…' : '') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $badgeMap = [
                                        'pendiente' => 'badge-pendiente',
                                        'leído' => 'badge-leído',
                                        'respondido' => 'badge-respondido',
                                        'archivado' => 'badge-archivado'
                                    ];
                                    $badgeClass = $badgeMap[$msg['status']] ?? 'badge-pendiente';
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badgeClass ?>">
                                        <?= ucfirst($msg['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    <?php if (!empty($msg['admin_response'])): ?>
                                        <i class="fa-regular fa-reply-all text-green-600 mr-1"></i>
                                        <?= htmlspecialchars(mb_substr($msg['admin_response'], 0, 50)) . (strlen($msg['admin_response']) > 50 ? '…' : '') ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">Sin respuesta</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex justify-center gap-2">
                                        <button onclick='verMensaje(<?= $msg['id'] ?>, <?= json_encode($msg['full_name']) ?>, <?= json_encode($msg['email']) ?>, <?= json_encode($msg['phone']) ?>, <?= json_encode($msg['message']) ?>, <?= json_encode($msg['admin_response']) ?>)' class="text-blue-600 hover:text-blue-800" title="Ver / Responder">
                                            <i class="fa-regular fa-eye text-lg"></i>
                                        </button>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="action" value="cambiar_estado">
                                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                            <select name="estado" onchange="this.form.submit()" class="text-xs border border-[#ecdbaa] rounded px-2 py-1 bg-[#fffef7]">
                                                <option value="pendiente" <?= $msg['status'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                                <option value="leído" <?= $msg['status'] == 'leído' ? 'selected' : '' ?>>Leído</option>
                                                <option value="respondido" <?= $msg['status'] == 'respondido' ? 'selected' : '' ?>>Respondido</option>
                                                <option value="archivado" <?= $msg['status'] == 'archivado' ? 'selected' : '' ?>>Archivado</option>
                                            </select>
                                        </form>
                                        <form method="POST" onsubmit="return confirm('¿Eliminar este mensaje permanentemente?')">
                                            <input type="hidden" name="action" value="eliminar">
                                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <i class="fa-regular fa-trash-can text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para responder -->
<div id="modalRespuesta" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
    <div class="modal-content w-full max-w-2xl p-6 relative shadow-2xl">
        <button onclick="cerrarModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        <h3 class="text-xl font-bold text-[#4b2e1a] mb-4 flex items-center gap-2"><i class="fa-regular fa-message"></i> Responder mensaje</h3>
        <div class="mb-4 border-b border-[#ecdbaa] pb-3">
            <p><span class="font-semibold text-gray-700">De:</span> <span id="modalNombre" class="text-gray-900"></span> (<span id="modalEmail" class="text-gray-600"></span>)</p>
            <p><span class="font-semibold text-gray-700">Teléfono:</span> <span id="modalTelefono" class="text-gray-600"></span></p>
        </div>
        <div class="mb-4">
            <label class="block font-semibold text-gray-700 mb-1">Mensaje original:</label>
            <div id="modalMensaje" class="bg-[#fef5e6] p-3 rounded-lg border border-[#ecdbaa] text-gray-700 whitespace-pre-wrap"></div>
        </div>
        <form method="POST" id="formResponder">
            <input type="hidden" name="action" value="responder">
            <input type="hidden" name="id" id="modalId">
            <div class="mb-3">
                <label class="block font-semibold text-gray-700 mb-1">Respuesta del administrador:</label>
                <textarea name="respuesta" id="modalRespuestaTexto" rows="4" class="w-full border border-[#ecdbaa] rounded-lg p-2 bg-[#fffef7] focus:ring-2 focus:ring-[#2d6a4f] focus:border-transparent" placeholder="Escribe aquí tu respuesta..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="cerrarModal()" class="px-4 py-2 border border-[#d4a373] rounded-lg hover:bg-[#fef5e6] transition">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-[#2d6a4f] hover:bg-[#1f4d38] text-white rounded-lg font-semibold transition">Enviar respuesta</button>
            </div>
        </form>
    </div>
</div>

<script>
    function verMensaje(id, nombre, email, telefono, mensaje, respuestaActual) {
        document.getElementById('modalId').value = id;
        document.getElementById('modalNombre').innerText = nombre;
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalTelefono').innerText = telefono || 'No especificado';
        document.getElementById('modalMensaje').innerText = mensaje;
        document.getElementById('modalRespuestaTexto').value = respuestaActual || '';
        document.getElementById('modalRespuesta').classList.remove('hidden');
        document.getElementById('modalRespuestaTexto').focus();
    }

    function cerrarModal() {
        document.getElementById('modalRespuesta').classList.add('hidden');
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('modalRespuesta');
        if (event.target === modal) {
            cerrarModal();
        }
    });
</script>
</body>
</html>