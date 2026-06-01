<?php
/* ============================================================
   FarmTech — Informes Médicos Veterinarios
   Archivo único · Solo necesita generate_pdf.php en la misma carpeta
   ============================================================ */

$ok = false; $pdfPath = ''; $errors = [];

// ── Descargar / ver archivo generado ─────────────────────────
if (isset($_GET['dl']) && !empty($_GET['f'])) {
    $f = basename($_GET['f']);
    $fp = __DIR__ . '/pdfs/' . $f;
    $ext = strtolower(pathinfo($fp, PATHINFO_EXTENSION));
    if (file_exists($fp)) {
        if ($ext === 'pdf') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$f.'"');
            header('Content-Length: '.filesize($fp));
            readfile($fp); exit;
        } elseif ($ext === 'html') {
            header('Content-Type: text/html; charset=UTF-8');
            readfile($fp); exit;
        }
    }
}

// ── Procesar formulario ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = [
        'numero_informe'  => 'INF-'.date('YmdHis'),
        'fecha'           => $_POST['fecha']           ?? date('Y-m-d'),
        'proximo_control' => $_POST['proximo_control'] ?? '',
        'veterinario'     => trim($_POST['veterinario']     ?? ''),
        'clinica'         => trim($_POST['clinica']         ?? ''),
        'granja'          => trim($_POST['granja']          ?? ''),
        'propietario'     => trim($_POST['propietario']     ?? ''),
        'direccion'       => trim($_POST['direccion']       ?? ''),
        'tipo_animal'     => $_POST['tipo_animal']     ?? '',
        'raza'            => trim($_POST['raza']            ?? ''),
        'identificacion'  => trim($_POST['identificacion']  ?? ''),
        'edad'            => trim($_POST['edad']            ?? ''),
        'sexo'            => $_POST['sexo']            ?? '',
        'peso'            => trim($_POST['peso']            ?? ''),
        'temperatura'     => trim($_POST['temperatura']     ?? ''),
        'frecuencia_card' => trim($_POST['frecuencia_card'] ?? ''),
        'frecuencia_resp' => trim($_POST['frecuencia_resp'] ?? ''),
        'motivo_consulta' => trim($_POST['motivo_consulta'] ?? ''),
        'sintomas'        => trim($_POST['sintomas']        ?? ''),
        'diagnostico'     => trim($_POST['diagnostico']     ?? ''),
        'tratamiento'     => trim($_POST['tratamiento']     ?? ''),
        'medicamentos'    => $_POST['medicamentos']    ?? [],
        'observaciones'   => trim($_POST['observaciones']   ?? ''),
        'estado'          => $_POST['estado']          ?? 'estable',
    ];

    if (!$d['veterinario']) $errors[] = 'El nombre del veterinario es requerido.';
    if (!$d['tipo_animal']) $errors[] = 'El tipo de animal es requerido.';
    if (!$d['diagnostico']) $errors[] = 'El diagnóstico es requerido.';

    if (!$errors) {
        require_once __DIR__ . '/generate_pdf.php';
        $r = generatePDF($d);
        if ($r['success']) { $ok = true; $pdfPath = $r['path']; }
        else $errors[] = $r['error'];
    }
}

// ── Helpers de vista ─────────────────────────────────────────
function h($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function old($k){ return h($_POST[$k] ?? ''); }
function sel($k,$v){ return (($_POST[$k] ?? '') === $v) ? 'selected' : ''; }
function chk($k,$v,$def=''){ $cur=$_POST[$k]??$def; return $cur===$v?'checked':''; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Informes Médicos — FarmTech</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { font-family:'DM Sans',sans-serif; background:#f0f4f0; }
  .card { background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(0,0,0,.05),0 4px 14px rgba(0,0,0,.07); }
  .inp { width:100%; padding:9px 13px; border:1.5px solid #dde5dd; border-radius:9px; font-size:14px;
         background:#fafafa; color:#2d3748; transition:border .15s,box-shadow .15s; font-family:inherit; }
  .inp:focus { outline:none; border-color:#2d9e5f; background:#fff; box-shadow:0 0 0 3px rgba(45,158,95,.12); }
  .lbl { display:block; font-size:11px; font-weight:700; color:#718096;
         text-transform:uppercase; letter-spacing:.06em; margin-bottom:5px; }
  .sec { font-size:11px; font-weight:700; color:#1a6b3c; text-transform:uppercase;
         letter-spacing:.1em; padding-bottom:9px; border-bottom:2px solid #e6f4ec; margin-bottom:18px; }
  .btn { background:linear-gradient(135deg,#1a6b3c,#2d9e5f); color:#fff; padding:12px 28px;
         border-radius:10px; font-weight:700; font-size:14px; border:none; cursor:pointer;
         display:inline-flex; align-items:center; gap:8px;
         box-shadow:0 4px 14px rgba(26,107,60,.3); transition:all .2s; }
  .btn:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(26,107,60,.4); }
  .med-grid { display:grid; grid-template-columns:1fr 1fr 1fr auto; gap:8px; align-items:center; }
  .estado-r { display:none; }
  .estado-r:checked + .estado-l { opacity:1; transform:scale(1.06); box-shadow:0 0 0 2px #1a6b3c; }
  .estado-l { opacity:.55; transition:all .2s; cursor:pointer; padding:5px 14px;
              border-radius:100px; font-size:12px; font-weight:700; border:1.5px solid; display:inline-block; }
</style>
</head>
<body class="min-h-screen py-8 px-4">
<div class="max-w-4xl mx-auto">

  <!-- ── Título de sección ─────────────────────────────── -->
  <div class="flex items-center justify-between mb-7">
    <div>
      <h2 class="text-2xl font-bold text-gray-800">Nuevo Informe Médico</h2>
      <p class="text-gray-400 text-sm mt-0.5">Complete los datos para generar el reporte veterinario en PDF</p>
    </div>
    <div class="text-right">
      <div class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Fecha</div>
      <div class="text-gray-700 font-semibold text-sm"><?= date('d/m/Y') ?></div>
    </div>
  </div>

  <!-- ── Banner de éxito ───────────────────────────────── -->
  <?php if ($ok):
    $ext = strtolower(pathinfo($pdfPath, PATHINFO_EXTENSION));
    $esPdf = $ext === 'pdf';
  ?>
  <div class="mb-6 rounded-2xl p-5 flex items-center gap-5"
       style="background:linear-gradient(135deg,#1a6b3c,#2d9e5f);color:#fff;">
    <span class="text-4xl">✅</span>
    <div class="flex-1">
      <div class="font-bold text-lg">¡Informe generado exitosamente!</div>
      <div class="text-green-100 text-sm mt-0.5">
        <?= $esPdf ? 'El PDF está listo para descargar.'
                   : 'Haz clic → se abre el informe → <strong>Ctrl+P → Guardar como PDF</strong>' ?>
      </div>
    </div>
    <a href="?dl=1&f=<?= urlencode(basename($pdfPath)) ?>" target="_blank"
       class="bg-white text-green-800 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-green-50 transition-colors whitespace-nowrap">
      <?= $esPdf ? '⬇️ Descargar PDF' : '🖨️ Ver e Imprimir' ?>
    </a>
  </div>
  <?php endif; ?>

  <!-- ── Errores ───────────────────────────────────────── -->
  <?php if ($errors): ?>
  <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
    <?php foreach ($errors as $e): ?>
      <p class="text-red-600 text-sm">⚠️ <?= h($e) ?></p>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- ════════════════ FORMULARIO ════════════════ -->
  <form method="POST">

    <!-- 1. Datos del Informe -->
    <div class="card p-6 mb-5">
      <div class="sec">📄 Datos del Informe</div>
      <div class="grid grid-cols-3 gap-5">
        <div>
          <label class="lbl">N° de Informe</label>
          <input class="inp" style="background:#f0faf5;color:#1a6b3c;font-weight:700;"
                 value="INF-<?= date('YmdHis') ?>" readonly>
        </div>
        <div>
          <label class="lbl">Fecha de Consulta</label>
          <input type="date" name="fecha" class="inp" value="<?= date('Y-m-d') ?>">
        </div>
        <div>
          <label class="lbl">Próximo Control</label>
          <input type="date" name="proximo_control" class="inp" value="<?= old('proximo_control') ?>">
        </div>
      </div>
    </div>

    <!-- 2. Veterinario y Granja -->
    <div class="grid grid-cols-2 gap-5 mb-5">
      <div class="card p-6">
        <div class="sec">👨‍⚕️ Veterinario</div>
        <div class="flex flex-col gap-4">
          <div>
            <label class="lbl">Nombre *</label>
            <input type="text" name="veterinario" class="inp" placeholder="Dr. Juan Pérez"
                   required value="<?= old('veterinario') ?>">
          </div>
          <div>
            <label class="lbl">Clínica / Institución</label>
            <input type="text" name="clinica" class="inp" placeholder="Clínica Veterinaria Los Prados"
                   value="<?= old('clinica') ?>">
          </div>
        </div>
      </div>
      <div class="card p-6">
        <div class="sec">🏡 Granja</div>
        <div class="flex flex-col gap-4">
          <div>
            <label class="lbl">Nombre de la Granja</label>
            <input type="text" name="granja" class="inp" placeholder="Granja El Roble"
                   value="<?= old('granja') ?>">
          </div>
          <div>
            <label class="lbl">Propietario</label>
            <input type="text" name="propietario" class="inp" placeholder="Carlos Rodríguez"
                   value="<?= old('propietario') ?>">
          </div>
          <div>
            <label class="lbl">Dirección</label>
            <input type="text" name="direccion" class="inp" placeholder="Km 5, Carretera Norte"
                   value="<?= old('direccion') ?>">
          </div>
        </div>
      </div>
    </div>

    <!-- 3. Animal -->
    <div class="card p-6 mb-5">
      <div class="sec">🐾 Datos del Animal</div>
      <div class="grid grid-cols-3 gap-5 mb-5">
        <div>
          <label class="lbl">Tipo de Animal *</label>
          <select name="tipo_animal" class="inp" required>
            <option value="">— Seleccionar —</option>
            <option value="Bovino"  <?= sel('tipo_animal','Bovino')  ?>>🐄 Bovino</option>
            <option value="Porcino" <?= sel('tipo_animal','Porcino') ?>>🐷 Porcino</option>
            <option value="Ovino"   <?= sel('tipo_animal','Ovino')   ?>>🐑 Ovino</option>
            <option value="Caprino" <?= sel('tipo_animal','Caprino') ?>>🐐 Caprino</option>
            <option value="Equino"  <?= sel('tipo_animal','Equino')  ?>>🐴 Equino</option>
            <option value="Aviar"   <?= sel('tipo_animal','Aviar')   ?>>🐔 Aviar</option>
            <option value="Conejo"  <?= sel('tipo_animal','Conejo')  ?>>🐇 Cunícola</option>
            <option value="Otro"    <?= sel('tipo_animal','Otro')    ?>>🐾 Otro</option>
          </select>
        </div>
        <div>
          <label class="lbl">Raza</label>
          <input type="text" name="raza" class="inp" placeholder="Holstein, Duroc…"
                 value="<?= old('raza') ?>">
        </div>
        <div>
          <label class="lbl">ID / Arete / Código</label>
          <input type="text" name="identificacion" class="inp" placeholder="AR-2024-001"
                 value="<?= old('identificacion') ?>">
        </div>
      </div>
      <div class="grid grid-cols-3 gap-5">
        <div>
          <label class="lbl">Edad</label>
          <input type="text" name="edad" class="inp" placeholder="2 años 3 meses"
                 value="<?= old('edad') ?>">
        </div>
        <div>
          <label class="lbl">Sexo</label>
          <select name="sexo" class="inp">
            <option value="">— Seleccionar —</option>
            <option value="Macho"  <?= sel('sexo','Macho')  ?>>♂ Macho</option>
            <option value="Hembra" <?= sel('sexo','Hembra') ?>>♀ Hembra</option>
            <option value="No determinado" <?= sel('sexo','No determinado') ?>>No determinado</option>
          </select>
        </div>
        <div>
          <label class="lbl">Peso (kg)</label>
          <input type="number" step="0.1" name="peso" class="inp" placeholder="450"
                 value="<?= old('peso') ?>">
        </div>
      </div>
    </div>

    <!-- 4. Signos Vitales -->
    <div class="card p-6 mb-5">
      <div class="sec">💓 Signos Vitales</div>
      <div class="grid grid-cols-3 gap-5">
        <?php foreach ([
          ['temperatura','Temperatura','38.5 °C'],
          ['frecuencia_card','Frec. Cardíaca','72 lpm'],
          ['frecuencia_resp','Frec. Respiratoria','16 rpm'],
        ] as [$name,$label,$ph]): ?>
        <div class="rounded-xl p-4" style="background:#f0faf5;border:1.5px solid #c6e8d5;">
          <label class="lbl text-center block"><?= $label ?></label>
          <input type="text" name="<?= $name ?>" class="inp text-center mt-1"
                 style="background:transparent;border-color:#c6e8d5;"
                 placeholder="<?= $ph ?>" value="<?= old($name) ?>">
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- 5. Información Clínica -->
    <div class="card p-6 mb-5">
      <div class="sec">🩺 Información Clínica</div>
      <div class="grid grid-cols-2 gap-5 mb-4">
        <div>
          <label class="lbl">Motivo de Consulta</label>
          <textarea name="motivo_consulta" class="inp" rows="3"
                    placeholder="Motivo principal de la consulta…"><?= old('motivo_consulta') ?></textarea>
        </div>
        <div>
          <label class="lbl">Síntomas Observados</label>
          <textarea name="sintomas" class="inp" rows="3"
                    placeholder="Síntomas observados…"><?= old('sintomas') ?></textarea>
        </div>
      </div>
      <div class="mb-4">
        <label class="lbl">Diagnóstico *</label>
        <textarea name="diagnostico" class="inp" rows="3" required
                  placeholder="Diagnóstico clínico…"><?= old('diagnostico') ?></textarea>
      </div>
      <div class="mb-4">
        <label class="lbl">Tratamiento Indicado</label>
        <textarea name="tratamiento" class="inp" rows="3"
                  placeholder="Tratamiento a seguir…"><?= old('tratamiento') ?></textarea>
      </div>
      <!-- Estado -->
      <div>
        <label class="lbl mb-3 block">Estado General</label>
        <div class="flex gap-2 flex-wrap">
          <?php foreach ([
            ['critico','Crítico','#dc2626','#fef2f2'],
            ['grave','Grave','#d97706','#fffbeb'],
            ['regular','Regular','#ca8a04','#fefce8'],
            ['estable','Estable','#16a34a','#f0fdf4'],
            ['bueno','Bueno','#059669','#ecfdf5'],
          ] as [$val,$label,$color,$bg]): ?>
          <label>
            <input type="radio" name="estado" value="<?= $val ?>" class="estado-r"
                   <?= chk('estado',$val,'estable') ?>>
            <span class="estado-l"
                  style="color:<?= $color ?>;background:<?= $bg ?>;border-color:<?= $color ?>;">
              <?= $label ?>
            </span>
          </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- 6. Medicamentos -->
    <div class="card p-6 mb-5">
      <div class="flex items-center justify-between mb-4">
        <div class="sec mb-0 pb-0 border-0">💊 Medicamentos Recetados</div>
        <button type="button" onclick="addMed()"
                class="text-green-700 text-sm font-bold bg-green-50 hover:bg-green-100
                       px-3 py-1.5 rounded-lg transition-colors">
          ➕ Agregar
        </button>
      </div>
      <div style="border-bottom:2px solid #e6f4ec;margin-bottom:12px;"></div>
      <div class="grid grid-cols-4 gap-2 mb-2"
           style="font-size:10px;font-weight:700;color:#718096;text-transform:uppercase;letter-spacing:.07em;">
        <div>Medicamento</div><div>Dosis</div><div>Frecuencia</div><div></div>
      </div>
      <div id="meds">
        <div class="med-grid mb-2" id="m0">
          <input type="text" name="medicamentos[0][nombre]"    class="inp" placeholder="Oxitetraciclina">
          <input type="text" name="medicamentos[0][dosis]"     class="inp" placeholder="10 mg/kg">
          <input type="text" name="medicamentos[0][frecuencia]" class="inp" placeholder="Cada 24h × 5 días">
          <button type="button" onclick="rmMed('m0')"
                  class="text-red-400 hover:text-red-600 text-xl font-bold w-8 h-8
                         flex items-center justify-center rounded-lg hover:bg-red-50">×</button>
        </div>
      </div>
    </div>

    <!-- 7. Observaciones -->
    <div class="card p-6 mb-7">
      <div class="sec">📝 Observaciones</div>
      <textarea name="observaciones" class="inp" rows="4"
                placeholder="Notas adicionales, recomendaciones, cuidados especiales…"><?= old('observaciones') ?></textarea>
    </div>

    <!-- Acciones -->
    <div class="flex justify-end gap-3">
      <button type="reset"
              class="px-5 py-3 text-gray-500 hover:text-gray-700 font-semibold
                     rounded-xl hover:bg-gray-100 transition-colors text-sm">
        Limpiar
      </button>
      <button type="submit" class="btn">
        📄 Generar Informe PDF
      </button>
    </div>

  </form>
</div><!-- /max-w -->

<script>
let mc = 1;
function addMed() {
  const c = document.getElementById('meds');
  const id = 'med-grid-' + mc;
  const d = document.createElement('div');
  d.className = 'med-grid mb-2'; d.id = id;
  d.innerHTML = `
    <input type="text" name="medicamentos[${mc}][nombre]"     class="inp" placeholder="Medicamento"  style="width:100%;padding:9px 13px;border:1.5px solid #dde5dd;border-radius:9px;font-size:14px;background:#fafafa;">
    <input type="text" name="medicamentos[${mc}][dosis]"      class="inp" placeholder="Dosis"        style="width:100%;padding:9px 13px;border:1.5px solid #dde5dd;border-radius:9px;font-size:14px;background:#fafafa;">
    <input type="text" name="medicamentos[${mc}][frecuencia]" class="inp" placeholder="Frecuencia"   style="width:100%;padding:9px 13px;border:1.5px solid #dde5dd;border-radius:9px;font-size:14px;background:#fafafa;">
    <button type="button" onclick="rmMed('${id}')"
            style="color:#f87171;font-size:20px;font-weight:700;width:32px;height:32px;
                   display:flex;align-items:center;justify-content:center;border:none;
                   background:transparent;cursor:pointer;border-radius:8px;">×</button>`;
  c.appendChild(d); mc++;
}
function rmMed(id){ const el=document.getElementById(id); if(el)el.remove(); }
</script>
</body>
</html>