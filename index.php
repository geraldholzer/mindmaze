<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="img/logo.svg" type="image/svg+xml">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
  <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" type="text/css" href="src/css/style.css">
  <link rel="stylesheet" href="src/css/main.css">
  <script src="src/javascript/index.js"></script>


</head>

<body>

  <div class="container mt-5">
    <div class="row">
      <div class="col text-center">
        <img src="img/Logo.svg" width="15%" />
        <h1 class="mt-4">Willkommen bei MindMaze</h1>
        <p class="lead">Gemeinsam lernen, spielen und gewinnen</p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-6">
        <button onclick="showSection('frmLogin')" class="button-long col-12 mt-3">Anmelden</button>

        <!--Login Formular!-->
        <form class="hidden mb-3" id="frmLogin" action="src\html-php-view\home.php" method="post">
          <div class="row justify-content-center">
            <div class="col-8">
              <div class=row>
                <label for="txtMailLogin">E-Mail</label>
                <input id="txtMailLogin" type="text" name="txtMailLogin">
              </div>
              <div class=row>
                <label for="txtPasswordLogin">Passwort</label>
                <input id="txtPasswordLogin" type="password" name="txtPasswordLogin">
              </div>
              <div class=row>
                <input class="button-short mt-4" type="submit" value="Login">
              </div>
            </div>
          </div>
        </form>

        <div class="text-center pt-5 standard-text-input">
          Noch nicht dabei? <span style="color: #9257DD;"><a href="src\html-php-view\signIn.php">Hier</a></span> registrieren
        </div>

      </div>
    </div>
  </div>

  <!-- IU-Logo ans Seitenende -->
  <div class="container mt-5">
    <div class="row">
      <div class="col text-center">
        <img src="img/iu_de.svg" width="120" height="120" />
      </div>
    </div>
  </div>


  <!--
  <script>
    function showSection(sectionID) {
      if (document.getElementById(sectionID).style.display == "block") {
        document.getElementById(sectionID).style.display = "none";
      } else {
        document.getElementById(sectionID).style.display = "block";
      }
    }
  </script>
  !-->

</body>

</html>
