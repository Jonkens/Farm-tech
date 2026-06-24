<?php
// herramientas/partos/procesos/descartar_alerta.php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../../../includes/query_helper.php';

$eventoId = (int) ($_GET['id'] ?? $_POST['descartar_alerta'] ?? 0);

if ($eventoId > 0) {
    eliminarEvento($eventoId);
}

// Redirigir al dashboard (o a la página desde donde se llamó)
$pagina = $_GET['pagina'] ?? 'dashboard';
header("Location: ../index.php?pagina={$pagina}&alerta_descartada=1");
exit;