<?php
session_start();

// Datenbankverbindung herstellen
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mindmaze";
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
$fragentyp = $_POST['fragentyp'];

// SQL-Statement vorbereiten und ausführen
$sql = "INSERT INTO fragen (KursID, FrageText, Status, InfoText, FragentypID) VALUES ('$modul', '$frage', '$status', '$infotext', '$fragentyp')";

if ($con->query($sql) === TRUE) {
    $fragenID = $con->insert_id;
    $sql = "INSERT INTO antworten (FragenID, Text) VALUES ('$fragenID', 'testing')";
    $con->query($sql);


} else {
    echo "Fehler beim Einfügen der Frage in die Datenbank: " . $con->error;
}

// Verbindung schließen
$con->close();