<?php
/**
 * Archivo de conexión a PostgreSQL 16
 * Configura los parámetros de conexión y devuelve un objeto PDO
 */

// Parámetros de conexión (cámbialos según tu entorno)
$host = 'localhost';         // Servidor de base de datos
$port = '5432';              // Puerto de PostgreSQL
$dbname = 'ganaderia2';   // Nombre de la base de datos
$user = 'postgres';        // Usuario
$password = 'software'; // Contraseña

// DSN para PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

try {
    // Crear conexión PDO con opciones para manejo de errores
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    // Opcional: establecer el esquema por defecto
    // $pdo->exec("SET search_path TO public");
} catch (PDOException $e) {
    // En producción, maneja el error sin mostrar detalles al usuario
    error_log($e->getMessage());
die('Error al conectar con la base de datos.');
}
?>