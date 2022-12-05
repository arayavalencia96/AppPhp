<?php

session_start();
require '../database.php';
$message = '';

if (isset($_GET['delete-curso'])) {
    $id = $_GET['delete-curso'];
} else {
    $id = '';
}

$sql = "DELETE FROM cursos WHERE id=$id";
$stmt = $conn->prepare($sql);

include_once '../datosBaseDatos.php';
$conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$query = "DELETE FROM imagenes WHERE id_curso=$id";

if ($stmt->execute() and mysqli_query($conexion, $query)) {
    header("Location: /php-login");
}
