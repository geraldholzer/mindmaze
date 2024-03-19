
  <!--Navbar Anfang-->
  <!-- Sticky top damit navi immer oben bleibt -->
  <nav class="navbar navbar-expand-lg custom-navbar">
    <script>

        function navbarReact(aSite){
          <?php 
         // $_SESSION['inGame']=false;
          if ($_SESSION['inGame']==false){
            echo "document.location.href = aSite;";
          }else{
            echo "alert('Du bist im Spiel!')";
          }
          ?>
          
        }
    </script>
    <div class="container">


    <div class="d-flex align-items-center">

      <a class="navbar-brand" href="#">
        <img src="../../img/logo.svg" alt="Logo" height="40" class="me-3">

      <a class="navbar-brand">IU-Mindmaze</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="javascript:navbarReact('./home.php')">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:navbarReact('./singleplayer.php')">Solo</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:navbarReact('./lobby.php')">Multiplayer</a>
          </li>
          <li class="nav-item dropdown">  
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Konto
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="javascript:navbarReact('./profile.php')">Profil</a></li>
              <li><a class="dropdown-item" href="javascript:navbarReact('./statistik.php')">Statistik</a></li>
              <li><a class="dropdown-item" href="javascript:navbarReact('./questions.php')">Fragen</a></li>
              <li><a class="dropdown-item" href="javascript:navbarReact('./profile.html')">TEST</a></li>
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
