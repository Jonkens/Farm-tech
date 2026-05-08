<?php
/**
 * Cálculos para distribución del hato (backend).
 * Funciones complementarias si son necesarias en el futuro.
 */

function calcularEstadisticasPeso(array $animales): array
{
    $pesos = array_column($animales, 'weight');
    $total = count($pesos);
    if ($total === 0) {
        return ['media' => 0, 'min' => 0, 'max' => 0, 'total' => 0];
    }
    return [
        'media' => array_sum($pesos) / $total,
        'min'   => min($pesos),
        'max'   => max($pesos),
        'total' => $total
    ];
}