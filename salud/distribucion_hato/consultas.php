<?php
/**
 * Consultas para distribución del hato.
 * Base de datos: ganaderia2
 */

require_once __DIR__ . '/../../includes/query_helper.php';

function obtenerDistribucionPorRaza(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT b.name AS breed, COUNT(*) AS total
         FROM animals a
         JOIN breeds b ON a.breed_id = b.id
         GROUP BY b.name
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorSexo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT gender, COUNT(*) AS total
         FROM animals
         GROUP BY gender
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorActividad(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT status, COUNT(*) AS total
         FROM animals
         GROUP BY status
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorEspecie(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT at.name AS animal_type, COUNT(*) AS total
         FROM animals a
         JOIN animal_types at ON a.animal_type_id = at.id
         GROUP BY at.name
         ORDER BY total DESC"
    );
}

function obtenerDistribucionPorEstablo(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT f.name AS facility, COUNT(*) AS total
         FROM animals a
         JOIN facilities f ON a.facility_id = f.id
         GROUP BY f.name
         ORDER BY total DESC"
    );
}

function obtenerAnimalesConPeso(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.tag AS code, a.name, b.name AS breed, a.weight_kg AS weight
         FROM animals a
         LEFT JOIN breeds b ON a.breed_id = b.id
         WHERE a.weight_kg IS NOT NULL
         ORDER BY a.weight_kg DESC"
    );
}

function obtenerTodosLosAnimales(PDO $pdo): array
{
    return ejecutarConsulta($pdo,
        "SELECT a.id, a.tag AS code, a.name,
                b.name AS breed, a.weight_kg AS weight,
                a.status, a.gender,
                at.name AS animal_type, f.name AS facility
         FROM animals a
         LEFT JOIN breeds b ON a.breed_id = b.id
         LEFT JOIN animal_types at ON a.animal_type_id = at.id
         LEFT JOIN facilities f ON a.facility_id = f.id"
    );
}