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

  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" href="../css/main.css">

</head>

<body>



  <?php

  //Prüfe ob die POST-Variablen und SESSION-Variablen gesetzt sind
  session_start();                                                        //Session starten
  $enterHome = false; //über diese Variable wird später geprüft, ob die Startseite aufgebaut wird. Zu Beginn ist sie false und kann im Verlauf der Prüfungen true werden
  $loginCheck = false; //über diese Variable wird geprüft, ob ein Abgleich von Passwort und E-Mail erfolgen soll (Login-Check)
  
  if (isset($_SESSION['Email']) && !empty($_SESSION['Email'])) {
    //Wenn die Session Variablen gesetzt sind, handelt es sich um einen Refresh bzw. erneuten Seitenaufruf nach einem erfolgreichem Login. In diesem Fall wird kein erneuter Login-Check durchgeführt
    //Um die Sicherheit zu erhöhen, könnte ggf. nochmal die SESSION Email und das SESSION PW verglichen werden
    $enterHome = true;
  }

  if (isset($_POST["txtMailLogin"], $_POST["txtPasswordLogin"])) {
    //Wenn die POST-Variablen belegt sind, wird ein erneuter Login-Check durchgeführt, unabhängig von der Belegung der Session Variablen
    $loginCheck = true;
    $enterHome = false;
  }

  if ($loginCheck == false && $enterHome == false) {
    //Hier wird geprüft, ob die Seite ohne Login aufgerufen wird, z.B. über den direkten Link. In dem Fall wird der Seitenaufbau abgebrochen
    die("<H1>Hoppla! Da scheint etwas schiefgelaufen zu sein!</H1>"); //Ausgeben einer Fehlermeldung
  }


  if ($loginCheck) {
    //Einrichten der Datenbankverbindung
    $servername = "localhost";                                                                      //Server der Datenbank
    $username = "root";                                                                             //Benutzer der Datenbank
    $password = "";                                                                                 //Passwort der Datenbank
    $dbname = "mindmaze";                                                                           //Name der Datenbank
    $con = new mysqli($servername, $username, $password, $dbname);                                  //Initialisieren der Datenbankverbindung
    if ($con->connect_error) {                                                                      //Prüfe ob die Verbindung zur DB fehlgeschlagen ist
      die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);    //Ausgeben einer Fehlermeldung
    }

    //Durchführen der SQL Abfrage
    $sql = "SELECT * FROM benutzer WHERE Email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $_POST['txtMailLogin']);
    $stmt->execute();

    //Zugriff auf Daten des Benutzers zum Vergleichen des Benutzernamens und Passwort
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row) {
      if (password_verify($_POST['txtPasswordLogin'], $row['Passwort'])) {
        //Wenn Passwort korrekt => Belege die Session-Variablen und setze $enterHome auf true
        $_SESSION['Vorname'] = $row['Vorname'];
        $_SESSION['Nachname'] = $row['Nachname'];
        $_SESSION['Email'] = htmlspecialchars($_POST["txtMailLogin"]);
        $_SESSION['Passwort'] = htmlspecialchars($_POST["txtPasswordLogin"]);
        $_SESSION['ZugriffsrechteID'] = $row['ZugriffsrechteID'];
        $_SESSION['StudiengangID'] = $row['StudiengangID'];
        $enterHome = true;
      } else {
        echo "<p>Das Passwort ist nicht korrekt!</p>";
      }
    } else {
      echo "<p>Es wurde kein Benutzer gefunden</p>";
    }


  }

  if ($enterHome == false) {
    die();
  }





  ?>


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
              <li><a class="dropdown-item" href="#">Statistik</a></li>
              <li><a class="dropdown-item" href="#">Fragen</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Abmelden</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container d-flex justify-content-center mt-4">
    <h1> Willkommen
      <?php echo $_SESSION['Vorname'] ?>
      <h2>

  </div>

  </div>

  <div class="container">
    <div class="row d-flex align-items-stretch justify-content-left">
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img/wooden-toys-2606733_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Einzelspieler</h5>
            <p class="card-text">Lerne für dich alleine</p>
            <a href="#" class="btn btn-primary">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img/people-2569234_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Supportive Mode</h5>
            <p class="card-text">Lerne zusammen mit einem Partner und korrigiert euch gegenseitig</p>
            <a href="#" class="btn btn-primary">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img\checkmate-1511866_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Versus</h5>
            <p class="card-text">Trete gegen einen Gegner an und vergleicht euer Wissen in einem Quiz</p>
            <a href="#" class="btn btn-primary">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img\sunset-1807524_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Team</h5>
            <p class="card-text">Testet euer Wissen als Team</p>
            <a href="#" class="btn btn-primary">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img\mannequin-1169852_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Profil</h5>
            <p class="card-text">Ändere deine Benutzereinstellungen</p>
            <a href="#" class="btn btn-primary">Los!</a>
          </div>
        </div>
      </div>
    </div>
  </div>



</body>

</html>