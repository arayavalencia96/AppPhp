<?php
session_start();
require 'database.php';

if (isset($_GET['profile'])) {
    $id = $_GET['profile'];
}

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    $datos = $stmt->fetch();
}

if (isset($_SESSION['user_id'])) {
    $idCurrentUser = $_SESSION['user_id'];
}

$message = '';

if (!empty($_POST['email']) && !empty($_POST['name'])) {
    $sql = "UPDATE users SET name=:name, email=:email WHERE id=$idCurrentUser";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_POST['email']);
    $stmt->bindParam(':name', $_POST['name']);

    if ($stmt->execute()) {
        $message = 'User actualizado exitosamente';
        header("Location: /php-login");
    } else {
        $message = 'Ocurrio un error, intentalo más tarde.';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Actualizar Perfil</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="assets/csstyle/style.css">
    <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
</head>

<body>

    <?php require 'partials/header.php' ?>

    <?php if (!empty($message)): ?>
    <p>
        <?= $message ?>
    </p>
    <?php endif; ?>

    <div class="marginTop mx-3">
      <h1>Cursos</h1>
      <p>Editar User</p>
    </div>

    <div class="container w-25 mt-2">
    <div class="abs-center">
      <form method="POST" action="profile.php" class="border p-3 novalidate" enctype="multipart/form-data">
      <div class="mb-3">
        <input name="id" type="hidden" value="<?= $datos['id']?>">
        <input name="password" type="hidden" value="<?= $datos['password']?>">
        </div>
        <div class="mb-3">
        <input type="email" placeholder="email" class="form-control" id="email" aria-describedby="email"
            name="email" value="<?=$datos['email']?>" required/>
        </div>
        <div class="mb-3">
          <input type="name" placeholder="Nombre" class="form-control" id="name" aria-describedby="name"
            name="name" value="<?=$datos['name']?>" required/>
        </div>
        <div class="justify-content-center d-flex mt-2">
          <input name="btn" type="submit" value='Editar' class="btn btn-primary">
        </div>
        <div class="justify-content-center d-flex mt-3">
            <a href="functions/delete-user.php?delete-user=<?=$idCurrentUser?>">Eliminar Cuenta</a>
        </div>
      </form>
    </div>
  </div>

    <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>
</body>

</html>
