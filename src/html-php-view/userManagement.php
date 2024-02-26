<!DOCTYPE html>
<html lang="de">

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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Konto
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><a class="dropdown-item" href="#">Statistik</a></li>
                        <li><a class="dropdown-item" href="#">Fragen</a></li>
                        
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../server/logout.php">Abmelden</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<body>
    <?php
    session_start();
    if ($_SESSION['ZugriffsrechteID'] != 3) {
        echo "<h3>Du scheinst nicht über die notwendigen Zugriffsrechte zu verfügen!</h3>";
        die();
    }

    // Wenn das Formular gesendet wurde
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verbinde mit der Datenbank
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "mindmaze";
        $con = new mysqli($servername, $username, $password, $dbname);
        if ($con->connect_error) {
            die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
        }

        // Aktualisiere die Zugriffsrechte für jeden Benutzer
        foreach ($_POST['ZugriffsrechteID'] as $benutzerID => $zugriffsrechte) {
            $sql = "UPDATE benutzer SET ZugriffsrechteID = $zugriffsrechte WHERE BenutzerID = $benutzerID";
            $con->query($sql);
        }
        echo "<p>Zugriffsrechte wurden erfolgreich aktualisiert!</p>";
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-1"> </div>
            <div class="col-10">
                <h1 class="mt-3">Benutzer</h1>
                <form class="mt-3" method="post">
                    <table class='table table-striped'>
                        <tr>
                            <th>ID</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>E-mail</th>
                            <th>Zugriffsrechte</th>
                        </tr>

                        <?php
                        // Verbinde mit der Datenbank
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "mindmaze";
                        $con = new mysqli($servername, $username, $password, $dbname);
                        if ($con->connect_error) {
                            die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
                        }

                        // Lade die Benutzer aus der Datenbank
                        $sql = "SELECT * FROM benutzer";
                        $result = $con->query($sql);



                        // Zeige die Benutzer und ihre aktuellen Zugriffsrechte an
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["BenutzerID"] . "</td>";
                            echo "<td>" . $row["Vorname"] . "</td>";
                            echo "<td>" . $row["Nachname"] . "</td>";
                            echo "<td>" . $row["Email"] . "</td>";
                            echo "<td>";
                            echo "<select name='ZugriffsrechteID[" . $row["BenutzerID"] . "]'>";
                            echo "<option value='1' " . ($row["ZugriffsrechteID"] == 1 ? "selected" : "") . ">Nutzer</option>";
                            echo "<option value='2' " . ($row["ZugriffsrechteID"] == 2 ? "selected" : "") . ">Tutor</option>";
                            echo "<option value='3' " . ($row["ZugriffsrechteID"] == 3 ? "selected" : "") . ">Admin</option>";
                            echo "</select>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <button class="button-short" type="submit">Änderungen bestätigen</button>
                </form>
            </div>
            <div class="col-1"> </div>
        </div>
    </div>

</body>

</html>