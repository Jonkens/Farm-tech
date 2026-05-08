<?php
/**
 * Funciones de cálculo para el panel diario.
 * No dependen de la base de datos, solo transforman datos.
 */

const PESOS_PROMEDIO = [
    'Bovino'  => 250,
    'Porcino' => 80,
    'Ovino'   => 25,
    'Caprino' => 20,
];

function calcularTotalAnimalesSemana(array $sacrificiosSemana): int
{
    return array_sum(array_column($sacrificiosSemana, 'total'));
}

function calcularTotalKgCarne(array $sacrificiosSemana): float
{
    $total = 0;
    foreach ($sacrificiosSemana as $tipo) {
        $cantidad = (int) $tipo['total'];
        $peso = PESOS_PROMEDIO[$tipo['animal_type']] ?? 0;
        $total += $cantidad * $peso;
    }
    return $total;
}

function llenarDatos(array $fechas, array $datosCrudos): array
{
    $resultado = [];
    foreach ($fechas as $f) {
        $resultado[] = $datosCrudos[$f] ?? 0;
    }
    return $resultado;
}

function calcularPromedioDiario($totalSemana, int $dias = 7): float
{
    return $dias > 0 ? $totalSemana / $dias : 0;
}

function calcularEficienciaGallinas(int $huevosSemana, int $gallinas, int $dias = 7): float
{
    if ($gallinas <= 0) return 0;
    return round(($huevosSemana / $dias) / $gallinas * 100);
}