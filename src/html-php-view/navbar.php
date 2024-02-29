
  <!--Navbar Anfang-->
  <!-- Sticky top damit navi immer oben bleibt -->
  <nav class="navbar navbar-expand-lg custom-navbar">
    <div class="container">


      <a class="navbar-brand">IU-Mindmaze</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="./home.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./singleplayer.html">Solo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./lobby.html">Multiplayer</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Konto
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Profil</a></li>
              <li><a class="dropdown-item" href="statistik.php">Statistik</a></li>
              <li><a class="dropdown-item" href="#">Fragen</a></li>
              <?php if ($_SESSION['ZugriffsrechteID'] == 3) {
                echo '<li><a class="dropdown-item" href="userManagement.php">Benutzerverwaltung</a></li>';
              } ?>

            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../server/logout.php">Abmelden</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
