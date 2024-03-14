<?php
require 'vendor/autoload.php';
//Ratchet wird für die Websockets benötigt
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServerInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

        
//Websocket server Klasse 
class MyWebSocketServer implements MessageComponentInterface
{
    protected $clients;
    protected $rooms;
 //Konstruktor
    public function __construct()
    {
        // Hier werden alle Verbindungen der Spieler gespeichert
        $this->clients = new \SplObjectStorage();
        // Hier werden die Spielsitzungen gespeichert in die dann die clients hinzugefügt werden
        $this->rooms = [];

   

    }

//Funktion zum holen und ausgeben der Fragen für den Multiplayer 
    private function fragenAusgebenAsync($fragenzahl,$kurs,$modus)
    {
//Dient zur Auswahl ob multiplechoice oder Freitext fragen gestellt werden
       if($modus =='Kooperativ'||$modus=="Versus" ){
        $FragentypID=1;//multiplechoice
       }else{
        $FragentypID=2;//Freitext
       }
       //Aufruf der connectToDatabase die dann die fetchquestions funktion aufruft  
       //die Asynchronität dient dazu keine Verbindungsbedingten fehler im Ablauf zu haben
        return $this->connectToDatabase($kurs,$FragentypID)
            ->then(function ($kursID) use ($fragenzahl,$FragentypID) {
                return $this->fetchQuestions($kursID,$fragenzahl,$FragentypID);
            })
            //Nach dem Holen der fragen wird die entsprechende umwandlungsfunktion aufgerufen 
            //die Daten werden in ein assoziatives array gespeichert und in JSON formatiert
            //Zwei Funktionen weil es bei den Freitextfragen anders zu handeln ist
            ->then(function ($questions) use ($fragenzahl, $kurs,$modus,$FragentypID) {
                if (!empty($questions)) {
                    if($FragentypID==1){ 
                        return $this->processQuestions($questions);
                    }else{
                        return $this->processQuestionssupportive($questions);
                    }
              //Wiederholter aufruf der Funktion falls die verbindung langsam ist      
                } else {
                    // Sleep for a short delay and then retry
                    sleep(2); // Sleep for 1 second
                    return $this->fragenAusgebenAsync($fragenzahl, $kurs,$modus);
                }
            });
    }
//Hier wird die Verbindung zur Datenbank hergestellt und die KursID für den übergebenen Kurs herausgesucht
    private function connectToDatabase($kurs)
    {
        return new \React\Promise\Promise(function ($resolve, $reject) use ($kurs) {
        //    //$servername="13.53.246.106";
            // $servername = "localhost";
            // $username = "root";
            // $password = "";
            // $dbname = "mindmaze";
            include __DIR__ . "/../html-php-view/dbconnect.php";
            // include "../html-php-view/dbconnect.php";

            $conn = new mysqli($servername,$username,$password,$dbname);
            if ($conn->connect_error){
                print_r("fehler verbindung".$conn->connect_error);}

            $stmt = $conn->prepare('SELECT KursID FROM kurse WHERE Beschreibung = ?');
            $stmt->bind_param('s', $kurs);
            $stmt->execute();
            $stmt->bind_result($kursID);
            $stmt->fetch();
            $stmt->close();
    
            $conn->close();
            
            $resolve($kursID);
        });
    }
    // Hier werden die Fragen aus der Datenbank geholt
    private function fetchQuestions($kursID,$fragenzahl,$FragentypID)
    {
        return new \React\Promise\Promise(function ($resolve, $reject) use ($kursID,$fragenzahl,$FragentypID) {
            //$Datenbankverbindung
            include __DIR__ . "/../html-php-view/dbconnect.php";
            $conn = new mysqli($servername,$username,$password,$dbname);
    
            $stmt1 = $conn->prepare("CREATE TEMPORARY TABLE temp_fragen AS SELECT * FROM fragen WHERE fragen.KursID=? AND FragentypID=? ORDER BY RAND() LIMIT ?");
            $stmt1->bind_param("sii", $kursID,$FragentypID,$fragenzahl);
            $stmt1->execute();
            //Nur wenn es sich um multiplechoice handelt wird mit antworten gejoint
            if($FragentypID==1){ 
            $stmt = $conn->prepare("SELECT * FROM temp_fragen JOIN antworten ON temp_fragen.FragenID = antworten.FragenID");
            $stmt->execute();
            $result = $stmt->get_result();
                                  }
            else{
            $stmt = $conn->prepare("SELECT * FROM temp_fragen");
            $stmt->execute();
            $result = $stmt->get_result();
                    }
                    
            $questions = [];
            while ($row = $result->fetch_assoc()) {
                $questions[] = $row;
            }
    
            $stmt->close();
            $conn->query("DROP TEMPORARY TABLE IF EXISTS temp_fragen");
            $conn->close();
    
            $resolve($questions);
        });
    }
    
    // Hier werden die Fragen in ein Assoziatives array gespeichert und in JSON formatiert
    private function processQuestions($questions)
    {  
        $processedQuestions = [];
    
        foreach ($questions as $row) {
            $fragenID = $row['FragenID'];
    
            if (!isset($processedQuestions[$fragenID])) {
                $processedQuestions[$fragenID] = [
                    'questiontext' => $row['FrageText'],
                    "explanation" => $row["InfoText"],
                    "questionid" => $row["FragenID"],
                    'answers' => [],
                ];
            }
    
            $processedQuestions[$fragenID]['answers'][] = [
                "answer" => $row['Text'],
                "answerid" => $row['AntwortID'],
            ];
        }
    
    $processedQuestionsJSON = json_encode($processedQuestions);
    // print_r("Fragenjson\n".$processedQuestionsJSON);
return $processedQuestionsJSON;
    }
  
    private function processQuestionssupportive($questions)
    {
        
        $processedQuestions = [];
    
        foreach ($questions as $row) {
            $fragenID = $row['FragenID'];
    
            if (!isset($processedQuestions[$fragenID])) {
                $processedQuestions[$fragenID] = [
                    'questiontext' => $row['FrageText'],
                    "explanation" => $row["InfoText"],
                    "questionid" => $row["FragenID"],
                ];
            }
    
           
        }
    
    $processedQuestionsJSON = json_encode($processedQuestions);
    // print_r("Fragenjson\n".$processedQuestionsJSON);
return $processedQuestionsJSON;
    }
  

//Bei Anmeldung hinzufügen des clients zur clientliste "clients"
    public function onOpen(ConnectionInterface $conn)
    {
        // Connection opened
        echo "New connection! ({$conn->resourceId})\n";
        $conn->finishflag= false;
        // Client zu clients hinzufügen
        $this->clients->attach($conn);
    }
    // Bei schließen der Verbindung  
    public function onClose(ConnectionInterface $conn)
    {
        // Connection closed
        echo "Connection {$conn->resourceId} has disconnected\n";

        // Remove the connection from all rooms
        foreach ($this->rooms as $room) {
            $room->detach($conn);
        }

        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Error occurred
        echo "An error occurred: {$e->getMessage()}\n";

        // Close the connection
        $conn->close();
    }

   
    //Hier wird eine Nachricht vom Client ausgewertet
    public function onMessage(ConnectionInterface $from, $msg)
    {
        // Übergebene nachricht in Assoziatives array umwandeln
        $data = json_decode($msg, true);

        if (!isset($data['type'])) {
            return; // Invalid message format
        }
        //Abfrage um welche art von Anfrage es sich handelt mit dem type parameter
        switch ($data['type']) {
            case 'subscribe'://Anmelden zu einer Spielsitzung bzw raum
                $this->handleSubscribe($from, $data['room'],$data['fragenzahl'],$data['kurs'],$data['modus'],$data['benutzername']);
                print_r($data['benutzername']);
                break;
            case 'message'://Allgemeine nachricht message
                $this->handleMessage($from, $data['room'], $data['message']);
                break;
            case 'finish'://Nachricht vom CLient spiel beendet
                $this->handleFinish($from, $data['room'], $data['message']);
                break;
            case 'interrupt'://Nachricht vom CLient spiel vorzeitig beendet
                $this->handleInterrupt($from, $data['room']);
                break;
        }
    }
    // Hier wird ein Client zu einem Raum gleichbedeutend mit Spielsitzung hinzugefügt
    private function handleSubscribe(ConnectionInterface $conn, $room,$fragenzahl,$kurs,$modus,$benutzername)
    {
        print_r("Kurs:".$kurs."  \n");
        // Raum erstellen falls nicht vorhanden 
        if (!isset($this->rooms[$room])) {
            $this->rooms[$room] = new \SplObjectStorage();
        }

        // Client zu Raum hinzufügen mit attach Wenn sich 2 Spieler im Raum befinden wird eine ready message gesendet.
        if($this->rooms[$room]->count()<2){
             $this->rooms[$room]->attach($conn);
             //Ready message dient dazu das Spiel nach dem Eintreffen beider Spieler zu löschen und die namen der Spieler zu übertragen
             if($this->rooms[$room]->count()==2){
                $questionsPromise = $this->fragenAusgebenAsync($fragenzahl, $kurs,$modus);
                $questionsPromise->then(function ($questions) use ($room,$benutzername) {

                    $this->broadcastToRoom($room, json_encode(['type' => 'questions', 'questions' => $questions]), null);
                    $message = "ready";
                    $this->broadcastToRoom($room, json_encode(['type' => 'message', 'message' => $message,"opponent"=>$benutzername]), null);
                 
                   
                  
                });
            }
             }
        echo "Client {$conn->resourceId} subscribed to room $room\n";   
    }
    
    //Normale Nachricht an alle Teilnehmer ausgeben
    private function handleMessage(ConnectionInterface $from, $room, $message)
    {
        
        $this->broadcastToRoom($room, json_encode(['type' => 'message', 'message' => $message]), $from);
    }
    // Wird bei einer Interrupt message aufgerufen
    private function handleInterrupt(ConnectionInterface $from, $room)
    {
       //Senden einer interuppt message an den gegner 
        $this->broadcastToRoom($room, json_encode(['type' => 'interrupt', 'message' => "interrupt"]), $from);
    }
    // Wird aufgerufen wen ein finishflag eingeht sobald ein Spieler das Spiel normal beendet hat 
    private function handleFinish(ConnectionInterface $from, $room, $message)
    {
        //Ausgeben der finish nachricht mit den Punkten an den Gegner
        $this->broadcastToRoom($room,  json_encode(['type' => 'finish', 'points' => $message]), $from);
        //Setzen des finishflag für den User der die Nachricht gesendet hat
        $from->finishflag= true;
        //Abfrage ob finishflag bei allen Spielern gesetzt ist
        $allClientsFinished = true;
        foreach ($this->rooms[$room] as $client) {
            if (!isset($client->finishflag) || !$client->finishflag) {
                $allClientsFinished = false;
                break;
            }
        }

        // Wenn das Flag bei allen Clients gesetzt ist
        if ($allClientsFinished) {
           echo "game over";
            // Sende die 'gameover' Nachricht an alle Clients im Raum
            $this->broadcastToRoom($room, json_encode(['type' => 'gameover', 'message' =>"gameover"]), null);
        }
      
    }
    
    private function broadcastToRoom($room, $message, $exclude)
    {   
        if (!isset($this->rooms[$room])) {
            return; // Spiel existiert nicht
        }

        foreach ($this->rooms[$room] as $client) {
            // Nachricht nicht an den absender schicken außer ready message
           if($message!=="ready"){
             if ($exclude !== $client) {
                $client->send($message);
            }
           }else{$client->send($message); }
                    
        }
    }

}

//  WebSocket Server erstellen
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new MyWebSocketServer()
        )
    ),
    8081  // Port wählen
);
//Port8081 weil sonst konflikt mit XAMPP
echo "WebSocket server started at 127.0.0.1:8081\n";

// Starten des WebSocket Server
$server->run();
?>

