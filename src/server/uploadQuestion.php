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
$fragentyp = $_POST['fragentyp'];

//AG: Transaktion starten, da mehrere DB-Änderungen gemacht werden
$con->begin_transaction();

// SQL-Statement vorbereiten und ausführen
$sql = "INSERT INTO fragen (KursID, FrageText, Status, InfoText, FragentypID) VALUES ('$modul', '$frage', '$status', '$infotext', '$fragentyp')";

if ($con->query($sql) === TRUE) {
    $fragenID = $con->insert_id;
    if($fragentyp === '1'){
        $A = $_POST['A'];
        $B = $_POST['B'];
        $C = $_POST['C'];
        $D = $_POST['D'];
        $sql = "INSERT INTO antworten (FragenID, Text) VALUES ('$fragenID', '$A')";
        $con->query($sql);
        $sql = "INSERT INTO antworten (FragenID, Text) VALUES ('$fragenID', '$B')";
        $con->query($sql);
        $sql = "INSERT INTO antworten (FragenID, Text) VALUES ('$fragenID', '$C')";
        $con->query($sql);
        $sql = "INSERT INTO antworten (FragenID, Text) VALUES ('$fragenID', '$D')";
        $con->query($sql);
    }else{
        $antwort = $_POST['antwort'];
        $sql = "INSERT INTO antworten (FragenID, Text) VALUES ('$fragenID', '$antwort')";
        $con->query($sql);
    }
} else {
    echo "Fehler beim Einfügen der Frage in die Datenbank: " . $con->error;
}

// Commit Transaktion und darauf reagieren
if ($con->commit()) {
    //Abhängig vom Ergebnis wird Meldung angezeigt, dass erfolgreich gespeichert wurde
    echo "true";
} else {
    echo "Fehler beim Einfügen der Frage in die Datenbank: " . $con->error;
}

// Verbindung schließen
$con->close();