<?php
session_start();
require "datosBaseDatos.php";
if (isset($_SESSION['user_id'])) {
    $idCurrentUser = $_SESSION['user_id'];
}

$conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$queryCourse = "SELECT * FROM cursos WHERE id_creador = $idCurrentUser";
$resultCourse = mysqli_query($conexion, $queryCourse);
$getDataCourse = mysqli_fetch_assoc($resultCourse);
$queryImage = "SELECT * FROM imagenes
WHERE id_curso = {$getDataCourse['id']}";
$resultImage = mysqli_query($conexion, $queryImage);

echo $getDataCourse['id_curso'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Cursos</title>
  <link rel="stylesheet" href="assets/csstyle/style.css">
  <link rel='stylesheet' href='assets/css/bootstrap.min.css'>
  <script src="http://localhost/proyects/AppPhp/assets/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <?php require 'partials/header.php' ?>
  <div class="p-4 marginTop">
    <div class="row row-cols-2 pt-2">
      <?php
while ($rowCourse = mysqli_fetch_assoc($resultCourse) and $rowImage=mysqli_fetch_assoc($resultImage)) {
    ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-4">
          <div class="card h-100 mb-3 tamano-card-seccion-principal">
            <div class="row g-0 h-100">
              <div class="col-md-3 d-flex">
              <img alt="ImageCourse" class="card-img-top light-pink-headline"
                src="data:image/jpg;base64, <?php echo base64_encode($rowImage['imagen']); ?>">
              </div>
              <div class="col-md-9">
                <div class="card-body">
                <div class="d-flex justify-content-center">
                  <h5 class="card-title pb-1">
                    <?php echo $rowCourse['titulo'] ?>
                  </h5>
                </div>
                  <div class="d-flex justify-content-center">
                    <p class="text-justify">
                      <?php echo $rowCourse['descripcion'] ?>
                    </p>
                  </div>
                  <div class="d-flex justify-content-center">
                    <p class="text-justify">
                      <?php echo $rowCourse['instructor'] ?>
                    </p>
                  </div>
                  <div class="d-flex justify-content-center">
                    <p class="text-justify">
                      <?php echo $rowCourse['precio'] ?>
                    </p>
                  </div>
                  <div class="d-flex justify-content-center">
                    <a href="edit-curso.php?edit-curso=<?=$rowCourse['id']?>" class="btn btn-sm text-nowrap btn-primary separationBtns">Editar</a>
                    <a href="functions/delete-curso.php?delete-curso=<?=$rowCourse['id']?>" class="btn btn-sm text-nowrap btn-primary">Eliminar</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br />
      <?php
}
?>
    </div>
  </div>
  <script src='http://localhost/proyects/AppPhp/assets/js/bootstrap.min.js'></script>
</body>

</html>