<?php
/**
 * VetFarm — Generador de PDF para informes médicos veterinarios
 * Compatible con Windows (XAMPP) + wkhtmltopdf
 */

function generatePDF(array $data): array {
    // Crear directorio pdfs si no existe
    $pdfDir = __DIR__ . DIRECTORY_SEPARATOR . 'pdfs';
    if (!is_dir($pdfDir)) {
        mkdir($pdfDir, 0755, true);
    }

    $nombreArchivo = 'informe_' . preg_replace('/[^a-zA-Z0-9]/', '_', $data['numero_informe']) . '_' . date('His');
    $pdfPath  = $pdfDir . DIRECTORY_SEPARATOR . $nombreArchivo . '.pdf';
    $htmlPath = $pdfDir . DIRECTORY_SEPARATOR . $nombreArchivo . '.html';

    // ── Detectar wkhtmltopdf en Windows y Linux/Mac ──────────────────────────
    $wkBin = detectarWkhtmltopdf();

    if ($wkBin) {
        // Generar el HTML del informe
        $html = construirHTML($data);
        file_put_contents($htmlPath, $html);

        // Convertir a PDF con wkhtmltopdf
        $resultado = convertirConWkhtmltopdf($wkBin, $htmlPath, $pdfPath);

        // Borrar el HTML temporal
        @unlink($htmlPath);

        if ($resultado['success']) {
            return ['success' => true, 'path' => $pdfPath, 'type' => 'pdf'];
        }
        // Si falla wkhtmltopdf, caer al fallback HTML
    }

    // ── Fallback: HTML imprimible desde el navegador ─────────────────────────
    $html = construirHTML($data);
    file_put_contents($htmlPath, $html);
    return ['success' => true, 'path' => $htmlPath, 'type' => 'html'];
}

// ─────────────────────────────────────────────────────────────────────────────
// Detectar wkhtmltopdf automáticamente en Windows, Linux y Mac
// ─────────────────────────────────────────────────────────────────────────────
function detectarWkhtmltopdf(): ?string {
    // Rutas comunes en Windows
    $rutasWindows = [
        'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
        'C:\\Program Files (x86)\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
        'C:\\wkhtmltopdf\\bin\\wkhtmltopdf.exe',
    ];

    foreach ($rutasWindows as $ruta) {
        if (file_exists($ruta)) {
            return $ruta;
        }
    }

    // En Linux/Mac intentar con which
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        $resultado = shell_exec('which wkhtmltopdf 2>/dev/null');
        if (!empty(trim($resultado ?? ''))) {
            return trim($resultado);
        }
    }

    return null; // No encontrado
}

// ─────────────────────────────────────────────────────────────────────────────
// Ejecutar wkhtmltopdf y devolver resultado
// ─────────────────────────────────────────────────────────────────────────────
function convertirConWkhtmltopdf(string $bin, string $htmlPath, string $pdfPath): array {
    $cmd = sprintf(
        '%s --page-size A4 --margin-top 10mm --margin-bottom 10mm --margin-left 10mm --margin-right 10mm --enable-local-file-access --load-error-handling ignore --quiet %s %s',
        escapeshellarg($bin),
        escapeshellarg($htmlPath),
        escapeshellarg($pdfPath)
    );

    // En Windows redirigir errores a NUL, en Linux a /dev/null
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $cmd .= ' 2>NUL';
    } else {
        $cmd .= ' 2>/dev/null';
    }

    exec($cmd, $output, $codigoRetorno);

    if ($codigoRetorno === 0 && file_exists($pdfPath) && filesize($pdfPath) > 0) {
        return ['success' => true];
    }

    return ['success' => false, 'error' => 'wkhtmltopdf falló (código ' . $codigoRetorno . ')'];
}

// ─────────────────────────────────────────────────────────────────────────────
// Construir el HTML completo del informe (PDF o fallback imprimible)
// ─────────────────────────────────────────────────────────────────────────────
function construirHTML(array $d): string {
    $fecha          = date('d/m/Y', strtotime($d['fecha']));
    $proximoControl = !empty($d['proximo_control'])
        ? date('d/m/Y', strtotime($d['proximo_control']))
        : 'No programado';

    $estados = [
        'critico' => ['color' => '#dc2626', 'bg' => '#fef2f2', 'texto' => 'CRITICO'],
        'grave'   => ['color' => '#d97706', 'bg' => '#fffbeb', 'texto' => 'GRAVE'],
        'regular' => ['color' => '#ca8a04', 'bg' => '#fefce8', 'texto' => 'REGULAR'],
        'estable' => ['color' => '#16a34a', 'bg' => '#f0fdf4', 'texto' => 'ESTABLE'],
        'bueno'   => ['color' => '#059669', 'bg' => '#ecfdf5', 'texto' => 'BUENO'],
    ];
    $est = $estados[$d['estado']] ?? $estados['estable'];

    $emojis = [
        'Bovino'  => '[BOVINO]',
        'Porcino' => '[PORCINO]',
        'Ovino'   => '[OVINO]',
        'Caprino' => '[CAPRINO]',
        'Equino'  => '[EQUINO]',
        'Aviar'   => '[AVIAR]',
        'Conejo'  => '[CONEJO]',
        'Otro'    => '[ANIMAL]',
    ];
    $tipoLabel = $emojis[$d['tipo_animal']] ?? '[ANIMAL]';

    // Tabla de medicamentos
    $medRows = '';
    $hasMeds = false;
    if (!empty($d['medicamentos'])) {
        foreach ($d['medicamentos'] as $i => $med) {
            if (empty($med['nombre'])) continue;
            $hasMeds = true;
            $bg = ($i % 2 === 0) ? '#f8fffe' : '#ffffff';
            $medRows .= '<tr style="background:' . $bg . ';">'
                . '<td style="padding:9px 14px;border-bottom:1px solid #e6f4ec;">' . e($med['nombre']) . '</td>'
                . '<td style="padding:9px 14px;border-bottom:1px solid #e6f4ec;font-weight:600;color:#1a6b3c;">' . e($med['dosis'] ?? '') . '</td>'
                . '<td style="padding:9px 14px;border-bottom:1px solid #e6f4ec;">' . e($med['frecuencia'] ?? '') . '</td>'
                . '</tr>';
        }
    }
    if (!$hasMeds) {
        $medRows = '<tr><td colspan="3" style="padding:12px 14px;color:#718096;font-style:italic;">No se registraron medicamentos</td></tr>';
    }

    // Badges del animal
    $badges = '';
    if (!empty($d['edad']))   $badges .= '<span class="badge">Edad: ' . e($d['edad']) . '</span>';
    if (!empty($d['sexo']))   $badges .= '<span class="badge">Sexo: ' . e($d['sexo']) . '</span>';
    if (!empty($d['peso']))   $badges .= '<span class="badge">Peso: ' . e($d['peso']) . ' kg</span>';

    // Vitales (solo si hay al menos uno)
    $vitales = '';
    if (!empty($d['temperatura']) || !empty($d['frecuencia_card']) || !empty($d['frecuencia_resp'])) {
        $vitales = '
        <div class="vitals">
            <div class="vital-box">
                <div class="vital-label">TEMPERATURA</div>
                <div class="vital-value">' . e($d['temperatura'] ?: '—') . '</div>
            </div>
            <div class="vital-box">
                <div class="vital-label">FREC. CARDIACA</div>
                <div class="vital-value">' . e($d['frecuencia_card'] ?: '—') . '</div>
            </div>
            <div class="vital-box">
                <div class="vital-label">FREC. RESPIRATORIA</div>
                <div class="vital-value">' . e($d['frecuencia_resp'] ?: '—') . '</div>
            </div>
        </div>';
    }

    // Secciones opcionales
    $secMotivo     = seccion('MOTIVO DE CONSULTA', $d['motivo_consulta'] ?? '');
    $secSintomas   = seccion('SINTOMAS OBSERVADOS', $d['sintomas'] ?? '');
    $secTratam     = seccion('TRATAMIENTO INDICADO', $d['tratamiento'] ?? '');
    $secObserv     = seccion('OBSERVACIONES Y RECOMENDACIONES', $d['observaciones'] ?? '');

    // Info de veterinario
    $infoVet  = infoRow('Nombre', $d['veterinario']);
    $infoVet .= !empty($d['clinica'])     ? infoRow('Clinica', $d['clinica']) : '';

    // Info de granja
    $infoGranja  = !empty($d['granja'])      ? infoRow('Granja', $d['granja']) : '';
    $infoGranja .= !empty($d['propietario']) ? infoRow('Propietario', $d['propietario']) : '';
    $infoGranja .= !empty($d['direccion'])   ? infoRow('Direccion', $d['direccion']) : '';

    $animalNombre = e($d['tipo_animal']) . (!empty($d['raza']) ? ' &mdash; ' . e($d['raza']) : '');
    $animalId     = !empty($d['identificacion']) ? e($d['identificacion']) : 'Sin identificacion';

    $numInforme = e($d['numero_informe']);
    $veterinario = e($d['veterinario']);

    return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Informe Medico - {$numInforme}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    color: #2d3748;
    background: #f5f5f5;
    padding: 20px;
}
.page {
    background: white;
    max-width: 760px;
    margin: 0 auto;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.10);
}

/* ── Header ── */
.header {
    background: #1a6b3c;
    padding: 28px 40px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.logo-box { display: flex; align-items: center; gap: 14px; }
.logo-icon {
    width: 50px; height: 50px;
    background: rgba(255,255,255,0.18);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px;
    font-weight: 700;
    color: white;
    letter-spacing: -1px;
}
.logo-nombre { font-size: 26px; font-weight: 700; letter-spacing: -0.5px; }
.logo-sub    { font-size: 11px; color: rgba(255,255,255,0.65); margin-top: 2px; }
.header-info { text-align: right; }
.informe-num  { font-size: 20px; font-weight: 700; }
.informe-fecha{ font-size: 12px; color: rgba(255,255,255,0.70); margin-top: 3px; }

/* Barra decorativa */
.deco-bar { height: 5px; background: linear-gradient(90deg, #2d9e5f 0%, #8bc34a 55%, #ffc107 100%); }

/* ── Content ── */
.content { padding: 32px 40px; }

/* Info cards (vet + granja) */
.info-grid { display: flex; gap: 16px; margin-bottom: 24px; }
.info-card {
    flex: 1;
    border: 1.5px solid #e6f4ec;
    border-radius: 10px;
    padding: 16px 18px;
    background: #fafffe;
}
.info-card-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.09em; color: #2d9e5f;
    padding-bottom: 8px; border-bottom: 1.5px solid #e6f4ec; margin-bottom: 12px;
}
.info-row-item { display: flex; gap: 6px; margin-bottom: 6px; }
.info-key { font-size: 11px; color: #718096; font-weight: 700; min-width: 78px; }
.info-val { font-size: 12px; color: #2d3748; }

/* Animal banner */
.animal-banner {
    background: #f0faf5;
    border: 1.5px solid #c6e8d5;
    border-radius: 10px;
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 24px;
}
.animal-tipo-label {
    background: #1a6b3c;
    color: white;
    font-size: 11px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 6px;
    letter-spacing: 0.04em;
    white-space: nowrap;
}
.animal-info { flex: 1; }
.animal-nombre { font-size: 17px; font-weight: 700; color: #1a6b3c; margin-bottom: 3px; }
.animal-id     { font-size: 12px; color: #718096; margin-bottom: 8px; }
.badges { display: flex; gap: 7px; flex-wrap: wrap; }
.badge {
    display: inline-block;
    padding: 3px 11px;
    border-radius: 100px;
    font-size: 11px; font-weight: 700;
    background: white; border: 1.5px solid #c6e8d5; color: #1a6b3c;
}
.estado-tag {
    padding: 7px 18px;
    border-radius: 100px;
    font-size: 12px; font-weight: 700;
    background: {$est['bg']};
    color: {$est['color']};
    border: 2px solid {$est['color']};
    white-space: nowrap;
}

/* Vitales */
.vitals { display: flex; gap: 12px; margin-bottom: 24px; }
.vital-box {
    flex: 1;
    background: #f0faf5;
    border: 1.5px solid #c6e8d5;
    border-radius: 10px;
    padding: 14px;
    text-align: center;
}
.vital-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; color: #718096; margin-bottom: 5px; }
.vital-value { font-size: 18px; font-weight: 700; color: #1a6b3c; }

/* Secciones clinicas */
.section { margin-bottom: 20px; }
.section-title {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.09em; color: #1a6b3c;
    padding-bottom: 7px; border-bottom: 2px solid #e6f4ec; margin-bottom: 10px;
}
.section-text { font-size: 13px; color: #4a5568; line-height: 1.65; }

/* Diagnostico destacado */
.dx-box {
    background: #f0faf5;
    border-left: 4px solid #2d9e5f;
    border-radius: 0 8px 8px 0;
    padding: 14px 18px;
}
.dx-text { font-size: 13px; color: #1a6b3c; font-weight: 600; line-height: 1.65; }

/* Tabla medicamentos */
.med-table { width: 100%; border-collapse: collapse; border: 1.5px solid #c6e8d5; border-radius: 10px; overflow: hidden; }
.med-table th {
    background: #1a6b3c; color: white;
    padding: 10px 14px;
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.07em; text-align: left;
}

/* Footer */
.footer {
    background: #f8fffe;
    border-top: 2px solid #e6f4ec;
    padding: 22px 40px;
    display: flex;
    gap: 0;
}
.footer-col { flex: 1; text-align: center; padding: 0 10px; }
.footer-col:not(:last-child) { border-right: 1px solid #e6f4ec; }
.footer-label { font-size: 10px; color: #718096; font-weight: 700; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 4px; }
.footer-val   { font-size: 13px; color: #2d3748; font-weight: 600; }
.firma-linea  { border-top: 1.5px solid #c6e8d5; padding-top: 6px; margin-top: 24px; }

/* Botones (solo pantalla, no impresion) */
.btn-bar {
    max-width: 760px; margin: 0 auto 16px;
    display: flex; gap: 10px; justify-content: flex-end;
}
.btn-print {
    background: #1a6b3c; color: white;
    padding: 11px 24px; border: none; border-radius: 8px;
    font-size: 14px; font-weight: 700; cursor: pointer;
}
.btn-close {
    background: #f1f5f9; color: #64748b;
    padding: 11px 22px; border: none; border-radius: 8px;
    font-size: 14px; font-weight: 600; cursor: pointer;
}

/* Print */
@media print {
    body { background: white; padding: 0; }
    .page { box-shadow: none; border-radius: 0; max-width: 100%; }
    .btn-bar { display: none; }
}
@page { margin: 10mm; }
</style>
</head>
<body>

<!-- Barra de botones (solo visible en pantalla) -->
<div class="btn-bar">
    <button class="btn-print" onclick="window.print()">&#128438; Imprimir / Guardar como PDF</button>
    <button class="btn-close" onclick="window.close()">&#x2715; Cerrar</button>
</div>

<div class="page">

    <!-- Header -->
    <div class="header">
        <div class="logo-box">
            <div class="logo-icon">VF</div>
            <div>
                <div class="logo-nombre">VetFarm</div>
                <div class="logo-sub">Sistema de Informes Medicos Veterinarios</div>
            </div>
        </div>
        <div class="header-info">
            <div class="informe-num">{$numInforme}</div>
            <div class="informe-fecha">Fecha: {$fecha}</div>
        </div>
    </div>
    <div class="deco-bar"></div>

    <div class="content">

        <!-- Veterinario y Granja -->
        <div class="info-grid">
            <div class="info-card">
                <div class="info-card-title">Veterinario Responsable</div>
                {$infoVet}
            </div>
            <div class="info-card">
                <div class="info-card-title">Datos de la Granja</div>
                {$infoGranja}
            </div>
        </div>

        <!-- Animal -->
        <div class="animal-banner">
            <div class="animal-tipo-label">{$tipoLabel}</div>
            <div class="animal-info">
                <div class="animal-nombre">{$animalNombre}</div>
                <div class="animal-id">ID / Arete: {$animalId}</div>
                <div class="badges">{$badges}</div>
            </div>
            <div class="estado-tag">{$est['texto']}</div>
        </div>

        <!-- Signos Vitales -->
        {$vitales}

        <!-- Motivo -->
        {$secMotivo}

        <!-- Sintomas -->
        {$secSintomas}

        <!-- Diagnostico (siempre presente) -->
        <div class="section">
            <div class="section-title">Diagnostico</div>
            <div class="dx-box">
                <div class="dx-text">{$d['diagnostico']}</div>
            </div>
        </div>

        <!-- Tratamiento -->
        {$secTratam}

        <!-- Medicamentos -->
        <div class="section">
            <div class="section-title">Medicamentos Recetados</div>
            <table class="med-table">
                <thead>
                    <tr>
                        <th>Medicamento</th>
                        <th>Dosis</th>
                        <th>Frecuencia / Duracion</th>
                    </tr>
                </thead>
                <tbody>
                    {$medRows}
                </tbody>
            </table>
        </div>

        <!-- Observaciones -->
        {$secObserv}

    </div><!-- /content -->

    <!-- Footer -->
    <div class="footer">
        <div class="footer-col">
            <div class="footer-label">Proximo Control</div>
            <div class="footer-val" style="color:#1a6b3c;">{$proximoControl}</div>
        </div>
        <div class="footer-col">
            <div class="footer-label">Firma del Veterinario</div>
            <div class="firma-linea">
                <div class="footer-val" style="font-style:italic;">{$veterinario}</div>
            </div>
        </div>
        <div class="footer-col">
            <div class="footer-label">Generado por</div>
            <div class="footer-val">VetFarm Sistema</div>
            <div style="font-size:11px;color:#a0aec0;margin-top:3px;">{$fecha}</div>
        </div>
    </div>

</div><!-- /page -->

</body>
</html>
HTML;
}

// ─────────────────────────────────────────────────────────────────────────────
// Helpers
// ─────────────────────────────────────────────────────────────────────────────

/** Escapa HTML de forma segura */
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Genera una fila de info (clave: valor) */
function infoRow(string $clave, string $valor): string {
    if (empty(trim($valor))) return '';
    return '<div class="info-row-item">'
         . '<span class="info-key">' . htmlspecialchars($clave) . ':</span>'
         . '<span class="info-val">'  . htmlspecialchars($valor) . '</span>'
         . '</div>';
}

/** Genera una sección clínica completa (o nada si está vacía) */
function seccion(string $titulo, string $contenido): string {
    if (empty(trim($contenido))) return '';
    return '<div class="section">'
         . '<div class="section-title">' . htmlspecialchars($titulo) . '</div>'
         . '<p class="section-text">' . nl2br(htmlspecialchars($contenido, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) . '</p>'
         . '</div>';
}