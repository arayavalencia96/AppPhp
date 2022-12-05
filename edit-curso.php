<?php
session_start();
require 'database.php';
$updated_at = date('Y-m-d H:i:s');
if (isset($_GET['edit-curso'])) {
    $id = $_GET['edit-curso'];
} else {
    $id = '';
}

$sql = "SELECT * FROM cursos WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    $datos = $stmt->fetch();
}


$message = '';

function submit($id, $message)
{
    require 'database.php';
    
    if (!empty($_POST['titulo']) && !empty($_POST['descripcion']) && !empty($_POST['instructor'])
    && !empty($_POST['precio'])) {

    if (file_exists($_FILES['foto']['tmp_name'])) {

        $tipoImagen = $_FILES['foto']['type'];
        $nombreImagen = $_FILES['foto']['name'];
        $tamañoImagen = $_FILES['foto']['size'];
        $imagenSubida = fopen($_FILES['foto']['tmp_name'], 'r');
        $imagenEnBynary = fread($imagenSubida, $tamañoImagen);


        $sql = "UPDATE cursos SET titulo=:titulo, descripcion=:descripcion, instructor=:instructor,
            precio=:precio, updated_at=:updated_at WHERE id=$id ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':titulo', $_POST['titulo']);
        $stmt->bindParam(':descripcion', $_POST['descripcion']);
        $stmt->bindParam(':instructor', $_POST['instructor']);
        $stmt->bindParam(':precio', $_POST['precio']);
        $stmt->bindParam(':updated_at', $_POST['updated_at']);

        include_once 'datosBaseDatos.php';
        $conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
        $imagenEnBynary = mysqli_escape_string($conexion, $imagenEnBynary);

        $query = "UPDATE imagenes SET nombre='$nombreImagen',
        tipo='$tipoImagen', imagen='$imagenEnBynary' WHERE id_curso=$id";

        if ($stmt->execute() and mysqli_query($conexion, $query)) {
            $message = 'Curso actualizado exitosamente';
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
      <p>Editar Curso</p>
    </div>

    <div class="container w-50 mt-2">
    <div class="abs-center">
      <form method="POST" action="<?=submit($datos['id'], 'Curso Actualizado')?>" class="border p-3 novalidate" enctype="multipart/form-data">
      <div class="mb-3">
      <input name="id" type="hidden" value="<?=$datos['id']?>">
      <input name="id_creador" type="hidden" value="<?=$datos['id_creador']?>">
      <input name="created_at" type="hidden" value="<?=$updated_at?>">
      <input name="updated_at" type="hidden" value="<?= $datos['updated_at'] ?>">
        </div>
      <div class="mb-3">
          <input type="text" placeholder="Titulo" class="form-control" id="titulo" aria-describedby="titulo"
            name="titulo" value="<?=$datos['titulo']?>" required/>
        </div>
        <div class="mb-3">
          <input type="text" placeholder="Descripción" class="form-control" id="descripcion"
            aria-describedby="descripcion" name="descripcion" value="<?=$datos['descripcion']?>" required/>
        </div>
        <div class="mb-3">
          <input type="text" placeholder="Instructor" class="form-control" id="instructor" aria-describedby="instructor"
            name="instructor" value="<?=$datos['instructor']?>" required/>
        </div>
        <div class="mb-3">
          <input type="number" placeholder="Precio" class="form-control" id="precio" aria-describedby="precio"
            name="precio" value="<?=$datos['precio']?>" required/>
        </div>
        <div class="mb-3">
          <input class="form-control" type="file" id="foto" accept=".svg, .png, .jpg" name="foto" required/>
        </div>
        <div class="justify-content-center d-flex">
          <input name="btn" type="submit" value='Editar' class="btn btn-primary">
        </div>
        <div class="justify-content-center d-flex">
        </div>
      </form>
    </div>
  </div>

  <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>

</body>

</html>