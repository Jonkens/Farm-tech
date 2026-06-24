<?php
// herramientas/partos/certificado/generar.php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../src/helpers.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$partoId = (int) ($_GET['parto_id'] ?? 0);
if ($partoId <= 0) die('ID de parto no válido.');

$pdo = getDB();
$stmt = $pdo->prepare("
    SELECT p.*, 
           m.name as madre_nombre, m.tag as madre_tag, m.breed_id as madre_raza_id, at_m.name as madre_tipo,
           f.name as padre_nombre, f.tag as padre_tag, f.breed_id as padre_raza_id, at_f.name as padre_tipo,
           c.name as cria_nombre, c.tag as cria_tag, c.birth_date as cria_fecha_nac, c.breed_id as cria_raza_id, at_c.name as cria_tipo,
           rm.name as madre_raza, rf.name as padre_raza, rc.name as cria_raza
    FROM partos p
    LEFT JOIN animales m ON p.madre_id = m.id
    LEFT JOIN tipos_animal at_m ON m.animal_type_id = at_m.id
    LEFT JOIN razas rm ON m.breed_id = rm.id
    LEFT JOIN animales f ON p.padre_id = f.id
    LEFT JOIN tipos_animal at_f ON f.animal_type_id = at_f.id
    LEFT JOIN razas rf ON f.breed_id = rf.id
    LEFT JOIN animales c ON p.cria_id = c.id
    LEFT JOIN tipos_animal at_c ON c.animal_type_id = at_c.id
    LEFT JOIN razas rc ON c.breed_id = rc.id
    WHERE p.id = ?
");
$stmt->execute([$partoId]);
$parto = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$parto) die('Parto no encontrado.');

$finca = [
    'nombre'      => 'FarmTech',
    'responsable' => 'Oscar Francisco Palma',
    'ubicacion'   => 'Ahuachapán, El Salvador',
    'telefono'    => '+503 0000-0000',
    'registro'    => 'REG-2024-001',
];

$fechaEmision = date('d/m/Y');
$numeroCert = 'CERT-' . str_pad($parto['id'], 6, '0', STR_PAD_LEFT);
$sexoCria = ($parto['cria_tag'] && $parto['cria_tag'][0] === 'M') ? 'Macho' : 'Hembra';

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "DejaVu Sans", sans-serif; background: #fff; color: #1a1a1a; padding: 30px; }
        .certificado { border: 4px double #2d6a2d; padding: 30px; min-height: 700px; position: relative; }
        .header { text-align: center; border-bottom: 2px solid #2d6a2d; padding-bottom: 16px; margin-bottom: 20px; }
        .finca-nombre { font-size: 22px; font-weight: bold; color: #2d6a2d; letter-spacing: 1px; }
        .finca-datos { font-size: 11px; color: #555; margin-top: 4px; }
        .titulo-cert { font-size: 18px; font-weight: bold; color: #1a3d1a; margin-top: 14px; text-transform: uppercase; letter-spacing: 2px; }
        .subtitulo { font-size: 12px; color: #666; margin-top: 4px; }
        .meta-row { display: flex; justify-content: space-between; font-size: 11px; color: #555; margin-bottom: 20px; background: #f4faf4; padding: 8px 12px; border-radius: 4px; }
        .seccion { margin-bottom: 18px; }
        .seccion-titulo { font-size: 12px; font-weight: bold; color: #2d6a2d; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #b7ddb7; padding-bottom: 4px; margin-bottom: 10px; }
        .grid-2 { display: table; width: 100%; }
        .grid-2 .col { display: table-cell; width: 50%; padding: 0 6px 0 0; }
        .campo { margin-bottom: 8px; }
        .campo-label { font-size: 10px; color: #777; text-transform: uppercase; letter-spacing: 0.5px; }
        .campo-valor { font-size: 13px; font-weight: bold; color: #1a1a1a; border-bottom: 1px solid #ddd; padding-bottom: 2px; margin-top: 2px; }
        .sello-area { margin-top: 30px; display: table; width: 100%; }
        .firma-col { display: table-cell; width: 50%; text-align: center; padding: 0 20px; }
        .firma-linea { border-top: 1px solid #333; margin-top: 40px; padding-top: 6px; font-size: 11px; color: #444; }
        .footer { position: absolute; bottom: 20px; left: 30px; right: 30px; text-align: center; font-size: 10px; color: #aaa; border-top: 1px solid #eee; padding-top: 8px; }
        .badge { display: inline-block; background: #2d6a2d; color: #fff; padding: 3px 10px; border-radius: 20px; font-size: 11px; margin-top: 6px; }
    </style>
</head>
<body>
<div class="certificado">
    <div class="header">
        <div class="finca-nombre">' . htmlspecialchars($finca['nombre']) . '</div>
        <div class="finca-datos">' . htmlspecialchars($finca['ubicacion']) . ' &nbsp;|&nbsp; Tel: ' . htmlspecialchars($finca['telefono']) . ' &nbsp;|&nbsp; Reg: ' . htmlspecialchars($finca['registro']) . '</div>
        <div class="titulo-cert">Certificado de Nacimiento Animal</div>
        <div class="subtitulo">Documento oficial de registro genealógico y reproductivo</div>
    </div>
    <div class="meta-row"><span><strong>N° Certificado:</strong> ' . $numeroCert . '</span><span><strong>Fecha de emisión:</strong> ' . $fechaEmision . '</span></div>

    <div class="seccion">
        <div class="seccion-titulo"> Datos del Animal Nacido</div>
        <div class="grid-2">
            <div class="col"><div class="campo"><div class="campo-label">Nombre / Identificación</div><div class="campo-valor">' . htmlspecialchars($parto['cria_nombre'] ?? '-') . '</div></div><div class="campo"><div class="campo-label">Arete / Código</div><div class="campo-valor">' . htmlspecialchars($parto['cria_tag'] ?? '-') . '</div></div><div class="campo"><div class="campo-label">Tipo de Ganado</div><div class="campo-valor">' . htmlspecialchars($parto['cria_tipo'] ?? '-') . '</div></div></div>
            <div class="col"><div class="campo"><div class="campo-label">Sexo</div><div class="campo-valor">' . $sexoCria . '</div></div><div class="campo"><div class="campo-label">Raza</div><div class="campo-valor">' . htmlspecialchars($parto['cria_raza'] ?? '-') . '</div></div><div class="campo"><div class="campo-label">Fecha de Nacimiento</div><div class="campo-valor">' . htmlspecialchars($parto['fecha_parto'] ?? '-') . '</div></div></div>
        </div>
        <div class="campo" style="margin-top:8px;"><div class="campo-label">Peso al Nacer</div><div class="campo-valor">' . htmlspecialchars($parto['peso_kg'] ?? '-') . ' kg</div></div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo"> Datos de la Madre</div>
        <div class="grid-2">
            <div class="col"><div class="campo"><div class="campo-label">Nombre</div><div class="campo-valor">' . htmlspecialchars($parto['madre_nombre'] ?? '-') . '</div></div><div class="campo"><div class="campo-label">Arete</div><div class="campo-valor">' . htmlspecialchars($parto['madre_tag'] ?? '-') . '</div></div></div>
            <div class="col"><div class="campo"><div class="campo-label">Raza</div><div class="campo-valor">' . htmlspecialchars($parto['madre_raza'] ?? '-') . '</div></div><div class="campo"><div class="campo-label">Tipo</div><div class="campo-valor">' . htmlspecialchars($parto['madre_tipo'] ?? '-') . '</div></div></div>
        </div>
    </div>

    <div class="seccion">
        <div class="seccion-titulo"> Datos del Padre</div>
        <div class="grid-2">
            <div class="col"><div class="campo"><div class="campo-label">Nombre</div><div class="campo-valor">' . htmlspecialchars($parto['padre_nombre'] ?? 'No registrado') . '</div></div><div class="campo"><div class="campo-label">Arete</div><div class="campo-valor">' . htmlspecialchars($parto['padre_tag'] ?? '-') . '</div></div></div>
            <div class="col"><div class="campo"><div class="campo-label">Raza</div><div class="campo-valor">' . htmlspecialchars($parto['padre_raza'] ?? '-') . '</div></div><div class="campo"><div class="campo-label">Tipo</div><div class="campo-valor">' . htmlspecialchars($parto['padre_tipo'] ?? '-') . '</div></div></div>
        </div>
    </div>

    ' . (!empty($parto['notas']) ? '<div class="seccion"><div class="seccion-titulo"> Notas</div><div style="font-size:12px; color:#444; padding: 8px; background:#f9f9f9; border-radius:4px;">' . htmlspecialchars($parto['notas']) . '</div></div>' : '') . '

    <div class="sello-area">
        <div class="firma-col"><div class="firma-linea"><strong>' . htmlspecialchars($finca['responsable']) . '</strong><br>Responsable de la Finca</div></div>
        <div class="firma-col"><div class="firma-linea"><strong>Médico Veterinario / Encargado</strong><br>Firma y Sello</div></div>
    </div>
    <div class="footer">Documento generado por FarmTech &nbsp;|&nbsp; ' . $fechaEmision . '<br><span class="badge">' . $numeroCert . '</span></div>
</div>
</body>
</html>';

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('certificado_nacimiento_' . $numeroCert . '.pdf', ['Attachment' => true]);