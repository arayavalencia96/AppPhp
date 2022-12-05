<?php

session_start();
require '../database.php';
$message = '';

if (isset($_GET['delete-user'])) {
    $id = $_GET['delete-user'];
} else {
    $id = '';
}

$sql = "DELETE FROM users WHERE id=$id";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: /php-login");
}
