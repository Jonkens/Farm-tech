<?php
/**
 * Funciones de cálculo para el registro de animales.
 * No dependen de la base de datos, solo transforman datos.
 */

/**
 * Calcula la edad en años a partir de una fecha de nacimiento.
 * @param string|null $fechaNacimiento Formato Y-m-d
 * @return string Edad formateada o 'N/D'
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

/**
 * Formatea una fecha para mostrar (d/m/Y).
 * @param string|null $fecha
 * @return string
 */
function formatearFecha($fecha)
{
    if (empty($fecha)) {
        return 'No disponible';
    }
    return date('d/m/Y', strtotime($fecha));
}

/**
 * Obtiene un icono representativo según el nombre del tipo de animal.
 * @param string $tipoNombre
 * @return string Emoji o texto
 */
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