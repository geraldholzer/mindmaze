<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="src/css/style.css">
    <link rel="stylesheet" href="src/css/main.css">

</head>

<script>
  function showSection(sectionID) {
    if (document.getElementById(sectionID).style.display == "block") {
      document.getElementById(sectionID).style.display = "none";
    }
    else {
      document.getElementById(sectionID).style.display = "block";
    }
  }
</script>


<style>

</style>


<body>
  


  <div class="container">
    
    <div class="row mt-2">
      
      <div class="col">
        <img src="img/iu_de.svg" width="120" height="120" />
      </div>
      <div class="col d-flex justify-content-end">
        <img src="img/Logo.svg" width="120" height="120" />
      </div>

    </div>
    
    <div class="row justify-content-center">
      <div class="col-6">
        <h1>Los geht's</h1>
        <h3 class="mt-5">Willkommen bei MindMaze</h3>
        <button onclick="showSection('frmLogin')" class="button-long col-12 mt-3">Anmelden</button>
        <br>


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
                <input id="txtPasswordLogin" type="text" name="txtPasswordLogin">
              </div>

              <div class=row>
                <input class="button-short mt-4" type="submit" value="Login">
              </div>

            </div>
          </div>
        </form>

        <div class="pt-5 standard-text-input">Noch nicht dabei? <a href="src\html-php-view\signIn.php">Hier </a> registrieren</div>
        <!--Register Formular!
        <h3 class="mt-5">Noch nicht dabei?</h3>
        <button onclick="showSection('frmRegister')" class="btn col-12 mt-3">Jetzt Registrieren</button>
        <br>
        <form class="hidden" id="frmRegister" action="register.php" method="post">
          <div class="row justify-content-center">
            <div class="col-8">

              <div class=row>
                <label for="txtNameRegister">Benutzername</label>
                <input id="txtNameRegister" type="text" name="txtNameRegister">
              </div>

              <div class=row>
                <label for="txtMailRegister">E-Mail</label>
                <input id="txtMailRegister" type="text" name="txtMailRegister">
              </div>

              <div class=row>
                <label for="txtPasswordRegister">Passwort</label>
                <input id="txtPasswordRegister" type="text" name="txtPasswordRegister">
              </div>

              <div class=row>
                <input class="mt-4 btn" type="submit" value="Registrieren">
              </div>

            </div>
          </div>
        </form>
        !-->


      </div>
      </div>
    <div class="row">
    </div>
    <div class="row">
    </div>
    <div class="row">
    </div>
    <div class="row">
    </div>
  </div>
</body>


</html>