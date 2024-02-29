<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="../javascript/lobby.js"></script>
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
include 'navbar.php';


// Datenbankverbindung
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mindmaze";
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Es konnte keine Verbindung zur Datenbank hergestellt werden: " . $con->connect_error);
}

// Benutzer-ID abrufen
$benutzerID = $_SESSION['BenutzerID'];



// SQL-Abfrage, um Sieg-Niederlage-Statistik nach Spielmodus abzufragen
$sql_statistics = "SELECT s.SpielmodiID, m.Beschreibung AS SpielmodusBeschreibung, 
                        SUM(CASE WHEN s.BenutzerIDSieger = ? THEN 1 ELSE 0 END) AS Siege,
                        SUM(CASE WHEN s.BenutzerIDVerlierer = ? THEN 1 ELSE 0 END) AS Niederlagen
                    FROM statistik s
                    INNER JOIN spielmodi m ON s.SpielmodiID = m.SpielmodiID
                    WHERE s.SpielmodiID IN (1, 2, 3) AND (s.BenutzerIDSieger = ? OR s.BenutzerIDVerlierer = ?)
                    GROUP BY s.SpielmodiID";
$stmt = $con->prepare($sql_statistics);
$stmt->bind_param("iiii", $benutzerID, $benutzerID, $benutzerID, $benutzerID);
$stmt->execute();
$result_statistics = $stmt->get_result();

if ($result_statistics->num_rows > 0) {  // Ausgabe Statistik, falls Einträge in der DB vorhanden sind
  echo "<div class='heading-statistik'>";
  echo "<h1>Statistik für " . $_SESSION['Vorname'] . " " . $_SESSION['Nachname'] . "</h1>";
  echo "</div>";

  echo "<div class='tableLobby'>";
  echo "<table>";
  echo "<thead><tr><th>Spielmodus</th><th>Siege</th><th>Niederlagen</th><th>Gewinnrate (%)</th></tr></thead>";
  echo "<tbody>";
  while ($row_statistics = $result_statistics->fetch_assoc()) {
      $siege = $row_statistics['Siege'];
      $niederlagen = $row_statistics['Niederlagen'];
      $gesamtspiele = $siege + $niederlagen;
      $gewinnrate = ($gesamtspiele > 0) ? ($siege / $gesamtspiele) * 100 : 0; // Berechnung Gewinnrate
      echo "<tr><td>" . $row_statistics['SpielmodusBeschreibung'] . "</td><td>" . $siege . "</td><td>" . $niederlagen . "</td><td>" . round($gewinnrate, 2) . "%</td></tr>";
  }
  echo "</tbody>";
  echo "</table>";
  echo "</div>";

  // SQL-Abfrage, um das letzte Spiel abzurufen
  $sql_last_game = "SELECT SpielDatum, IF(BenutzerIDSieger = ?, 'Sieg', 'Niederlage') AS Ergebnis, 
                  m.Beschreibung AS Spielmodus
                  FROM statistik s
                  INNER JOIN spielmodi m ON s.SpielmodiID = m.SpielmodiID
                  WHERE (s.BenutzerIDSieger = ? OR s.BenutzerIDVerlierer = ?)
                  ORDER BY SpielDatum DESC
                  LIMIT 1";
  $stmt_last_game = $con->prepare($sql_last_game);
  $stmt_last_game->bind_param("iii", $benutzerID, $benutzerID, $benutzerID);
  $stmt_last_game->execute();
  $result_last_game = $stmt_last_game->get_result();

  if ($result_last_game->num_rows > 0) {
      $row_last_game = $result_last_game->fetch_assoc();
      echo "<div class='last-game'>";
      echo "<p>Zuletzt gespieltes Spiel: " . $row_last_game['Ergebnis'] . " in " . $row_last_game['Spielmodus'] . " am " . $row_last_game['SpielDatum'] . "</p>";
      echo "</div>";
  }
} else { // leere Tabelle erstellen, falls keine Einträge vorhanden sind
  echo "<div class='heading-statistik'>";
  echo "<h1>Statistik für " . $_SESSION['Vorname'] . " " . $_SESSION['Nachname'] . "</h1>";
  echo "</div>";

  echo "<div class='statistikTable'>";
  echo "<table>";
  echo "<thead><tr><th>Spielmodus</th><th>Siege</th><th>Niederlagen</th><th>Gewinnrate (%)</th></tr></thead>";
  echo "<tbody>";
  echo "<tr><td>Kooperativ</td><td>0</td><td>0</td><td>0%</td></tr>";
  echo "<tr><td>Versus</td><td>0</td><td>0</td><td>0%</td></tr>";
  echo "<tr><td>Supportive</td><td>0</td><td>0</td><td>0%</td></tr>";
  echo "</tbody>";
  echo "</table>";
  echo "</div>";
}

$con->close();
?>
</body>
</html>