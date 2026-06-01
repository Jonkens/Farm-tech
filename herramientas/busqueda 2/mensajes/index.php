<?php
// Incluir conexión a la base de datos
require_once __DIR__ . '/../../conexion.php';
require_once __DIR__ . '/../../includes/query_helper.php';

// Procesar acciones POST y redirigir con mensaje
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Importar mensajes desde contact_messages
    if (isset($_POST['action']) && $_POST['action'] === 'importar') {
        $sql = "INSERT INTO messages_admin (full_name, email, phone, message, status, created_at)
                SELECT full_name, email, phone, message, status, created_at
                FROM contact_messages
                WHERE NOT EXISTS (
                    SELECT 1 FROM messages_admin
                    WHERE messages_admin.email = contact_messages.email
                    AND messages_admin.message = contact_messages.message
                )";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $count = $stmt->rowCount();
        $mensaje = "Se importaron $count mensajes nuevos.";
    }

    // Cambiar estado
    if (isset($_POST['action']) && $_POST['action'] === 'cambiar_estado' && isset($_POST['id']) && isset($_POST['estado'])) {
        $id = intval($_POST['id']);
        $estado = $_POST['estado'];
        $stmt = $pdo->prepare("UPDATE messages_admin SET status = :estado WHERE id = :id");
        $stmt->execute([':estado' => $estado, ':id' => $id]);
        $mensaje = "Estado actualizado correctamente.";
    }

    // Responder mensaje
    if (isset($_POST['action']) && $_POST['action'] === 'responder' && isset($_POST['id']) && isset($_POST['respuesta'])) {
        $id = intval($_POST['id']);
        $respuesta = trim($_POST['respuesta']);
        if (!empty($respuesta)) {
            $stmt = $pdo->prepare("UPDATE messages_admin SET admin_response = :respuesta, status = 'respondido', responded_at = NOW() WHERE id = :id");
            $stmt->execute([':respuesta' => $respuesta, ':id' => $id]);
            $mensaje = "Respuesta enviada y mensaje marcado como respondido.";
        } else {
            $error = "La respuesta no puede estar vacía.";
        }
    }

    // Eliminar mensaje
    if (isset($_POST['action']) && $_POST['action'] === 'eliminar' && isset($_POST['id'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM messages_admin WHERE id = :id");
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
$sql = "SELECT * FROM messages_admin";
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
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM messages_admin WHERE status = :status");
    $stmt->execute([':status' => $est]);
    $counts[$est] = $stmt->fetchColumn();
}
$total = array_sum($counts);

// Verificar si hay mensajes sin importar
$stmt = $pdo->query("SELECT COUNT(*) FROM contact_messages cm
                     WHERE NOT EXISTS (SELECT 1 FROM messages_admin ma
                                       WHERE ma.email = cm.email AND ma.message = cm.message)");
$pendientes_importar = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Mensajes - Ganadería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📬 Buzón de mensajes</h1>
        <div class="flex gap-2">
            <?php if ($pendientes_importar > 0): ?>
                <form method="POST" onsubmit="return confirm('¿Importar <?= $pendientes_importar ?> mensajes?')">
                    <input type="hidden" name="action" value="importar">
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg shadow">
                        <i class="fa-regular fa-cloud-arrow-up"></i> Importar (<?= $pendientes_importar ?>)
                    </button>
                </form>
            <?php endif; ?>
            
        </div>
    </div>

    <?php if ($mensaje): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg border-l-4 border-green-500 flex justify-between items-center">
            <span><i class="fa-regular fa-circle-check"></i> <?= $mensaje ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-green-600">&times;</button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg border-l-4 border-red-500 flex justify-between items-center">
            <span><i class="fa-regular fa-circle-exclamation"></i> <?= $error ?></span>
            <button onclick="this.parentElement.style.display='none'" class="text-red-600">&times;</button>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap gap-2 mb-6 border-b border-gray-200 pb-2">
        <a href="?filtro=todos" class="px-4 py-2 rounded-t-lg <?= $filtro == 'todos' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">Todos (<?= $total ?>)</a>
        <?php foreach ($estados as $est): ?>
            <a href="?filtro=<?= $est ?>" class="px-4 py-2 rounded-t-lg <?= $filtro == $est ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>"><?= ucfirst($est) ?> (<?= $counts[$est] ?>)</a>
        <?php endforeach; ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remitente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mensaje</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Respuesta</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($mensajes)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-regular fa-inbox text-4xl mb-2 block"></i>
                                No hay mensajes en esta categoría.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($mensajes as $msg): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($msg['created_at'])) ?></td>
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
                                    $badgeClass = match($msg['status']) {
                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'leído' => 'bg-blue-100 text-blue-800',
                                        'respondido' => 'bg-green-100 text-green-800',
                                        'archivado' => 'bg-gray-100 text-gray-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                    ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badgeClass ?>">
                                        <?= ucfirst($msg['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    <?php if (!empty($msg['admin_response'])): ?>
                                        <i class="fa-regular fa-reply-all text-green-500 mr-1"></i>
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
                                            <select name="estado" onchange="this.form.submit()" class="text-xs border rounded px-2 py-1 bg-white">
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
<div id="modalRespuesta" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl w-full max-w-2xl p-6 relative shadow-2xl">
        <button onclick="cerrarModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        <h3 class="text-xl font-bold mb-4 flex items-center gap-2"><i class="fa-regular fa-message"></i> Responder mensaje</h3>
        <div class="mb-4 border-b pb-3">
            <p><span class="font-semibold">De:</span> <span id="modalNombre"></span> (<span id="modalEmail"></span>)</p>
            <p><span class="font-semibold">Teléfono:</span> <span id="modalTelefono"></span></p>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Mensaje original:</label>
            <div id="modalMensaje" class="bg-gray-50 p-3 rounded-lg border text-gray-700 whitespace-pre-wrap"></div>
        </div>
        <form method="POST" id="formResponder">
            <input type="hidden" name="action" value="responder">
            <input type="hidden" name="id" id="modalId">
            <div class="mb-3">
                <label class="block font-semibold mb-1">Respuesta del administrador:</label>
                <textarea name="respuesta" id="modalRespuestaTexto" rows="4" class="w-full border rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Escribe aquí tu respuesta..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="cerrarModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">Enviar respuesta</button>
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

    // Cerrar modal si se hace clic fuera del contenido
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('modalRespuesta');
        if (event.target === modal) {
            cerrarModal();
        }
    });
</script>
</body>
</html>