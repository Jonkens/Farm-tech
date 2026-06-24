<?php
/**
 * Funciones de cálculo para el registro de animales.
 * No dependen de la base de datos, solo transforman datos.
 */

function calcularEdad($fechaNacimiento)
{
    if (empty($fechaNacimiento)) {
        return 'N/D';
    }
    $timestamp = strtotime($fechaNacimiento);
    if ($timestamp === false) {
        return 'N/D';
    }
    $edad = floor((time() - $timestamp) / (365.25 * 24 * 3600));
    return $edad . ' años';
}

function formatearFecha($fecha)
{
    if (empty($fecha)) {
        return 'No disponible';
    }
    return date('d/m/Y', strtotime($fecha));
}

function obtenerIconoTipo($tipoNombre)
{
    switch ($tipoNombre) {
        case 'Bovino': return '🐄';
        case 'Ovino':  return '🐑';
        case 'Porcino': return '🐖';
        case 'Caprino': return '🐐';
        case 'Ave':     return '🐔';
        default:        return '🐾';
    }
}