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

<?php include ("navbar.php"); ?>

<body>
    <?php
    session_start();
    if ($_SESSION['ZugriffsrechteID'] != 3) {
        echo "<h3>Du scheinst nicht über die notwendigen Zugriffsrechte zu verfügen!</h3>";
        die();
    }

    // Verbinde mit der Datenbank
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mindmaze";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
    }

    // Wenn das Formular gesendet wurde
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Aktualisiere die Zugriffsrechte für jeden Benutzer
        foreach ($_POST['ZugriffsrechteID'] as $benutzerID => $zugriffsrechte) {
            $sql = "UPDATE benutzer SET ZugriffsrechteID = $zugriffsrechte WHERE BenutzerID = $benutzerID";
            $con->query($sql);
        }
        echo "<p>Zugriffsrechte wurden erfolgreich aktualisiert!</p>";
    }

    // Setze die Standardwerte für die Suchfelder
    $qryVorname = isset($_GET['Vorname']) ? $_GET['Vorname'] : "";
    $qryNachname = isset($_GET['Nachname']) ? $_GET['Nachname'] : "";
    $qryEmail = isset($_GET['Email']) ? $_GET['Email'] : "";

    // Baue die WHERE-Bedingung basierend auf den eingereichten Formularwerten auf
    $whereClause = "WHERE 1"; // Initialisiere die WHERE-Klausel mit 1, um alle Datensätze zu erhalten
    
    // Füge die Bedingungen für Vorname, Nachname und E-Mail hinzu, wenn sie nicht leer sind
    if (!empty($qryVorname)) {
        $whereClause .= " AND Vorname = '" . $qryVorname . "'";
    }
    if (!empty($qryNachname)) {
        $whereClause .= " AND Nachname = '" . $qryNachname . "'";
    }
    if (!empty($qryEmail)) {
        $whereClause .= " AND Email = '" . $qryEmail . "'";
    }

    // Führe die SQL-Abfrage mit der WHERE-Klausel aus
    $sql = "SELECT * FROM benutzer $whereClause";
    $result = $con->query($sql);
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

                <form action="userManagement.php" method="GET">
                    <label for="Vorname">Vorname:</label><br>
                    <input type="text" id="Vorname" name="Vorname"><br><br>

                    <label for="Nachname">Nachname:</label><br>
                    <input type="text" id="Nachname" name="Nachname"><br><br>

                    <label for="Email">E-mail:</label><br>
                    <input type="text" id="Email" name="Email"><br><br>

                    <input type="submit" value="Absenden">
                </form>
            </div>
            <div class="col-1"> </div>
        </div>
    </div>

</body>

</html>