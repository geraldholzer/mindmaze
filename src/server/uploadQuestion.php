<?php
session_start();

// // Datenbankverbindung herstellen
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "mindmaze";
include "../html-php-view/dbconnect.php";
$con = new mysqli($servername, $username, $password, $dbname);

// Überprüfen, ob eine Verbindung hergestellt werden konnte
if ($con->connect_error) {
    die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
}

// Daten aus dem POST-Array abrufen
$modul = $_POST['modul'];
$frage = $_POST['frage'];
$status = 0;
$infotext = $_POST['infotext'];

// SQL-Statement vorbereiten und ausführen
$sql = "INSERT INTO fragen (KursID, FrageText, Status, InfoText) VALUES ('$modul', '$frage', '$status', '$infotext')";

if ($con->query($sql) === TRUE) {
    echo "Die Frage wurde erfolgreich eingereicht.";
} else {
    echo "Fehler beim Einfügen der Frage in die Datenbank: " . $con->error;
}

// Verbindung schließen
$con->close();