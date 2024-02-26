<?php

// Verbindung zur MySQL-Datenbank herstellen

//$servername="13.53.246.106";
$servername="localhost";
$username="root";
$pw="";
$db="mindmaze";
$conn= new mysqli($servername,$username,$pw,$db);

// Überprüfen, ob die Verbindung erfolgreich war
if ($conn->connect_error) {
   echo("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
//Abfrage des action Parameters über diesen wird vom Client die gewünschte Funktion aufgerufen
if(isset ($_POST["action"])){
    $action = $_POST['action'];
}

if(isset ($_POST["fragenzahl"])){
    $fragenzahl = $_POST['fragenzahl'];
}

if(isset($_POST['gamename'])){
    $gamename=$_POST['gamename'];
}
// Hier wird die passende spielmodi ID aus der Datenbank geholt da in der Auswahl im Dropdown Feld nur die Beschreibung steht
//Gespeichert wird das Ergebnis in der variable $modusID diese wird zum erstellen eines neuen Spiels benötigt 
if(isset($_POST['modus'])){
    $modus=$_POST['modus'];
    $stmt = $conn->prepare('SELECT SpielmodiID FROM spielmodi WHERE Beschreibung = ?');
    $stmt->bind_param('s', $modus); // 's' steht für einen String-Parameter
    $stmt->execute();
    $stmt->bind_result($modusID);
    $stmt->fetch();
    $stmt->close();
}
// Hier wird die passende  kursID aus der Datenbank geholt da in der Auswahl im Dropdown Feld nur die Beschreibung steht
//Gespeichert wird das Ergebnis in der variable $kursID diese wird zum erstellen eines neuen Spiels benötigt 
if(isset($_POST['kurs'])){
    $kurs=$_POST['kurs'];
    $stmt = $conn->prepare('SELECT KursID FROM kurse WHERE Beschreibung = ?');
    $stmt->bind_param('s', $kurs); // 's' steht für einen String-Parameter
    $stmt->execute();
    $stmt->bind_result($kursID);
    $stmt->fetch();
    $stmt->close();
}

// Abfrage welche Aktion vom Client gefordert wird
if ($action === 'deleteGame') {
    $gameName = $_POST['gamename'];
    deleteGame($gamename, $conn);
} else if ($action === 'addGame') {
    addGame($gamename,$kursID,$modusID,$fragenzahl, $conn);
} else if ($action === 'getGameList') {
    sendGamelist($conn);
} else if ($action === 'getKursDropdown') {
    sendKursDropdown($conn);
}
else if ($action === 'getModusDropdown') {
    sendModusDropdown($conn);
}
//Hier wird ein neues Spiel erstellt und in die Tabelle spiele geschrieben
function addGame($name,$kursID,$modusID,$fragenzahl, $conn) {
    $started = time();
    $stmt = $conn->prepare('INSERT INTO spiele (Spielname,KursID,SpielmodiID,BenutzerFragen) VALUES (?,?,?,?)');
    $stmt->bind_param('siii', $name,$kursID,$modusID,$fragenzahl);
    $stmt->execute();
    $stmt->close();
}
//Hier wird ein Spiel aus der Tabelle spiele gelöscht
function deleteGame($name, $conn) {
    $stmt = $conn->prepare('DELETE FROM spiele WHERE Spielname = ?');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $stmt->close();
}

//Hier wird ein assoziatives Array mit allen verfügbaren spielen an den client gesendet
function sendGamelist($conn) {
    //Die spiele Tabelle wird mit der kurse und spielmodi Tabelle gejoint
    // um die Beschreibungen für spielmodi und kurs auch weitergeben zu können
    $result = $conn->query("SELECT spiele.*, kurse.Beschreibung AS kurs, spielmodi.Beschreibung AS modus
     FROM spiele JOIN kurse ON (spiele.KursID = kurse.KursID) JOIN spielmodi ON (spiele.SpielmodiID = spielmodi.SpielmodiID);");
    $gamesArray = array();

    while ($row = $result->fetch_assoc()) {
        $gamesArray[] = array(
            'name' => $row['Spielname'],
            'modus' => $row['modus'],
            'kurs' => $row['kurs'],
            "fragenzahl" =>$row['BenutzerFragen'],
        );
    }

    // Konvertiere das Array in JSON
    $gamesJson = json_encode($gamesArray);

    // Ausgabe des JSON an den Client
    echo $gamesJson;
}

// Hier werden aus der Tabelle kurse die Texte aus der Beschreibung Spalte aller Kurse geladen und an den Client gesendet
// Dies dient zum Füllen der Dropdownfelder
function sendKursDropdown($conn){
$result=$conn->query("SELECT Beschreibung  From kurse;");
   $kursArray= array();
    while($row = $result->fetch_assoc()){
        $kursArray[] = $row["Beschreibung"];
    }
     // Konvertiere das Array in JSON
     $kursJson = json_encode($kursArray);

     // Ausgabe des JSON an den Client
     echo $kursJson;
}

// Hier werden aus der Tabelle spielmod  die Beschreibungen aller Kurse geladen und an den CLient gesendet
// Dies dient zum Füllen der Dropdownfelder
function sendModusDropdown($conn){
    $result=$conn->query("SELECT Beschreibung  From spielmodi;");
       $modusArray= array();
        while($row = $result->fetch_assoc()){
            $modusArray[] = $row["Beschreibung"];
        }
         // Konvertiere das Array in JSON
         $kursJson = json_encode($modusArray);
    
         // Ausgabe des JSON an den Client
         echo $kursJson;
    }

// Verbindung schließen
$conn->close();
?>
