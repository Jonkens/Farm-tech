<?php
/**
 * Consultas para distribución del hato.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

function obtenerDistribucionPorRaza(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT r.name AS breed, COUNT(*) AS total
         FROM animales a
         JOIN razas r ON a.breed_id = r.id
         GROUP BY r.name
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorSexo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT gender, COUNT(*) AS total
         FROM animales
         GROUP BY gender
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorActividad(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT status, COUNT(*) AS total
         FROM animales
         GROUP BY status
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorEspecie(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT ta.name AS animal_type, COUNT(*) AS total
         FROM animales a
         JOIN tipos_animal ta ON a.animal_type_id = ta.id
         GROUP BY ta.name
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorEstablo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT i.name AS facility, COUNT(*) AS total
         FROM animales a
         JOIN instalaciones i ON a.facility_id = i.id
         GROUP BY i.name
         ORDER BY total DESC"
    );
}

function obtenerAnimalesConPeso(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.tag AS code, a.name, r.name AS breed, a.weight_kg AS weight
         FROM animales a
         LEFT JOIN razas r ON a.breed_id = r.id
         WHERE a.weight_kg IS NOT NULL
         ORDER BY a.weight_kg DESC"
    );
}

function obtenerTodosLosAnimales(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.id, a.tag AS code, a.name,
                r.name AS breed, a.weight_kg AS weight,
                a.status, a.gender,
                ta.name AS animal_type, i.name AS facility
         FROM animales a
         LEFT JOIN razas r ON a.breed_id = r.id
         LEFT JOIN tipos_animal ta ON a.animal_type_id = ta.id
         LEFT JOIN instalaciones i ON a.facility_id = i.id"
    );
}