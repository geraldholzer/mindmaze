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

</head>

<body>
    <div class="container mx-0">
        <div class="row">
            <div class="col bg-light col-3 vh-100">
                <div class="row">
                    <div class="col justify-content-start">
                    <img src="img/iu_de.svg" width="80" height="80" />
                    </div>
                    <div class="col justify-content-end">
                    <img src="img/Logo.svg" width="80" height="80" />

                    </div>
                </div>
                
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Active</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
            <div class="col justify-content-end">


                <?php

                //Prüfen ob die POST-Variablen gesetzt sind
                if (isset($_POST["txtMailLogin"], $_POST["txtPasswordLogin"]) == false) {    //Prüfen ob alle benötigten POST Variablen gesetzt sind
                
                    die("<H1>Hoppla! Da scheint etwas schiefgelaufen zu sein!</H1>"); //Ausgeben einer Fehlermeldung
                
                }

                session_start();
                $_SESSION['mail'] = htmlspecialchars($_POST["txtMailLogin"]);
                $_SESSION['password'] = htmlspecialchars($_POST["txtPasswordLogin"]);

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
                $sql = "SELECT * FROM benutzer WHERE mail = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("s", $_SESSION['mail']);
                $stmt->execute();

                //Zugriff auf Daten des Benutzers
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                if ($row) {
                    if (password_verify($_SESSION['password'], $row['passwort'])) {
                        echo "<div class='row'>
                        <div class='col'>
                            <p>Hallo " . $row['name'] . "!</p>
                        </div>
                        </div>";
                    } else {
                        echo "<p>Das Passwort ist nicht korrekt!</p>";
                    }
                    ;
                } else {
                    echo "<p>Es wurde kein Benutzer gefunden</p>";
                }
                ?>

            </div>
        </div>

    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="#">IU-Mindmaze</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="./index.php">Home</a>
          </li>
          <!-- href auf singleplayer.html hinzugefügt Gerald -->
          <li class="nav-item">
            <a class="nav-link" href="./src/html-php-view/singleplayer.html">Solo</a>
          </li>
            <!-- href auf lobby.html hinzugefügt Gerald -->
          <li class="nav-item">
            <a class="nav-link" href="./src/html-php-view/lobby.html">Multiplayer</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
              aria-expanded="false">
              Konto
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Statistik</a></li>
              <li><a class="dropdown-item" href="#">Fragen</a></li>
              <li><a class="dropdown-item" href="#">Passwort ändern</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Abmelden</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

</body>

</html>