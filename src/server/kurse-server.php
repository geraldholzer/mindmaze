<?php
  // Überprüfen, ob das Formular gesendet wurde
  if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
    if (isset($_POST['action']) && ($_POST['action']) == 'getKurseByStudiengang') {
      include "../html-php-view/dbconnect.php";
      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
        session_start();
        //Normaler User soll alle Kurse des Studiengangs sehen
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
        //Tutor soll alle Kurse sehen vom Studiengang bei dem er als Benutzer eingetragen ist
        }else if ($_SESSION['ZugriffsrechteID'] == 2){ 
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
        }
      } catch (PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }
    }
  }
?>