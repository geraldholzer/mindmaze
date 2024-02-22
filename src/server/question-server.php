<?php

class answer{
    public $answer;
    public $correct;

    function __construct($answer,$correct) {
        $this->answer = $answer;
        $this->correct = $correct;
      }
}

class question{
    public $questionid;
    public $questiontext;
    public $explanation;
    public $answers;
    
    function __construct($questiontext,$explanation,$answerarray){
        $this->questiontext=$questiontext;
        $this->explanation=$explanation;
        $this->answers= $answerarray;
        
    }
    
}
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




if(isset($_POST['action'])){
    $action=$_POST['action'];
}
if(isset($_POST['answerid'])){
    $answerid=$_POST['answerid'];
}
if(isset($_POST['questionid'])){
    $questionid=$_POST['questionid'];
}


if ($action==="fragenladen"){
fragenAusgeben($conn);
}elseif($action==="answercheck"){
    answercheck($answerid,$questionid,$conn);
}

function answercheck($answerid,$questionid,$conn){
    $stmt = $conn->prepare("Select Korrekt From antworten WHERE AntwortID =? AND FragenID=? ");
    $stmt->bind_param("ss",$answerid,$questionid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['Korrekt'];
    $stmt->close();
}

function fragenAusgeben($conn) {

    $stmt=$conn->prepare("SELECT * From fragen join antworten on (fragen.FragenID = antworten.FragenID);");
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
    
    $questionsJSON = json_encode($questions);
    
    echo $questionsJSON;
}


?>

