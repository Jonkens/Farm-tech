<?php
// herramientas/partos/registros/registrar-evento/calculos.php

/**
 * Devuelve las clases CSS para el badge según el tipo de evento.
 */
function obtenerBadgeColorEvento(string $tipo): string {
    return match($tipo) {
        'Celo'                  => 'bg-pink-100 text-pink-700',
        'Inseminación'          => 'bg-purple-100 text-purple-700',
        'Monta'                 => 'bg-amber-100 text-amber-700',
        'Confirmación de preñez'=> 'bg-green-100 text-green-700',
        default                 => 'bg-gray-100 text-gray-700'
    };
}