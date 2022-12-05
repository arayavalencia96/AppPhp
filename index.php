<?php
  session_start();

  require 'database.php';

  if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, email, password, name FROM users WHERE id = :id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

    if (count($results) > 0) {
      $user = $results;
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>LearnGeeks</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/csstyle/style.css">
    <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
  </head>
  <body>
    <?php require 'partials/header.php' ?>
    <?php require 'coursesList.php' ?>
    <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>
  </body>
</html>
