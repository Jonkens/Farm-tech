<?php

$uploadDir = 'uploads/';

$host = 'localhost';
$db   = 'ganado_db';
$user = 'postgres';
$pass = 'software';
$port = "5432";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
} catch (PDOException $e) {
    die("Error BD");
}

$nombre = $_POST['nombre'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$raza = $_POST['raza'] ?? '';
$edad = $_POST['edad'] ?? '';
$peso = $_POST['peso'] ?? '';
$ubicacion = $_POST['ubicacion'] ?? '';

if (
    $nombre && $tipo && $raza &&
    $edad && $peso && $ubicacion &&
    isset($_FILES['imagen'])
) {

    $tmp = $_FILES['imagen']['tmp_name'];
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombreImg = uniqid('vaca_') . '.' . $ext;
    $ruta = $uploadDir . $nombreImg;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($tmp, $ruta)) {

        $sql = "INSERT INTO vacas 
                (nombre, tipo, raza, edad, peso, ubicacion, imagen, fecha_registro) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $nombre,
            $tipo,
            $raza,
            $edad,
            $peso,
            $ubicacion,
            $ruta
        ]);
    }
}

header("Location: index.php");
exit();