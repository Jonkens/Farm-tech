<?php
// herramientas/partos/procesos/eliminar_evento.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../../includes/query_helper.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id > 0) {
    eliminarEvento($id);
}

header('Location: ../index.php?pagina=registrar-evento&eliminado=1');
exit;