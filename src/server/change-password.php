<?php
session_start();
// Datenbankverbindung herstellen
include "../html-php-view/dbconnect.php";
$con = new mysqli($servername, $username, $password, $dbname);

// Überprüfen, ob eine Verbindung hergestellt werden konnte
if ($con->connect_error) {
    die ("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
}

// Daten aus dem POST-Array abrufen
$Benutzer = $_POST['BenutzerID'];
$Zugriffsrechte = $_POST['Zugriffsrechte'];

//AG: Transaktion starten, da mehrere DB-Änderungen gemacht werden
$con->begin_transaction();

if (!empty($_POST['newPassword'])) {

    $Passwort = password_hash(htmlspecialchars($_POST["newPassword"]), PASSWORD_DEFAULT);

    // SQL-Statement vorbereiten
    $sql = "UPDATE benutzer SET Passwort = ? WHERE BenutzerID = ?";
    $stmt = $con->prepare($sql);


    // Parameter binden und Statement ausführen
    $stmt->bind_param("si", $Passwort, $Benutzer);
    if ($stmt->execute()) {
        // Commit Transaktion und darauf reagieren
        if ($con->commit()) {
            //Abhängig vom Ergebnis wird Meldung angezeigt, dass erfolgreich gespeichert wurde
            echo "true";
        } else {
            echo "Fehler beim Commit der Transaktion";
        }
    } else {
        echo "Fehler beim Ausführen des SQL-Statements: " . $stmt->error;
    }
}

$sql = "UPDATE benutzer SET ZugriffsrechteID = ? WHERE BenutzerID = ?";
$stmt = $con->prepare($sql);

$stmt->bind_param("si", $Zugriffsrechte, $Benutzer);

if ($stmt->execute()) {
    // Commit Transaktion und darauf reagieren
    if ($con->commit()) {
        //Abhängig vom Ergebnis wird Meldung angezeigt, dass erfolgreich gespeichert wurde
        echo "true";
    } else {
        echo "Fehler beim Commit der Transaktion";
    }
} else {
    echo "Fehler beim Ausführen des SQL-Statements: " . $stmt->error;
}

// Statement schließen
$stmt->close();

// Verbindung schließen
$con->close();
?>