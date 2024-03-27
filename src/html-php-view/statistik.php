<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <!-- Popper.js and Bootstrap JS CDN links -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
</head>

<body>

<?php

session_start(); // Session starten (vor Aufruf der Navbar!)
$_SESSION['inGame']=false;//Wird benötigt um navbar zu aktivieren 
include 'navbar.php';

// Datenbankverbindung
include "../html-php-view/dbconnect.php";
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Es konnte keine Verbindung zur Datenbank hergestellt werden: " . $con->connect_error);
}

// Überprüfen, ob Benutzer eingeloggt ist
if(isset($_SESSION['BenutzerID'])) {

    // Benutzer-ID abrufen
    $benutzerID = $_SESSION['BenutzerID'];

    // SQL-Abfrage, um Gesamtsumme der beantworteten Fragen und korrekten Antworten für jeden Spielmodus abzurufen
    $sql_statistics = "SELECT m.Beschreibung AS Spielmodus, SUM(s.Fragenzahl) AS GesamtFragen, SUM(s.Punkte) AS GesamtPunkte
                        FROM statistik s
                        INNER JOIN spielmodi m ON s.SpielmodiID = m.SpielmodiID
                        WHERE s.BenutzerID = ?
                        GROUP BY s.SpielmodiID";
    $stmt = $con->prepare($sql_statistics);
    $stmt->bind_param("i", $benutzerID);
    $stmt->execute();
    $result_statistics = $stmt->get_result();

    if ($result_statistics->num_rows > 0) {  // Ausgabe Statistik, falls Einträge in der DB vorhanden sind
        echo "<div class='container text-center'>";
        echo "<h1 class='statistikheader'><span>Statistik für " . $_SESSION['Vorname'] . " " . $_SESSION['Nachname'] . "</span></h1>";
        echo "</div>";
        echo "<div class='tableLobby'>";
        echo "<table>";
        echo "<thead class='tableLobby'><tr><th>Spielmodus</th><th>Fragen Gesamt</th><th>Erreichte Punkte</th><th>Punkte in %</th></tr></thead>";
        echo "<tbody class='tableLobby'>";
        while ($row_statistics = $result_statistics->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row_statistics['Spielmodus'] . "</td>";
            echo "<td>" . $row_statistics['GesamtFragen'] . "</td>";
            echo "<td>" . $row_statistics['GesamtPunkte'] . "</td>";
            $progress = ($row_statistics['GesamtPunkte'] / $row_statistics['GesamtFragen']) * 100;
            echo "<td><div class='progress' style='height: 20px;'><div class='progress-bar' role='progressbar' style='width: " . $progress . "%;' aria-valuenow='" . $progress . "' aria-valuemin='0' aria-valuemax='100'>" . round($progress, 2) . "%</div></div></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else { // Anzeige falls keine Einträge vorhanden sind
        echo "<div class='container text-center'>";
        echo "<h1>Noch keine Spiele gespielt</h1>";
        echo "</div>";
    }
} else {
    // Benutzer ist nicht eingeloggt
    echo "<div class='container text-center'>";
    echo "<h1>Du bist nicht eingeloggt</h1>";
    echo "</div>";
}

$con->close();
?>

</body>
</html>
