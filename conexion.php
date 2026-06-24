<?php
$host = 'localhost';         // Servidor de base de datos
$port = '5432';              // Puerto de PostgreSQL
$dbname = 'ganaderia2';   // Nombre de la base de datos
$user = 'postgres';        // Usuario
$password = 'software'; // Contraseña

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

try {
    
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    
    error_log($e->getMessage());
    die('Error al conectar con la base de datos.');
}
