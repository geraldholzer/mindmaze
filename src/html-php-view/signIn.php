<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="../../img/logo.svg" type="image/svg+xml">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anmeldung</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" href="../css/main.css">
  <style>
    /* Anpassung der Logos */
    #iu-logo {
      width: 120px;
      height: 120px;
      margin-top: 20px;
    }

    #mm-logo {
      width: 120px;
      height: 120px;
      margin-top: 20px;
    }
  </style>
</head>

<body>

  <div class="container mt-5">
    <div class="row">
      <div class="col text-center">
        <img src="../../img/Logo.svg" width="15%" />
        <h1 class="mt-4">Los geht's</h1>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-6">
        
        <!--Register Formular!-->
        <button onclick="showSection('frmRegister')" class="button-long col-12 mt-3">Jetzt Registrieren</button>
        <br>
        <form class="hidden mb-3" id="frmRegister" action="register.php" method="post">
          <div class="row justify-content-center">
            <div class="col-8">
              <div class="row">
                <label for="txtFirstNameRegister">Vorname</label>
                <input id="txtFirstNameRegister" type="text" name="txtFirstNameRegister">
              </div>
              <div class="row">
                <label for="txtLastNameRegister">Nachname</label>
                <input id="txtLastNameRegister" type="text" name="txtLastNameRegister">
              </div>
              <div class="row">
                <label for="txtMailRegister">E-Mail</label>
                <input id="txtMailRegister" type="text" name="txtMailRegister">
              </div>
              <div class="row">
                <label for="txtPasswordRegister">Passwort</label>
                <input id="txtPasswordRegister" type="password" name="txtPasswordRegister">
              </div>
              <div class="row">
                <label for="selectStudiengang">Studiengang</label>
                <select id="selectStudiengang" name="selectStudiengang">
                  <?php
                  // Einrichten der Datenbankverbindung
                  // $servername = "localhost";
                  // $username = "root";
                  // $password = "";
                  // $dbname = "mindmaze";
                  include "../html-php-view/dbconnect.php";
                  $con = new mysqli($servername, $username, $password, $dbname);
                  if ($con->connect_error) {
                      die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
                  }
                  // Durchführen der SQL Abfrage
                  $sql = "SELECT * FROM studiengang";
                  $result = $con->query($sql);
                  if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                          echo "<option value='" . $row["StudiengangID"] . "'>" . $row["Beschreibung"] . "</option>";
                      }
                  } else {
                      echo "Keine Studiengänge gefunden";
                  }
                  $con->close();
                  ?>
                </select>
              </div>
              <div class="row">
                <input class="button-short mt-4" type="submit" value="Registrieren">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- IU-Logo ans Seitenende -->
  <div class="container mt-5">
    <div class="row">
      <div class="col text-center">
        <img src="../../img/iu_de.svg" width="120" height="120" />
      </div>
    </div>
  </div>

  <script>
    function showSection(sectionID) {
      var section = document.getElementById(sectionID);
      if (section.style.display == "block") {
        section.style.display = "none";
      } else {
        section.style.display = "block";
      }
    }
  </script>

</body>

</html>
