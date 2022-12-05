
<?php

  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: /php-login');
  }
  require 'database.php';

  if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (is_countable($results) && count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
      $_SESSION['user_id'] = $results['id'];
      header("Location: /php-login");
    } else {
      $message = 'Correo o Email invalido';
    }
  }

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/csstyle/style.css">
    <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
  </head>
  <body>
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>
    

    <div class="marginTop mx-3">
      <h1>Ingreso</h1>
      <p>Ingresar al Sistema</p>
    </div>

    <div class="container w-25 mt-2">
    <div class="abs-center">
      <form method="POST" action="login.php" class="border p-3 novalidate" enctype="multipart/form-data">
      <div class="mb-3">
        <input name="id" type="hidden" >
        <input name="name" type="hidden" >
        </div>
        <div class="mb-3">
        <input type="email" placeholder="Email" class="form-control" id="email" aria-describedby="email"
            name="email" required/>
        </div>
        <div class="mb-3">
          <input type="password" placeholder="Contraseña" class="form-control" id="password" aria-describedby="password"
            name="password" required/>
        </div>
        <div class="justify-content-center d-flex mt-2">
          <input name="btn" type="submit" value='Ingresar' class="btn btn-primary">
        </div>
        <div class="justify-content-center d-flex mt-1">
        <span>or <a href="signup.php">SignUp</a></span>
        </div>
      </form>
    </div>
  </div>


    <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>
  </body>
</html>
