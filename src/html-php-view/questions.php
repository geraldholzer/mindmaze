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
  <?php session_start(); ?>
  <?php include("navbar.php"); ?>

  <?php
  //lädt die vorhandenen Studiengänge aus der DB und erstelle Comboboxeinträge-->
  function getStudiengang()
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
      $stmt = $conn->query("SELECT * FROM studiengang");

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<option value='" . $row["StudiengangID"] . "'>" . $row["Beschreibung"] . "</option>";
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

                <!-- BAUSTELLE: Hier die DB abfragen !-->
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
      // Daten sammeln
      var modul = document.getElementById('selectStudiengang').value;
      var frage = document.getElementById('question').value;
      var infotext = document.getElementById('hint').value;
      var antwort = document.getElementById('answer').value;
      var fragentyp = document.getElementById('answertype').value;

      // Daten vorbereiten
      var data = new FormData();
      data.append('modul', modul);
      data.append('frage', frage);
      data.append('antwort', antwort);
      data.append('infotext', infotext);
      data.append('fragentyp', fragentyp);

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
          // Hier kannst du weitere Aktionen ausführen, z.B. eine Erfolgsmeldung anzeigen
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
                                      </div>`;
      }
    });

    function getStudiengangfromField() {

    }
  </script>
  
  <!-- SH: Hiermit wird der eventlistener einmal aufgerufen und überprüft, welcher Fragentyp ausgewählt wurde und passt dann die Auswahlfelder an !-->
  <script> document.getElementById('answertype').dispatchEvent(new Event('change')); </script>

</body>

</html>