<?php
require 'conexion.php';

$id = $_POST['id'];
$nombre = trim($_POST['nombre']);
$tipo = trim($_POST['tipo']);
$raza = trim($_POST['raza']);
$peso = $_POST['peso'];
$edad = $_POST['edad'];

$sql = "UPDATE vacas 
        SET nombre = ?, tipo = ?, raza = ?, peso = ?, edad = ?
        WHERE id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$nombre, $tipo, $raza, $peso, $edad, $id]);

header("Location: index.php");
exit;
?>