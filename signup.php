<?php

  require 'database.php';

  $message = '';

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $sql = "INSERT INTO users (email, password, name) VALUES (:email, :password, :name)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
      $message = 'User creado exitosamente';
      header('Location: /login.php');
    } else {
      $message = 'Ocurrio un error, intentalo más tarde.';
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>SignUp</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/csstyle/style.css">
    <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
  </head>
  <body>

    <?php require 'partials/header.php' ?>

    <div class="marginTop mx-3">
      <h1>Ingreso</h1>
      <p>Ingresar al Sistema</p>
    </div>

    <div class="container w-25 mt-2">
    <div class="abs-center">
      <form method="POST" action="signup.php" class="border p-3 novalidate" enctype="multipart/form-data">
      <div class="mb-3">
        <input name="id" type="hidden" >
        </div>
        <div class="mb-3">
        <input type="email" placeholder="Email" class="form-control" id="email" aria-describedby="email"
            name="email" required/>
        </div>
        <div class="mb-3">
        <input type="name" placeholder="Nombre" class="form-control" id="name" aria-describedby="name"
            name="name" required/>
        </div>
        <div class="mb-3">
          <input type="password" placeholder="Contraseña" class="form-control" id="password" aria-describedby="password"
            name="password" required/>
        </div>
        <div class="mb-3">
          <input type="password" placeholder="Repetir Contraseña" class="form-control" id="confirm_password" aria-describedby="confirm_password"
            name="confirm_password" required/>
        </div>
        <div class="justify-content-center d-flex mt-2">
          <input name="btn" type="submit" value='Registrarse' class="btn btn-primary">
        </div>
        <div class="justify-content-center d-flex mt-1">
        <span>or <a href="login.php">SignIn</a></span>
        </div>
      </form>
    </div>
  </div>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>

  </body>
</html>
