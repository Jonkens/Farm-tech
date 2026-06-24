<?php
// herramientas/partos/registros/genealogia/calculos.php

/**
 * Devuelve una clase CSS para el sexo del animal.
 */
function obtenerClaseSexo(string $sexo): string {
    return $sexo === 'M' ? 'border-blue-400' : 'border-pink-400';
}

/**
 * Devuelve un icono para el sexo del animal.
 */
function obtenerIconoSexo(string $sexo): string {
    return $sexo === 'M' ? '🐂' : '🐄';
}

/**
 * Devuelve el color de fondo para el sexo del animal.
 */
function obtenerColorSexo(string $sexo): string {
    return $sexo === 'M' ? 'bg-blue-50 hover:bg-blue-100' : 'bg-pink-50 hover:bg-pink-100';
}