<?php
/**
 * Funciones de cálculo y utilidades para el módulo de salud.
 */

/**
 * Devuelve la clase CSS para la badge según el estado de salud.
 */
function getBadgeClass(string $estado): string
{
    $map = [
        'Excelente'      => 'badge-excelente',
        'Bueno'          => 'badge-bueno',
        'Regular'        => 'badge-regular',
        'Enfermo'        => 'badge-enfermo',
        'En observación' => 'badge-observacion'
    ];
    return $map[$estado] ?? 'badge-default';
}

/**
 * Formatea una fecha a d/m/Y.
 */
function formatearFecha(?string $fecha): string
{
    if (empty($fecha)) return '—';
    return date('d/m/Y', strtotime($fecha));
}