<?php
  // Überprüfen, ob und welche Request-Methode versendet wurde
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
    //Funktion zum ermitteln von Kurse lt. Studiengang und Benutzer und erstellen von Comboboxeinträgen
    if (isset($_POST['action']) && ($_POST['action']) == 'getKurseByStudiengang') {
      include "../html-php-view/dbconnect.php";
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
        session_start();
        //Student: soll alle Kurse des Studiengangs sehen
        if ($_SESSION['ZugriffsrechteID'] == 1){ 
          $studiengang = $_POST['studiengangID'];
          // Durchführen der SQL Abfrage
          $stmt = $conn->prepare("SELECT kurse.KursID AS KursID, kurse.Beschreibung AS Beschreibung FROM kurse 
                                          LEFT JOIN studiengangkurse ON kurse.KursID = studiengangkurse.KursID
                                          WHERE studiengangkurse.StudiengangID = :studiengangID");
          $stmt->bindParam(':studiengangID', $studiengang);
          $stmt->execute();
    
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
          }
        //Tutor: soll alle Kurse sehen vom Studiengang bei dem er als Benutzer eingetragen ist
        }
        else if ($_SESSION['ZugriffsrechteID'] == 2){ 
          $studiengang = $_POST['studiengangID'];
          $userID = $_SESSION['BenutzerID'];
          // Durchführen der SQL Abfrage
          $stmt = $conn->prepare("SELECT kurse.KursID AS KursID, kurse.Beschreibung AS Beschreibung FROM kurse 
                                          LEFT JOIN studiengangkurse ON kurse.KursID = studiengangkurse.KursID
                                          WHERE studiengangkurse.StudiengangID = :studiengangID
                                          AND kurse.BenutzerID = :benID");
          $stmt->bindParam(':studiengangID', $studiengang);
          $stmt->bindParam(':benID', $userID);
          $stmt->execute();
    
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
          }  
        //Admin soll alles sehen       
        }
        else if ($_SESSION['ZugriffsrechteID'] == 3){ 
          $studiengang = $_POST['studiengangID'];
          $userID = $_SESSION['BenutzerID'];
          // Durchführen der SQL Abfrage
          $stmt = $conn->prepare("SELECT kurse.KursID AS KursID, kurse.Beschreibung AS Beschreibung FROM kurse 
                                          LEFT JOIN studiengangkurse ON kurse.KursID = studiengangkurse.KursID
                                          WHERE studiengangkurse.StudiengangID = :studiengangID");
          $stmt->bindParam(':studiengangID', $studiengang);
          $stmt->execute();
    
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
          }         
        }
      } catch (PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }
    }

    //Funktion zum Ermittln der Fragen je Kust und erstellen einer Tabelle
    if (isset($_POST['action']) && ($_POST['action']) == 'getFragenByKurs') {
      include "../html-php-view/dbconnect.php";
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
        $kursID = $_POST['kursID'];
        // Durchführen der SQL Abfrage
        $stmtquestion = $conn->prepare("SELECT fragen.*, fragentyp.Beschreibung AS FragenTypBesch FROM fragen LEFT JOIN fragentyp ON fragen.FragentypID = fragentyp.FragentypID
                                                                                                  WHERE fragen.KursID = :kurs AND fragen.Status = 0"
                                                                                                  );
        $stmtquestion->bindParam(':kurs', $kursID);
        $stmtquestion->execute();
  
        while ($question = $stmtquestion->fetch(PDO::FETCH_ASSOC)) {
          $stmtanswer = $conn->prepare("SELECT antworten.* FROM antworten WHERE antworten.FragenID = :question");
          $stmtanswer->bindParam(':question', $question["FragenID"]);
          $stmtanswer->execute();

          echo "<tr id='tablequestion" . $question["FragenID"] . "'>";
          echo "  <td>" . $question["FragenID"]  . "</td>";
          echo "  <td>" . $question["FragenTypBesch"] . "</td>";
          echo "  <td>" . $question["FrageText"] . "</td>";
          echo "  <td>" . $question["InfoText"]  . "</td>";
          if ($question["FragentypID"] == 1){
            echo "  <td></td>";  
          }
          while ($answer = $stmtanswer->fetch(PDO::FETCH_ASSOC)) {
            if ($answer["Korrekt"] == 1){
              echo '  <td style="color: green; font-weight: bold;">' . $answer["Text"]  . "</td>";
            }
            else{
              echo "  <td>" . $answer["Text"]  . "</td>";
            }
          }
          if ($question["FragentypID"] == 2){
            echo "  <td></td>";  
            echo "  <td></td>";  
            echo "  <td></td>";  
            echo "  <td></td>";  
          } 
          echo "  <td><button onclick='discardAnswer(". $question["FragenID"] . "); event.preventDefault()'>Ablehnen</td>";
          echo "  <td><button onclick='acceptAnswer(" . $question["FragenID"] . "); event.preventDefault()'>Freigeben</td>";
          echo "</tr>";  
        }
      } catch (PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }
    }

    //Funktion zum setzen des Fragen-Status
    if (isset($_POST['action']) && ($_POST['action']) == 'setFragenStatus') {
      include "../html-php-view/dbconnect.php";
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $fragenID = $_POST['fragenID'];
        $fragenStatus = $_POST['fragenStatus'];
        // Durchführen der SQL Abfrage
        $stmt = $conn->prepare("UPDATE fragen SET fragen.Status = :fragenStatus WHERE fragen.FragenID = :fragenID");
        $stmt->bindParam(':fragenID', $fragenID);
        $stmt->bindParam(':fragenStatus', $fragenStatus);
        $result = $stmt->execute();

        if ($result) {
          echo "true";
        }
        else {
          echo "false";
        }  
      } catch (PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }
    }
  }
?>