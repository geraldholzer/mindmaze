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
//Abfrage ob parameter gesetzt sind und Variablen zuweisen
if(isset($_POST['fragenzahl'])){
    $fragenzahl=$_POST['fragenzahl'];
}
if(isset($_POST['meldetext'])){
    $meldetext=$_POST['meldetext'];
}

if(isset($_POST['action'])){
    $action=$_POST['action'];
}
if(isset($_POST['answerid'])){
    $answerid=$_POST['answerid'];
}
if(isset($_POST['questionid'])){
    $questionid=$_POST['questionid'];
} 

$kursID=0;


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
// Auswertung der "action" Variable und ausführen der gewünschten Funktion
if ($action==="fragenladen"){
fragenAusgeben($conn,$fragenzahl,$kursID);
}elseif($action==="answercheck"){
    answercheck($answerid,$questionid,$conn);
}elseif($action==="fragemelden"){
    fragemelden($conn,$questionid,$meldetext);
}elseif($action==="statuscheck"){
        statuscheck($conn,$questionid);}


//Funktion zum Abfragen ob die multiplechoice Antwort stimmt
function answercheck($answerid,$questionid,$conn){
    $stmt = $conn->prepare("Select Korrekt From antworten WHERE AntwortID =? AND FragenID=? ");
    $stmt->bind_param("ss",$answerid,$questionid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['Korrekt'];
    $stmt->close();
}
//Funktion zum Abfragen des Status einer Frage 
function statuscheck($conn,$questionid){
$stmt = $conn->prepare("SELECT fragen.Status From fragen WHERE fragen.FragenID =?");
$stmt->bind_param("i",$questionid);
$stmt->execute();
$result=$stmt->get_result();
$status=$result->fetch_assoc();
echo $status["Status"];
}
//Funktion zum ausgeben der Fragen am Beginn des Spiels
function fragenAusgeben($conn,$fragenzahl,$kursID) {

// Zufällig 5 Fragen aus der Datenbank laden und in eine temporäre Tabelle einfügen

$stmt1 =$conn->prepare ("CREATE TEMPORARY TABLE temp_fragen AS SELECT * FROM fragen WHERE fragen.KursID=? ORDER BY RAND() LIMIT ?");
$stmt1->bind_param("is",$kursID,$fragenzahl);
$stmt1->execute();


    $stmt=$conn->prepare("SELECT * FROM temp_fragen JOIN antworten ON temp_fragen.FragenID = antworten.FragenID"
 );

    // $stmt->bind_param("s",$fragenzahl);
    $stmt->execute();
    $result = $stmt->get_result();

    $questions = array();

    while ($row = $result->fetch_assoc()) {
        // FragenID als Schlüssel verwenden
        $fragenID = $row['FragenID'];
    
        // Wenn die FragenID noch nicht im Array existiert, ein leeres Array für Antworten erstellen
        if (!isset($questions[$fragenID])) {
            $questions[$fragenID] = array(
                'questiontext' => $row['FrageText'],
                "explanation"=>$row["InfoText"],
                "questionid"=> $row["FragenID"],
                'answers' => array()
            );
        }
    
        // Antwort zur entsprechenden Frage hinzufügen
        $questions[$fragenID]['answers'][] = array(
            "answer"=>$row['Text'],
            "answerid"=>$row['AntwortID'],

        );
    }
    
    $stmt->close();
    //Temporäre Tabelle löschen
    $conn->query("DROP TEMPORARY TABLE IF EXISTS temp_fragen");
    $questionsJSON = json_encode($questions);
    
    echo $questionsJSON;
}
// Fragemeldung in die Spalte MeldeGrund der fragen Tabelle einfügen
function fragemelden($conn,$fragenID,$meldetext){
$stmt=$conn->prepare("UPDATE fragen SET MeldeGrund = ?, Status = 0 WHERE FragenID = ?" );
$stmt->bind_param("si",$meldetext,$fragenID);
$stmt->execute();
}





//ALT/////////////////////////////////ALT//////////////////////ALT////////////////////////////////ALT/////////////

// class answer{
//     public $answer;
//     public $correct;

//     function __construct($answer,$correct) {
//         $this->answer = $answer;
//         $this->correct = $correct;
//       }
// }

// class question{
//     public $questionid;
//     public $questiontext;
//     public $explanation;
//     public $answers;
    
//     function __construct($questiontext,$explanation,$answerarray){
//         $this->questiontext=$questiontext;
//         $this->explanation=$explanation;
//         $this->answers= $answerarray;
        
//     }
    
// }

?>