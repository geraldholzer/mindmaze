<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="../../img/logo.svg" type="image/svg+xml">
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
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
        $_SESSION['BenutzerID'] = $row['BenutzerID']; // Hinzugefügt von T.S.
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

  <?php include ("navbar.php"); ?>

  <div class="container d-flex justify-content-center mt-4">

    <h2> Willkommen
      <?php echo $_SESSION['Vorname'] ?>
    </h2>

  </div>

  </div>

  <div class="container">
    <div class="row d-flex align-items-stretch justify-content-left">

      <?php if ($_SESSION['ZugriffsrechteID'] == 3) {
        echo '<div class="col-md-4 col-sm-6">
          <div class="card m-5" style="width: 300px; height: 400px;">
            <img class="card-img-top" src="../../img\collector-3930337_1280.jpg" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Benutzerverwaltung</h5>
              <p class="card-text">Zugriffsrechte anpassen</p>
              <a href="userManagement.php" class="btn btn-primary btn-custom">Los!</a>
            </div>
          </div>
        </div>';
      } ?>

      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img/wooden-toys-2606733_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Einzelspieler</h5>
            <p class="card-text">Lerne für dich alleine</p>
            <a href="#" class="btn btn-primary btn-custom">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img/people-2569234_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Supportive Mode</h5>
            <p class="card-text">Lerne zusammen mit einem Partner und korrigiert euch gegenseitig</p>
            <a href="#" class="btn btn-primary btn-custom">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img\checkmate-1511866_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Versus</h5>
            <p class="card-text">Trete gegen einen Gegner an und vergleicht euer Wissen in einem Quiz</p>
            <a href="#" class="btn btn-primary btn-custom">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img\sunset-1807524_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Team</h5>
            <p class="card-text">Testet euer Wissen als Team</p>
            <a href="#" class="btn btn-primary btn-custom">Los!</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-6">
        <div class="card m-5" style="width: 300px; height: 400px;">
          <img class="card-img-top" src="../../img\mannequin-1169852_1280.jpg" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Profil</h5>
            <p class="card-text">Ändere deine Benutzereinstellungen</p>
            <a href="#" class="btn btn-primary btn-custom">Los!</a>
          </div>
        </div>
      </div>





    </div>
  </div>



</body>

</html>
