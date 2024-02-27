<?php

// Funktion zum Speichern des Passworts in die Datenbank
function savePasswordToDatabase($password) {
     // Verbindung zur Datenbank herstellen und Abfrage ausführen
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "mindmaze";
 
     try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
        // Passwort hashen (z. B. mit bcrypt)
        $hashedPassword = password_hash(htmlspecialchars($password), PASSWORD_DEFAULT);
        $UserID = 1; //noch ändern auf Session-Variable

        // Passwort in die Datenbank speichern
        $sql = "UPDATE benutzer SET Passwort=:passwort WHERE BenutzerID=:benID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':passwort', $hashedPassword);
        $stmt->bindParam(':benID', $UserID);
        $result = $stmt->execute();

        if ($result) {
            $meldung = "Passwort erfolgreich gespeichert!";
        }
        else {
            $meldung = "Achtung: Passwort konnte nicht gespeichert werden!";
        }

        //Meldung ausgeben und zurück zur letzten Seite gehen
        echo "<script>alert('$meldung'); window.history.back();</script>";
    } catch(PDOException $e) {
        echo "Fehler: " . $e->getMessage();
    }        
}

function getUserDetails() {
    // Verbindung zur Datenbank herstellen und Abfrage ausführen
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mindmaze";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $userID = 1; //noch ändern auf Session-Variable

        // Abfrage vorbereiten
        $stmt = $conn->prepare("SELECT benutzer.*, zugriffsrechte.Beschreibung AS ZugriffsrechteBeschreibung, studiengang.Beschreibung AS StudiengangBeschreibung FROM benutzer 
                                              INNER JOIN zugriffsrechte ON benutzer.ZugriffsrechteID = zugriffsrechte.ZugriffsrechteID
                                              INNER JOIN studiengang    ON benutzer.StudiengangID = studiengang.StudiengangID 
                                              WHERE Benutzerid = :id");
        $stmt->bindParam(':id', $userID);
        $stmt->execute();

        // Datensatz abrufen
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verbindung schließen
        $conn = null;

        return $row; // Rückgabe des Datensatzes
    } catch(PDOException $e) {
        echo "Fehler: " . $e->getMessage();
    }
}

// Überprüfen, ob das Formular gesendet wurde
if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
    //Prüfen ob die eingegebenen Passwörter gleich sind
    if ($_POST['txtNewPassword'] == $_POST['txtNewPasswordMatch']) {
        // Passwort vom Formular empfangen
        $passwordFromForm = $_POST['txtNewPasswordMatch'];
        // Passwort in die Datenbank speichern, indem die Funktion aufgerufen wird
        savePasswordToDatabase($passwordFromForm);
    }
    else{
        //Meldung ausgeben und zurück zur letzten Seite gehen
        $meldung = "Die Passwörter stimmen nicht überein!";
        echo "<script>alert('$meldung'); window.history.back();</script>";
    }
}
else if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER["REQUEST_METHOD"] == "GET")) {
    $benutzerDetails = getUserDetails();

    // Sende das Ergebnis zurück als JSON
    echo json_encode($benutzerDetails);  
}
?>