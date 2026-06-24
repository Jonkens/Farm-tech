<?php
/**
 * Cálculos para estimación de carne (backend).
 * Aquí se pueden centralizar funciones de estimación si se desea migrar desde JS.
 * Actualmente la lógica principal permanece en la vista por interactividad.
 */

/**
 * Estima la carne en canal a partir del peso, raza y edad.
 * @param float $peso Kg
 * @param string $raza
 * @param int $edad meses
 * @return array ['canal' => float, 'limpia' => float]
 */
function estimarCarneBackend($peso, $raza, $edad) {
    $factoresRaza = [
        'Holstein' => 50, 'Ayrshire' => 52, 'Jersey' => 48,
        'Guernsey' => 51, 'Shorthorn' => 55, 'Pardo Suizo' => 50
    ];
    $factorBase = $factoresRaza[$raza] ?? 50;
    
    if ($edad < 12) $ajuste = 0.85;
    elseif ($edad <= 24) $ajuste = 1.0;
    elseif ($edad <= 36) $ajuste = 1.05;
    else $ajuste = 1.1;
    
    $canal = $peso * ($factorBase / 100) * $ajuste;
    $limpia = $canal * 0.70;
    return ['canal' => round($canal, 2), 'limpia' => round($limpia, 2)];
}