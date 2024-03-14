<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="../css/style.css" rel="stylesheet">
  <link href="../css/main.css" rel="stylesheet">
  <!-- Bootstrap CSS etc.-->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</head>

<style>
  .form-group {
    display: flex;
    flex-direction: row;
    align-items: center;
    margin-bottom: 10px;
  }

  .form-group label {
    flex: 0 0 160px;
  }

  .form-group input {
    flex: 0 0 250px;
  }

  .center-vertically {
    display: flex;
    align-items: center;
  }
</style>

<body>
  <!-- Einbinden der PHP-Seite für die Navbar -->
  <?php session_start();?>
  <?php include ("navbar.php");?>

  <?php
    //lädt die vorhandenen Studiengänge aus der DB und erstelle Comboboxeinträge-->
    function getStudiengang(){
      // Verbindung zur Datenbank herstellen und Abfrage ausführen
      $servername = "localhost";
      $username = "root";
      $dbpassword = "";
      $dbname = "mindmaze";

      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

        // Durchführen der SQL Abfrage
        $stmt = $conn->query("SELECT * FROM studiengang");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          echo "<option value='" . $row["StudiengangID"] . "'>" . $row["Beschreibung"] . "</option>";
        }
      } catch(PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }    
    }

    //lädt die vorhandenen Studiengänge aus der DB und erstelle Comboboxeinträge-->
    function getKurse(){
      // Verbindung zur Datenbank herstellen und Abfrage ausführen
      $servername = "localhost";
      $username = "root";
      $dbpassword = "";
      $dbname = "mindmaze";

      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

        $UserID = $_SESSION['BenutzerID']; 
        // Durchführen der SQL Abfrage
        $stmt = $conn->prepare("SELECT * FROM kurse WHERE BenutzerID = :benID");
        $stmt->bindParam(':benID', $UserID);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
        }
      } catch(PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }    
    }

    //lädt die Fragen + Antworten eines Studiengangs aus der DB und erzeugt eine Tabelle
    function getQuestionTable(){
      // Verbindung zur Datenbank herstellen und Abfrage ausführen
      $servername = "localhost";
      $username = "root";
      $dbpassword = "";
      $dbname = "mindmaze";

      try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);              
        
        $kursID = 1;
        // Durchführen der SQL Abfrage
        $stmt = $conn->prepare("SELECT fragen.* FROM fragen LEFT JOIN antworten ON fragen.FragenID = antworten.FragenID
                                                            WHERE fragen.KursID = :kurs");
        $stmt->bindParam(':kurs', $kursID);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        }
      } catch(PDOException $e) {
        echo "Fehler: " . $e->getMessage();
      }              
    }
  ?>

  <!--Einklappbarer Bereich-->
  <div class="accordion" id="Questions">
    <!--"Frage einreichen"-->
    <div class="card">
      <div class="card-header" id="Question_Submit">
        <h5 class="mb-0">
          <button class="button-short" type="button" data-toggle="collapse"
            data-target="#collapseQuestionSubmit" aria-expanded="true" aria-controls="collapseQuestionSubmit">
            Frage einreichen
          </button>
        </h5>
      </div>

      <div id="collapseQuestionSubmit" class="collapse show" aria-labelledby="Frage einreichen" data-parent="#Questions">
        <div class="card-body">
          <div class="row">
            <form id=submitQuestion method="post">
              <div class="form-group">
                <label for="selectStudiengang">Studiengang</label>
                <select id="selectStudiengang" name="selectStudiengang">
                  <?php
                    getStudiengang();
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="question">Frage:</label>
                <textarea id="question" name="question" rows="4" cols="80"></textarea>
              </div>  
              <div class="form-group">
                <label for="answertype">Antwortart:</label>   
                <select id="answertype" name="answertype">
                  <option value="freetext">Freitext</option>
                  <option value="multiplechoice">Multiple Choice</option>
                </select>
              </div>
              <div class="form-group">
                <div id="answerfields">
                  <div class="form-group">
                    <label for="answer">Antwort:</label>
                    <textarea id="answer" name="answer" rows="4" cols="80"></textarea>
                  </div>                
                </div>
              </div>  
              <div class="form-group">
                <label for="hint">Hinweis:</label>
                <textarea id="hint" name="hint" rows="4" cols="80"></textarea>               
              </div>              
              <div class="form-group">
                <input class="mt-3 button-short" type="submit" value="Absenden">
              </div>  
            </form>  
          </div>
        </div>
      </div>
    </div>

    <!--"Frage freigeben"-->
    <?php if ($_SESSION['ZugriffsrechteID'] == 3) {
      echo '<div class="card">
              <div class="card-header" id="Question_Accept">
                <h5 class="mb-0">
                  <button class="button-short" type="button" data-toggle="collapse" data-target="#collapseQuestionAccept"
                    aria-expanded="true" aria-controls="collapseQuestionAccept">
                    Frage freigeben
                  </button>
                </h5>
              </div>

              <div id="collapseQuestionAccept" class="collapse" aria-labelledby="Frage freigeben" data-parent="#Questions">
                <div class="card-body">
                  <form id=QuestionAcceptForm>
                    <div class="form-group">
                      <label for="selectKurs">Kurs</label>
                      <select id="selectKurs" name="selectKurs">';                       
                        echo getKurse();                      
      echo           '</select>
                    </div>
                    <div class="form-group">';
                      echo getQuestionTable(); 
      echo         '</div>
                  </form>
                </div>
              </div>
            </div>';
    }
    ?>
  </div>

  <script>
    document.getElementById('answertype').addEventListener('change', function() {
      var auswahl = this.value;
      var answerfieldsDiv = document.getElementById('answerfields');
      
      // Leere das Div, um vorherige Felder zu entfernen
      answerfieldsDiv.innerHTML = '';
      
      if (auswahl === 'freetext') {
        answerfieldsDiv.innerHTML = `<div class="form-group">
                                      <label for="answer">Antwort:</label>
                                      <textarea id="answer" name="answer" rows="4" cols="80"></textarea>
                                    </div>`;
      } else if (auswahl === 'multiplechoice') {
        answerfieldsDiv.innerHTML = ` <div class="form-group">
                                        <label for="A">A:</label>
                                        <textarea id="A" name="A" rows="1" cols="60"></textarea>
                                      </div>
                                      <div class="form-group">
                                        <label for="B">B:</label>
                                        <textarea id="B" name="B" rows="1" cols="60"></textarea>
                                      </div>
                                      <div class="form-group">
                                        <label for="C">C:</label>
                                        <textarea id="C" name="C" rows="1" cols="60"></textarea>
                                      </div>
                                      <div class="form-group">
                                        <label for="D">D:</label>
                                        <textarea id="D" name="D" rows="1" cols="60"></textarea>
                                      </div>`;
      }
    });

    function getStudiengangfromField(){

    }
  </script>
</body>
</html> 