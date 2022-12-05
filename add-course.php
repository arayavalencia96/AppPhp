<?php 
require 'database.php';

session_start();

  if (isset($_SESSION['user_id'])) {
    $idCurrentUser = $_SESSION['user_id'];
  }

if (isset($_POST)) {

    if (!empty($_POST['titulo']) && !empty($_POST['descripcion']) && !empty($_POST['instructor'])
        && !empty($_POST['precio'])) {

        if (file_exists($_FILES['foto']['tmp_name'])) {

            $tipoImagen = $_FILES['foto']['type'];
            $nombreImagen = $_FILES['foto']['name'];
            $tamañoImagen = $_FILES['foto']['size'];
            $imagenSubida = fopen($_FILES['foto']['tmp_name'], 'r');
            $imagenEnBynary = fread($imagenSubida, $tamañoImagen);

            $message = '';
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $sql = "INSERT INTO cursos (titulo, descripcion, instructor, precio,  id_creador, created_at, updated_at) VALUES (:titulo,
            :descripcion, :instructor, :precio, '$idCurrentUser', '$created_at', '$updated_at')";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':titulo', $_POST['titulo']);
            $stmt->bindParam(':descripcion', $_POST['descripcion']);
            $stmt->bindParam(':instructor', $_POST['instructor']);
            $stmt->bindParam(':precio', $_POST['precio']);
            $resultPostForm = $stmt->execute();

            $getLastId = $conn->prepare("SELECT id FROM cursos WHERE cursos.id = ( SELECT MAX(cursos.id) FROM cursos)");
            $getLastId->execute();
            $lastId = $getLastId->fetch(PDO::FETCH_ASSOC)['id'];

            include_once 'datosBaseDatos.php';
            $conexion = mysqli_connect($server, $username, $password, $database);
            $imagenEnBynary = mysqli_escape_string($conexion, $imagenEnBynary);

            $query = "INSERT INTO `apicursosapplication`.`imagenes` (`nombre`, `tipo`, `imagen`, `id_curso`)
            VALUES ('$nombreImagen', '$tipoImagen', '$imagenEnBynary', '$lastId')";

            $resultPostImage = mysqli_query($conexion, $query);

            if ($resultPostForm && $resultPostImage) {
                $message = 'Curso creado exitosamente';
                header("Location: /php-login");
            } else {
                $message = 'Ocurrio un error, intentalo más tarde.';
            }
        }

    }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Agregar curso</title>
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="assets/csstyle/style.css">
  <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
</head>

<body>
  <?php require 'partials/header.php'?>

  <div class="marginTop mx-3">
      <h1>Cursos</h1>
      <p>Crear Curso</p>
    </div>

  <div class="container w-50 mt-2">
    <div class="abs-center">
      <form method="POST" action="add-course.php" class="border p-3 novalidate" enctype="multipart/form-data">
        <div class="mb-3">
          <input type="text" placeholder="Titulo" class="form-control" id="titulo" aria-describedby="titulo"
            name="titulo" required/>
        </div>
        <div class="mb-3">
          <input type="text" placeholder="Descripción" class="form-control" id="descripcion"
            aria-describedby="descripcion" name="descripcion" required/>
        </div>
        <div class="mb-3">
          <input type="text" placeholder="Instructor" class="form-control" id="instructor" aria-describedby="instructor"
            name="instructor" required/>
        </div>
        <div class="mb-3">
          <input type="number" placeholder="Precio" class="form-control" id="precio" aria-describedby="precio"
            name="precio" required/>
        </div>
        <div class="mb-3">
          <input class="form-control" type="file" id="foto" accept=".svg, .png, .jpg" name="foto" required/>
        </div>
        <div class="justify-content-center d-flex">
          <input name="btn" type="submit" value='Guardar' class="btn btn-primary">
        </div>
        <div class="justify-content-center d-flex">
        <?php if (!empty($message)): ?>
        <p> <?=$message?></p>
        <?php endif;?>
        </div>
      </form>
    </div>
  </div>
  <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>
</body>
</html>
