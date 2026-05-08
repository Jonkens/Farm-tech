<?php
/**
 * Cálculos puros para comparativa mensual.
 */

function calcularCambioPorcentual(float $actual, float $anterior): float
{
    if ($anterior == 0) return $actual > 0 ? 100 : 0;
    return round(($actual - $anterior) / $anterior * 100, 1);
}

function calcularEficienciaHuevos(int $huevosAnuales, int $gallinas, int $dias = 365): float
{
    if ($gallinas <= 0 || $huevosAnuales <= 0) return 0;
    return round(($huevosAnuales / $dias) / $gallinas * 100);
}