<?php
/**
 * Funciones de cálculo para el módulo financiero.
 */

function calcularGanancia(float $ingresos, float $gastos): float
{
    return $ingresos - $gastos;
}