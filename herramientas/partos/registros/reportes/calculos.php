<?php
// herramientas/partos/registros/reportes/calculos.php

/**
 * Devuelve un color hexadecimal para un índice dado (para gráficos).
 */
function obtenerColorPorIndice(int $indice): string {
    $colores = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec489a', '#06b6d4', '#f97316', '#14b8a6'];
    return $colores[$indice % count($colores)];
}

/**
 * Formatea un número con separador de miles.
 */
function formatearNumero(int $numero): string {
    return number_format($numero, 0, ',', '.');
}