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

<?php
session_start();
$_SESSION['inGame']=false;//Wird benötigt um navbar zu aktivieren 
if (!(isset($_SESSION["Email"]))) {
  die("<H1>Hoppla! Da scheint etwas schiefgelaufen zu sein!</H1>"); //Ausgeben einer Fehlermeldung
}
?>

<body>
  <!-- Einbinden der PHP-Seite für die Navbar -->

  <?php include("navbar.php"); ?>

  <?php
  //lädt die vorhandenen Studiengänge aus der DB und erstelle Comboboxeinträge-->
  function getStudiengangByUser()
  {
    // Verbindung zur Datenbank herstellen und Abfrage ausführen
    // $servername = "localhost";
    // $username = "root";
    // $dbpassword = "";
    // $dbname = "mindmaze";
    include "../html-php-view/dbconnect.php";
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      

      if ($_SESSION['ZugriffsrechteID'] == 3){
        $stmt = $conn->prepare("SELECT * FROM studiengang");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<option value='" . $row["StudiengangID"] . "'>" . $row["Beschreibung"] . "</option>";
        }
      }
      //Normaler User darf nur seinen eigenen Studiengang sehen
      if ($_SESSION['ZugriffsrechteID'] == 1) {
        $studiengangIDUser = $_SESSION['StudiengangID'];
        // Durchführen der SQL Abfrage
        $stmt = $conn->prepare("SELECT * FROM studiengang WHERE StudiengangID = :studiengangID");
        $stmt->bindParam(':studiengangID', $studiengangIDUser);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<option value='" . $row["StudiengangID"] . "'>" . $row["Beschreibung"] . "</option>";
        }
        // Tutor darf alle Studiengänge sehen, bei denen er in mindestens einem Kurs als Zuständiger Benutzer eingetragen ist
      } else if ($_SESSION['ZugriffsrechteID'] == 2) {

        $UserID = $_SESSION['BenutzerID'];
        // Durchführen der SQL Abfrage
        $stmt = $conn->prepare("SELECT DISTINCT studiengang.StudiengangID AS Studiengang, studiengang.Beschreibung AS Beschreibung 
                                FROM kurse
                                INNER JOIN studiengangKurse ON kurse.KursID = studiengangKurse.KursID
                                INNER JOIN studiengang      ON studiengangKurse.StudiengangID = studiengang.StudiengangID
                                WHERE kurse.BenutzerID = :benID");
        $stmt->bindParam(':benID', $UserID);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          echo "<option value='" . $row["Studiengang"] . "'>" . $row["Beschreibung"] . "</option>";
        }
      }
    } catch (PDOException $e) {
      echo "Fehler: " . $e->getMessage();
    }
  }

  function getFragentyp()
  {
    // Verbindung zur Datenbank herstellen und Abfrage ausführen
    // $servername = "localhost";
    // $username = "root";
    // $dbpassword = "";
    // $dbname = "mindmaze";
    include "../html-php-view/dbconnect.php";

    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // Durchführen der SQL Abfrage
      $stmt = $conn->query("SELECT * FROM fragentyp");

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row["FragentypID"] . "'>" . $row["Beschreibung"] . "</option>";
      }
    } catch (PDOException $e) {
      echo "Fehler: " . $e->getMessage();
    }
  }

  //lädt die vorhandenen Studiengänge aus der DB und erstelle Comboboxeinträge-->
  function getKurse()
  {
    // Verbindung zur Datenbank herstellen und Abfrage ausführen
    // $servername = "localhost";
    // $username = "root";
    // $dbpassword = "";
    // $dbname = "mindmaze";
    include "../html-php-view/dbconnect.php";
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $UserID = $_SESSION['BenutzerID'];
      // Durchführen der SQL Abfrage
      $stmt = $conn->prepare("SELECT * FROM kurse WHERE BenutzerID = :benID");
      $stmt->bindParam(':benID', $UserID);
      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
      }
    } catch (PDOException $e) {
      echo "Fehler: " . $e->getMessage();
    }
  }

  //lädt die Fragen + Antworten eines Studiengangs aus der DB und erzeugt eine Tabelle
  function getQuestionTable()
  {
    // Verbindung zur Datenbank herstellen und Abfrage ausführen
    // $servername = "localhost";
    // $username = "root";
    // $dbpassword = "";
    // $dbname = "mindmaze";
    include "../html-php-view/dbconnect.php";
    try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $kursID = 1;
      // Durchführen der SQL Abfrage
      $stmt = $conn->prepare("SELECT fragen.* FROM fragen LEFT JOIN antworten ON fragen.FragenID = antworten.FragenID
                                                            WHERE fragen.KursID = :kurs");
      $stmt->bindParam(':kurs', $kursID);
      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

      }
    } catch (PDOException $e) {
      echo "Fehler: " . $e->getMessage();
    }
  }
  ?>

  <div class="container">
    <!--Einklappbarer Bereich-->
    <div class="accordion" id="Questions">
      <!--"Frage einreichen"-->
      <div class="card">
        <div class="card-header" id="Question_Submit">
          <h5 class="mb-0">
            <button class="button-short" type="button" data-toggle="collapse" data-target="#collapseQuestionSubmit"
              aria-expanded="true" aria-controls="collapseQuestionSubmit">
              Frage einreichen
            </button>
          </h5>
        </div>

        <div id="collapseQuestionSubmit" class="collapse show" aria-labelledby="Frage einreichen"
          data-parent="#Questions">
          <div class="card-body">
            <div class="row">
              <form id=submitQuestion method="post">
                <div class="form-group">
                  <label for="selectStudiengang">Studiengang</label>
                  <select id="selectStudiengang" name="selectStudiengang">
                    <?php getStudiengangByUser(); ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="questionSubmitKurse">Kurs</label>
                  <select id="questionSubmitKurse" name="questionSubmitKurse">
                    <!-- AG: wird im Change befüllt) -->
                  </select>
                </div>
                <div class="form-group">
                  <label for="question">Frage:</label>
                  <textarea id="question" name="question" rows="4" cols="80"></textarea>
                </div>
                <div class="form-group">
                  <label for="answertype">Antwortart:</label>
                  <select id="answertype" name="answertype">
                    <?php getFragentyp() ?>
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
                  <!-- SH: ButtonType entfernt und ID hinzugefügt!-->
                  <input class="mt-3 button-short" id="submitBtn" value="Absenden">
                </div>
              </form>
              <!-- AG: Zu Beginn nicht sichtbares Textfeld, welches beim erfolgreichen absenden aktiviert wird-->
              <p id="submitMessage" style="display: none;">Frage wurde erfolgreich eingereicht! Danke für Ihre
                Mitarbeit!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--"Frage freigeben"-->
    <?php if ($_SESSION['ZugriffsrechteID'] == 2) {
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
      echo '</select>
                    </div>
                    <div class="form-group">';
      echo getQuestionTable();
      echo '</div>
                  </form>
                </div>
              </div>
            </div>';
    }
    ?>
  </div>

  <!-- SH: Nachfolgendes Skript hinzugefügt, um Daten in die Datenbank zu schicken !-->
  <script>
    document.getElementById('submitBtn').addEventListener('click', function () {
      //Meldungen alle deaktivieren
      document.getElementById("submitMessage").style.display = "none";

      // Daten sammeln
      var modul = document.getElementById('questionSubmitKurse').value;
      var frage = document.getElementById('question').value;
      var infotext = document.getElementById('hint').value;
      var fragentyp = document.getElementById('answertype').value;

      // Daten vorbereiten
      var data = new FormData();
      data.append('modul', modul);
      data.append('frage', frage);
      data.append('infotext', infotext);
      data.append('fragentyp', fragentyp);
      if (document.getElementById('answertype').value === '1') {
        data.append('A', document.getElementById("A").value);
        data.append('B', document.getElementById("B").value);
        data.append('C', document.getElementById("C").value);
        data.append('D', document.getElementById("D").value);
        data.append('richtigeAntwort', document.getElementById("correctAnswer").value);
      } else {
        data.append('antwort', document.getElementById('answer').value);
      }

      // Anfrage senden
      fetch('../server/uploadQuestion.php', {
        method: 'POST',
        body: data
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Fehler beim Senden der Daten.');
          }
          return response.text();
        })
        .then(data => {
          // Erfolgsmeldung anzeigen oder weitere Aktionen ausführen
          console.log('Daten erfolgreich eingereicht:', data);

          // AG: Hier wird auf die Antwort reagiert
          if (data.trim() === "true") {
            //Aktion, wenn die Frage korrekt eingereicht wurde
            //Alle Eingaben wieder leeren
            document.getElementById("submitQuestion").reset();
            //Meldung für erfolgreiches einreichen anzeigen
            document.getElementById("submitMessage").style.display = "block";
          }
          //Fehler beim Schreiben in die Datenbank
          else if (data.trim() === "false") {
            //ToDo: Eventuell Fehlermeldung anzeigen
          }
        })
        .catch(error => {
          console.error('Fehler:', error);
          // Hier kannst du Fehlerbehandlung durchführen, z.B. eine Fehlermeldung anzeigen
        });
      document.getElementById("textQuestion").value = "";
    });
  </script>

  <script>
    document.getElementById('answertype').addEventListener('change', function () {
      var auswahl = event.target.value;
      var answerfieldsDiv = document.getElementById('answerfields');

      // Leere das Div, um vorherige Felder zu entfernen
      answerfieldsDiv.innerHTML = '';

      if (auswahl === '2') {
        answerfieldsDiv.innerHTML = `<div class="form-group">
                                      <label for="answer">Antwort:</label>
                                      <textarea id="answer" name="answer" rows="4" cols="80"></textarea>
                                    </div>`;
      } else if (auswahl === '1') {
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
                                      </div>
                                      <div class="form-group">
                                        <label for="correctAnswer">Korrekte Antwort:</label>
                                        <select id="correctAnswer" name="correctAnswer"> 
                                          <option value='correctAnswerA'>A</option>"
                                          <option value='correctAnswerB'>B</option>"
                                          <option value='correctAnswerC'>C</option>"
                                          <option value='correctAnswerD'>D</option>"
                                        </select> 
                                      </div>`;
      }
    }); 
  </script>

  <script>
    //Wenn sich der Studiengang ändert, müssen die Kurse aktualisiert werden
    document.getElementById('selectStudiengang').addEventListener('change', function () {
      var data = new FormData();
      data.append('action', 'getKurseByStudiengang');
      data.append('studiengangID', document.getElementById("selectStudiengang").value);

      // Anfrage senden
      fetch('../server/kurse-server.php', {
        method: 'POST',
        body: data
      })
        // Die Antwort als Text lesen und ins HTML setzen
        .then(response => response.text())
        .then(data => {
          console.error(data);
          var questionSubmitKurseDiv = document.getElementById('questionSubmitKurse');
          questionSubmitKurseDiv.innerHTML = data.trim();
        })
        .catch(error => {
          console.error('Fehler:', error);
          // Hier kannst du Fehlerbehandlung durchführen, z.B. eine Fehlermeldung anzeigen
        });
    }); 
  </script>

  <!-- SH: Hiermit wird der eventlistener einmal aufgerufen und überprüft, welcher Fragentyp ausgewählt wurde und passt dann die Auswahlfelder an !-->
  <script> document.getElementById('answertype').dispatchEvent(new Event('change')); </script>
  <script> document.getElementById('selectStudiengang').dispatchEvent(new Event('change')); </script>

</body>

</html>