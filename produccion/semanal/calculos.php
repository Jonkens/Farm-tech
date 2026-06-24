<?php
/**
 * Funciones de cálculo para comparativa semanal.
 * No dependen de la base de datos.
 */

const PESOS_CARNE = [
    'Bovino'  => 250,
    'Porcino' => 80,
    'Ovino'   => 25,
    'Caprino' => 20,
];

/**
 * Calcula el cambio porcentual entre dos valores.
 */
function calcularCambioPorcentual(float $actual, float $anterior): float
{
    if ($anterior == 0) return $actual > 0 ? 100 : 0;
    return round(($actual - $anterior) / $anterior * 100, 1);
}

/**
 * Calcula los kilogramos totales de carne a partir del detalle de sacrificios.
 */
function calcularKgCarne(array $detalleSacrificios): float
{
    $total = 0;
    foreach ($detalleSacrificios as $tipo) {
        $total += (int)$tipo['total'] * (PESOS_CARNE[$tipo['animal_type']] ?? 0);
    }
    return $total;
}

/**
 * Calcula el promedio diario.
 */
function promedioDiario(float $total, int $dias = 7): float
{
    return $dias > 0 ? $total / $dias : 0;
}

/**
 * Calcula la eficiencia de huevos (% huevos/gallina/día).
 */
function calcularEficienciaHuevos(int $huevosSemana, int $gallinas, int $dias = 7): float
{
    if ($gallinas <= 0 || $huevosSemana <= 0) return 0;
    return round(($huevosSemana / $dias) / $gallinas * 100);
}