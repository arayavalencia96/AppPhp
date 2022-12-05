<?php

  require 'database.php';

  if (isset($_SESSION['user_id'])) {
    $idCurrentUser = $_SESSION['user_id'];
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
<header>
<nav class="navbar navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="/php-login">LearnGeeks</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasDarkNavbar"
          aria-controls="offcanvasDarkNavbar"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div
          class="offcanvas offcanvas-end text-bg-dark"
          tabindex="-1"
          id="offcanvasDarkNavbar"
          aria-labelledby="offcanvasDarkNavbarLabel"
        >
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">
            LearnGeeks
            </h5>
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            ></button>
          </div>
          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <?php if(!empty($user)): ?>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="logout.php"
                  >Logout</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" aria-current="page">Welcome <?= $user['name']; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="profile.php?profile=<?=$idCurrentUser?>">Editar Perfil</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="add-course.php">Crear Curso</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../coursesListOwn.php">Ver mis Cursos</a>
              </li>
              <?php else: ?>
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="login.php"
                  >SignIn</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="signup.php"
                  >SignUp</a
                >
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
    </nav>
</header>
