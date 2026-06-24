<?php
/**
 * Vista de Estimación de carne y proyección nutricional.
 * Recibe $datosVista desde el controlador.
 */
if (!isset($datosVista) || !is_array($datosVista)) {
    $datosVista = [];
}
$vacasSacrificadas = $datosVista['vacasSacrificadas'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estimación de Carne - Ganadería Tropical</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    /* Fondo ganadero con verde oscuro, radial y rayas */
    body {
      font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
      background: #1a4d2a;
      background-image: radial-gradient(circle at 10% 20%, rgba(255,215,140,0.1) 2%, transparent 2.5%),
      repeating-linear-gradient(45deg, rgba(34,85,34,0.3) 0px, rgba(34,85,34,0.3) 2px, transparent 2px, transparent 8px);
      background-size: 30px 30px, 12px 12px;
    }
    .notification-fade { transition: opacity 0.3s ease-in-out; }
    .tooltip-icon { cursor: help; border-bottom: 1px dotted #a16207; color: #b45309; }
    input:focus, select:focus {
      outline: none;
      border-color: #d97706 !important;
      ring-color: #fbbf24 !important;
      box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.3);
    }
    .btn-estimar {
      background: linear-gradient(135deg, #b45309 0%, #92400e 100%);
    }
    .btn-estimar:hover {
      background: linear-gradient(135deg, #9a3412 0%, #78350f 100%);
    }
    .btn-proyectar {
      background: linear-gradient(135deg, #2d6a4f 0%, #1f543d 100%);
    }
    .btn-proyectar:hover {
      background: linear-gradient(135deg, #1f543d 0%, #143d2c 100%);
    }
    .card-resultado {
      background: #fef3c7;
      border-left: 6px solid #d97706;
    }
    .card-proyeccion {
      background: #f5f5dc;
      border: 1px solid #d4a373;
    }
    .bg-selector {
      background: #fdf8e7;
      border: 1px solid #d4a373;
    }
  </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
  <div class="fixed inset-0 bg-black bg-opacity-30 pointer-events-none"></div>
  <div class="bg-white rounded-xl shadow-2xl p-8 max-w-2xl w-full relative z-10 border-t-8 border-amber-700">
    <div id="notificationArea" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-20 w-auto max-w-md pointer-events-none"></div>

    <h1 class="text-3xl font-bold text-center mb-6 flex items-center justify-center gap-3">
      <i class="fas fa-drumstick-bite text-amber-700"></i>
      <span class="bg-gradient-to-r from-green-800 to-amber-700 bg-clip-text text-transparent">Estimación de Carne</span>
      <i class="fas fa-tree text-green-700"></i>
    </h1>

    <!-- Selector de vaca sacrificada -->
    <div class="mb-6 bg-selector rounded-lg p-4 shadow-sm">
      <label class="block text-sm font-semibold text-stone-800 mb-2 flex items-center gap-2">
        <i class="fas fa-tag text-amber-700"></i> Seleccionar vaca sacrificada
      </label>
      <select id="selectVacaSacrificada" class="w-full border border-amber-300 rounded-lg p-2 focus:ring-amber-500 focus:border-amber-500 bg-white">
        <option value="">-- Seleccione una vaca sacrificada --</option>
        <?php foreach ($vacasSacrificadas as $vaca): ?>
          <option value="<?= htmlspecialchars(json_encode($vaca), ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($vaca['name'] ?: $vaca['code']) ?> - <?= htmlspecialchars($vaca['breed']) ?> (<?= $vaca['weight'] ?> kg, <?= $vaca['age'] ?> meses)
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (empty($vacasSacrificadas)): ?>
        <p class="text-xs text-amber-800 mt-1"><i class="fas fa-info-circle"></i> No hay vacas registradas como sacrificadas. Ingrese los datos manualmente.</p>
      <?php endif; ?>
    </div>

    <!-- Sección Estimación de carne -->
    <div>
      <h2 class="text-xl font-bold text-stone-800 mb-3 flex items-center gap-2 border-b border-amber-300 pb-2">
        <i class="fas fa-weight-hanging text-amber-700"></i> Estimación de carne en canal
      </h2>
      <div class="mb-4">
        <label class="block text-sm font-medium text-stone-700 mb-1">Peso vivo (kg)</label>
        <input type="number" id="pesoEstimacion" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600" placeholder="Ej: 480">
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-stone-700 mb-1">Raza</label>
        <select id="razaEstimacion" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600">
          <option>Holstein</option><option>Ayrshire</option><option>Jersey</option>
          <option>Guernsey</option><option>Shorthorn</option><option>Pardo Suizo</option>
        </select>
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium text-stone-700 mb-1">Edad (meses)</label>
        <input type="number" id="edadEstimacion" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600" placeholder="Ej: 52">
      </div>
      <button id="btnEstimarCarne" class="btn-estimar w-full text-white px-4 py-2 rounded-lg transition font-medium mb-6 shadow-md">
        <i class="fas fa-chart-line mr-2"></i> Estimar carne en canal
      </button>
      <div class="card-resultado rounded-lg p-4 mb-6 space-y-2">
        <div><p class="text-sm text-stone-700">Carne en canal (kg):</p><p id="resultadoCanal" class="text-3xl font-bold text-amber-800">--- kg</p></div>
        <div><p class="text-sm text-stone-700">Carne limpia para consumo (kg):</p><p id="resultadoLimpia" class="text-3xl font-bold text-amber-800">--- kg</p></div>
      </div>
    </div>

    <!-- Sección Proyección mejorada -->
    <div class="border-t border-amber-200 pt-6 mt-2">
      <h2 class="text-xl font-bold text-stone-800 mb-4 flex items-center gap-2">
        <i class="fas fa-chart-simple text-green-700"></i> Proyección basada en consumo y genética
        <span class="text-sm font-normal text-stone-500 tooltip-icon" title="Modelo que considera consumo de materia seca, energía del alimento y potencial genético">
          <i class="fas fa-circle-info"></i>
        </span>
      </h2>
      
      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Edad (meses)</label>
            <input type="number" id="edadProyeccion" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600">
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Peso actual (kg)</label>
            <input type="number" id="pesoActualProyeccion" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Raza</label>
            <select id="razaProyeccion" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600">
              <option>Holstein</option><option>Ayrshire</option><option>Jersey</option>
              <option>Guernsey</option><option>Shorthorn</option><option>Pardo Suizo</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-stone-700 mb-1">Tipo de dieta</label>
            <select id="tipoDieta" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600">
              <option value="alta">Concentrado (Alto grano) - 2.9 Mcal/kg</option>
              <option value="media" selected>Mixta (Ensilaje + grano) - 2.4 Mcal/kg</option>
              <option value="baja">Forraje (Pasto/heno) - 2.0 Mcal/kg</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-stone-700 mb-1">
            Consumo diario de alimento (kg MS)
            <span class="tooltip-icon" title="Materia Seca consumida por día. Aprox 2-3% del peso vivo">
              <i class="fas fa-circle-info"></i>
            </span>
          </label>
          <input type="number" step="0.1" id="consumoMS" class="w-full border border-stone-300 rounded-lg p-2 focus:border-amber-600">
        </div>
        <div class="bg-amber-50 p-3 rounded-lg border border-amber-200">
          <p class="text-sm font-medium text-stone-700 mb-2 flex items-center gap-1">
            <i class="fas fa-sliders-h text-amber-600"></i> Parámetros avanzados (opcional)
          </p>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-xs text-stone-600">Suplemento</label>
              <select id="suplemento" class="w-full border border-stone-300 rounded p-1 text-sm">
                <option value="0">Ninguno</option>
                <option value="5">Ionóforo (Monensina) +5% efic.</option>
                <option value="8">β-agonista (Zilpaterol) +8% efic.</option>
                <option value="3">Levaduras/Probiótico +3% efic.</option>
              </select>
            </div>
            <div>
              <label class="block text-xs text-stone-600">Estrés calórico</label>
              <select id="estres" class="w-full border border-stone-300 rounded p-1 text-sm">
                <option value="0">Confort térmico</option>
                <option value="-10">Leve (-10% CMS)</option>
                <option value="-20">Moderado (-20% CMS)</option>
              </select>
            </div>
          </div>
        </div>
        <button id="btnCalcularProyeccion" class="btn-proyectar w-full text-white px-4 py-2 rounded-lg transition font-medium mt-2 shadow-md">
          <i class="fas fa-calculator mr-2"></i> Calcular proyección real
        </button>
        <div class="card-proyeccion rounded-lg p-4 mt-4">
          <p class="text-sm font-semibold text-stone-700 mb-3">Resultados de proyección a 30 y 60 días:</p>
          <div class="flex justify-between gap-4">
            <div class="flex-1 text-center bg-white p-3 rounded shadow-sm">
              <span class="block text-xs text-stone-500">Peso a 30 días</span>
              <span id="peso30Dias" class="text-xl font-bold text-green-800">--- kg</span>
              <span id="gdp30" class="block text-xs text-stone-500 mt-1"></span>
            </div>
            <div class="flex-1 text-center bg-white p-3 rounded shadow-sm">
              <span class="block text-xs text-stone-500">Peso a 60 días</span>
              <span id="peso60Dias" class="text-xl font-bold text-green-800">--- kg</span>
              <span id="gdp60" class="block text-xs text-stone-500 mt-1"></span>
            </div>
          </div>
          <div class="mt-3 text-sm text-stone-700 border-t border-amber-200 pt-2 text-center">
            <span class="font-medium">Ganancia Diaria Proyectada:</span> <span id="gdpPromedio" class="font-semibold text-amber-800">--</span> kg/día
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function() {
      'use strict';
      
      document.addEventListener('DOMContentLoaded', function() {
        const pesoEstimacion = document.getElementById('pesoEstimacion');
        const razaEstimacion = document.getElementById('razaEstimacion');
        const edadEstimacion = document.getElementById('edadEstimacion');
        const btnEstimarCarne = document.getElementById('btnEstimarCarne');
        const resultadoCanal = document.getElementById('resultadoCanal');
        const resultadoLimpia = document.getElementById('resultadoLimpia');

        const edadProyeccion = document.getElementById('edadProyeccion');
        const pesoActualProyeccion = document.getElementById('pesoActualProyeccion');
        const razaProyeccion = document.getElementById('razaProyeccion');
        const tipoDieta = document.getElementById('tipoDieta');
        const consumoMS = document.getElementById('consumoMS');
        const suplemento = document.getElementById('suplemento');
        const estres = document.getElementById('estres');
        const btnCalcularProyeccion = document.getElementById('btnCalcularProyeccion');
        const peso30Dias = document.getElementById('peso30Dias');
        const peso60Dias = document.getElementById('peso60Dias');
        const gdp30 = document.getElementById('gdp30');
        const gdp60 = document.getElementById('gdp60');
        const gdpPromedio = document.getElementById('gdpPromedio');
        
        const notificationArea = document.getElementById('notificationArea');
        const selectVacaSacrificada = document.getElementById('selectVacaSacrificada');

        function mostrarNotificacion(mensaje, tipo = 'error') {
          const notification = document.createElement('div');
          notification.className = `mb-2 p-3 rounded-lg shadow-lg text-white ${tipo === 'error' ? 'bg-red-600' : 'bg-green-700'} notification-fade`;
          notification.innerText = mensaje;
          notificationArea.appendChild(notification);
          setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
          }, 3500);
        }

        selectVacaSacrificada.addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          if (!selectedOption.value) return;
          
          try {
            const vaca = JSON.parse(selectedOption.value);
            if (vaca.weight) pesoEstimacion.value = vaca.weight;
            if (vaca.breed) razaEstimacion.value = vaca.breed;
            if (vaca.age) edadEstimacion.value = vaca.age;
            
            if (vaca.weight) pesoActualProyeccion.value = vaca.weight;
            if (vaca.breed) razaProyeccion.value = vaca.breed;
            if (vaca.age) edadProyeccion.value = vaca.age;
            
            if (vaca.weight) {
              const sugerido = (parseFloat(vaca.weight) * 0.024).toFixed(1);
              consumoMS.value = sugerido;
            }
            
            mostrarNotificacion(`Datos de "${vaca.name || vaca.code}" cargados correctamente.`, 'success');
          } catch (e) {
            console.error('Error al parsear datos de vaca:', e);
          }
        });

        // ✅ Validación de peso: mínimo 400 kg, máximo 650 kg
        function validarPeso(peso) {
          if (isNaN(peso) || peso <= 0) { mostrarNotificacion('Peso debe ser > 0'); return false; }
          if (peso < 400) { mostrarNotificacion('Peso mínimo para sacrificio es 400 kg'); return false; }
          if (peso > 650) { mostrarNotificacion('Peso máximo 650 kg'); return false; }
          return true;
        }

        // ✅ Validación de edad: mínimo 48 meses, máximo 120 meses
        function validarEdad(edad) {
          if (isNaN(edad) || edad <= 0) { mostrarNotificacion('Edad debe ser > 0'); return false; }
          if (edad < 48) { mostrarNotificacion('Edad mínima para sacrificio es 48 meses'); return false; }
          if (edad > 120) { mostrarNotificacion('Edad máxima 120 meses'); return false; }
          return true;
        }

        function obtenerFactorRaza(raza) {
          const factores = { 'Holstein': 50, 'Ayrshire': 52, 'Jersey': 48, 'Guernsey': 51, 'Shorthorn': 55 };
          return factores[raza] || 50;
        }
        function ajustePorEdad(edadMeses) {
          if (edadMeses < 12) return 0.85;
          if (edadMeses <= 24) return 1.0;
          if (edadMeses <= 36) return 1.05;
          return 1.1;
        }

        function estimarCarne() {
          const peso = parseFloat(pesoEstimacion.value);
          const raza = razaEstimacion.value;
          const edad = parseFloat(edadEstimacion.value);
          if (!validarPeso(peso) || !validarEdad(edad)) return;
          const factorBase = obtenerFactorRaza(raza);
          const ajuste = ajustePorEdad(edad);
          const carneCanal = peso * (factorBase / 100) * ajuste;
          const carneLimpia = carneCanal * 0.70;
          resultadoCanal.innerText = carneCanal.toFixed(2) + ' kg';
          resultadoLimpia.innerText = carneLimpia.toFixed(2) + ' kg';
        }

        const parametrosRaza = {
          'Holstein':   { pesoAdulto: 750, tasaMadurez: 0.009, factorEficiencia: 0.85 },
          'Ayrshire':   { pesoAdulto: 680, tasaMadurez: 0.010, factorEficiencia: 0.88 },
          'Jersey':     { pesoAdulto: 550, tasaMadurez: 0.011, factorEficiencia: 0.90 },
          'Guernsey':   { pesoAdulto: 650, tasaMadurez: 0.010, factorEficiencia: 0.87 },
          'Shorthorn':  { pesoAdulto: 800, tasaMadurez: 0.008, factorEficiencia: 0.80 },
          'Pardo Suizo':{ pesoAdulto: 720, tasaMadurez: 0.009, factorEficiencia: 0.84 }
        };

        const energiaDieta = { 'alta': 2.9, 'media': 2.4, 'baja': 2.0 };

        function calcNEm(pesoVivo) { return 0.077 * Math.pow(pesoVivo, 0.75); }
        function calcEnergiaRetenida(cms, emDieta, nem) {
          const emConsumida = cms * emDieta;
          const eficienciaGanancia = 0.44;
          let re = (emConsumida - nem) * eficienciaGanancia;
          return Math.max(0, re);
        }
        function calcGDPdesdeRE(re, pesoVivo) {
          if (re <= 0) return 0;
          return (13.91 * Math.pow(re, 0.9116)) / Math.pow(pesoVivo, 0.6837);
        }
        function ajusteMadurez(pesoActual, pesoAdulto, tasaMadurez) {
          const gradoMadurez = pesoActual / pesoAdulto;
          return Math.exp(-tasaMadurez * Math.pow(gradoMadurez, 2) * 100);
        }

        function calcularProyeccionNutricional() {
          const edad = parseFloat(edadProyeccion.value);
          const pesoInicial = parseFloat(pesoActualProyeccion.value);
          const raza = razaProyeccion.value;
          const dieta = tipoDieta.value;
          let cmsInput = parseFloat(consumoMS.value);
          
          if (!validarEdad(edad)) return;
          if (!validarPeso(pesoInicial)) return;
          
          if (isNaN(cmsInput) || cmsInput <= 0) {
            mostrarNotificacion('Ingrese un consumo de materia seca válido (>0 kg/día)');
            return;
          }

          const params = parametrosRaza[raza] || parametrosRaza['Holstein'];
          const pesoAdulto = params.pesoAdulto;
          const tasaMadurez = params.tasaMadurez;
          const factorEficienciaBase = params.factorEficiencia;

          const emDieta = energiaDieta[dieta];
          const mejoraSuplemento = parseFloat(suplemento.value) / 100;
          const factorEstres = 1 + (parseFloat(estres.value) / 100);
          const cmsEfectivo = cmsInput * factorEstres;
          
          if (cmsEfectivo <= 0) {
            mostrarNotificacion('El consumo efectivo es 0 o negativo debido al estrés térmico');
            return;
          }

          const nem = calcNEm(pesoInicial);
          let re = calcEnergiaRetenida(cmsEfectivo, emDieta, nem);
          re = re * factorEficienciaBase * (1 + mejoraSuplemento);
          
          let gdpBase = calcGDPdesdeRE(re, pesoInicial);
          const factorMadurez = ajusteMadurez(pesoInicial, pesoAdulto, tasaMadurez);
          const gdpAjustada = gdpBase * factorMadurez;
          const gdpFinal = Math.min(gdpAjustada, 2.2);

          const peso30 = pesoInicial + (gdpFinal * 30);
          const peso60 = pesoInicial + (gdpFinal * 60);
          
          peso30Dias.innerText = peso30.toFixed(1) + ' kg';
          peso60Dias.innerText = peso60.toFixed(1) + ' kg';
          gdp30.innerText = `GDP: ${gdpFinal.toFixed(3)} kg/d`;
          gdp60.innerText = `GDP: ${gdpFinal.toFixed(3)} kg/d`;
          gdpPromedio.innerText = gdpFinal.toFixed(3) + ' kg/día';
          
          mostrarNotificacion(`Proyección calculada (GDP: ${gdpFinal.toFixed(3)} kg/d, eficiencia ${(cmsEfectivo/gdpFinal).toFixed(2)}:1)`, 'success');
          
          const cmsRecomendado = pesoInicial * 0.025;
          if (cmsInput < cmsRecomendado * 0.8) {
            setTimeout(() => mostrarNotificacion(`El consumo ingresado es bajo para el peso. Consumo típico ~${cmsRecomendado.toFixed(1)} kg MS/día.`, 'error'), 200);
          }
        }

        btnEstimarCarne.addEventListener('click', estimarCarne);
        btnCalcularProyeccion.addEventListener('click', calcularProyeccionNutricional);

        document.querySelectorAll('#pesoEstimacion, #edadEstimacion').forEach(i => i.addEventListener('keypress', e => { if(e.key==='Enter') estimarCarne(); }));
        document.querySelectorAll('#edadProyeccion, #pesoActualProyeccion, #consumoMS').forEach(i => i.addEventListener('keypress', e => { if(e.key==='Enter') calcularProyeccionNutricional(); }));

        function addBlurValidation() {
          // ✅ Peso: 400 – 650 kg
          const pesos = [pesoEstimacion, pesoActualProyeccion];
          pesos.forEach(c => c?.addEventListener('blur', function(){
            let v = parseFloat(this.value);
            this.style.borderColor = (!isNaN(v) && v>=400 && v<=650) ? '#d4a373' : '#ef4444';
          }));
          // ✅ Edad: 48 – 120 meses
          const edades = [edadEstimacion, edadProyeccion];
          edades.forEach(c => c?.addEventListener('blur', function(){
            let v = parseFloat(this.value);
            this.style.borderColor = (!isNaN(v) && v>=48 && v<=120) ? '#d4a373' : '#ef4444';
          }));
          consumoMS?.addEventListener('blur', function(){
            let v = parseFloat(this.value);
            this.style.borderColor = (!isNaN(v) && v>0) ? '#d4a373' : '#ef4444';
          });
        }
        addBlurValidation();
        
        pesoActualProyeccion?.addEventListener('input', function() {
          const peso = parseFloat(this.value);
          if (!isNaN(peso) && peso > 0) {
            const sugerido = (peso * 0.024).toFixed(1);
            if (!consumoMS.value) consumoMS.value = sugerido;
          }
        });
      });
    })();
  </script>
</body>
</html>