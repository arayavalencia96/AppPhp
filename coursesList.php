<?php
include_once "datosBaseDatos.php";

$conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
$queryImage = "SELECT `nombre`, `tipo`, `imagen` FROM `apicursosapplication`.`imagenes`";
$queryIdCourses = "SELECT `titulo`, `descripcion`, `instructor`, `precio` FROM `apicursosapplication`.`cursos`";
$resultImage = mysqli_query($conexion, $queryImage);
$resultIdCourses = mysqli_query($conexion, $queryIdCourses);
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
  <div class="p-4 marginTop">
    <div class="row row-cols-2 pt-2">
      <?php
while ($rowCourse = mysqli_fetch_assoc($resultIdCourses) and $rowImage = mysqli_fetch_assoc($resultImage)) {
    ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-4">
          <div class="card h-100 mb-3 tamano-card-seccion-principal">
            <div class="row g-0 h-100">
              <div class="col-md-3 d-flex">
              <img alt="ImageCourse" class="card-img-top light-pink-headline"
                src="data:image/jpg;base64, <?php echo base64_encode($rowImage['imagen']); ?>">
              </div>
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
                    <a class="btn btn-sm text-nowrap btn-primary">Ver Curso</a>
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