


<html lang="en">
<head>
<link rel="icon" href="../../img/logo.svg" type="image/svg+xml">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supportive</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" href="../css/main.css">
  <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="../javascript/supportive.js"></script>
  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script> -->

</head>
<?php 
//Prüfe ob die POST-Variablen und SESSION-Variablen gesetzt sind
session_start();   

if(isset($_SESSION['BenutzerID'])) {
   $_SESSION['inGame']=true;//Wird benötigt um navbar zu aktivieren 
  include ("navbar.php");
  $_SESSION['inGame']=true;//Wird benötigt um navbar zu aktivieren 
?>
<body>
<script>
        // Session variable für js speichern
        var vorname = <?php echo json_encode($_SESSION['Vorname']); ?>;
        var nachname = <?php echo json_encode($_SESSION['Nachname']); ?>;
        var benutzername=vorname+" "+nachname;
      
    </script>
 
  <!-- mt steht für margin top -->
  <div class="row mt-5">
    <!-- übercontainer -->
    <div class="container">
      <!-- Reihe in der die card dargestellt werden soll -->
      <div class="row">
        <!-- Dient zur Zentrierung der Card -->
        <div class="col-lg-8 mx-auto">
          <div class="card">
            <div class="card-body">
    <!-- Meldungscontainer -->
            <div class="row mb-1">
              <!-- Meldebutton -->
                      <div class="col-1">
                          <button type="button" class="btn button-long btn-block btn-sm d-none" id="Meldebutton">Melden</button>
                      </div>
            </div>
            <!-- Frage und Fragemelden button -->
            <div class="row mb-1">
              <div class="col-10 offset-3 mx-auto ">  
                     <div class="col-8">
                          <p class="card-text d-none" id="question">Wer bin ich</p>
                      </div>
                      
              </div>
          </div> 
          <!-- Meldung für erfolgreiches abschicken -->
          <div class="alert alert-success d-none" role="alert" id="Meldunggesendet">
            <h4 class="alert-heading">Frage gemeldet</h4>
            <p>Danke für die Mitarbeit</p>
            <button type="button" class="btn-close"  id="meldunggesendetclose" aria-label="Close"></button>
          </div>
         <!-- Meldung für nicht erfolgreiches abschicken -->
         <div class="alert alert-primary d-flex align-items-center d-none" role="alert"  id="Meldungnichtgesendet">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
          </svg>
          <div>
            Frage wurde bereits gemeldet
          </div>
          <button type="button" id= "meldungnichtgesendetclose" class="btn-close" aria-label="Close"></button>
        </div>
          <!-- Meldungscontainer mit absendebutton und Eingabefeld -->
          <div class="col-10 offset-3 mx-auto d-none mb-2" id="Meldecontainer">
            <input type="text" id="Meldetext" class="form-control-lg col-12 mb-1" placeholder="Grund für die Meldung eingeben">
            <button type="button" class="btn btn-outline-primary btn-block btn-sm" id="Meldungabsendenbutton">Meldung absenden</button>
            <button type="button" class="btn btn-outline-primary btn-block btn-sm" id="Meldungabbrechenbutton">Abbrechen</button>
        </div> 
              <!-- Button zum starten eines neuen Spiels  die klasse btn-outline-primary ist bootstrap standard col-11 steht für 11 von 12 spalten 
                und eine spalte offset mt für margin top 1-->
              <button type="button" class="btn btn-outline-primary col-11 offset-1  mt-1" id="Joingame">Spiel erstellen
                oder beitreten</button>
              <!-- Quizstartbutton -->
              <button type="button" class="btn btn-outline-primary col-11 offset-1  mt-1 d-none" id="Start">Quiz
                starten</button>
              <!-- Fragetext -->
              <p class="card-text d-none col-10 mx-auto" id="question">Wer bin ich</p>
              
                <!-- Button zum Weiterschalten -->
                <div class="row justify-content-center">
                  <button  type="button" class="btn button-long col-8  mt-1"id="Next">Next</button>
                  <button  type="button" class="btn button-long col-2  mt-1"  data-bs-toggle="modal" data-bs-target="#exampleModal" id="Beenden">Beenden</button>
                </div>
                <!-- Meldung erscheint falls der Beenden button gedrückt wird -->
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Spiel beenden</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Spiel wirklich beenden?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                        <button type="button" class="btn btn-primary"   data-bs-dismiss="modal" id="Beendenmodal">Spiel beenden</button>
                      </div>
                    </div>
                  </div>
                </div>
            
                <!-- Container für die Erklärung -->
                <div class="row d-none col-lg-10 mx-auto" id="explanationcontainer">
                  <div id="explanation">Standarderklärung</div>
                </div>
                <!-- Container für den Chat -->
                <div class="row d-none col-lg-12 mx-auto" id="chatcontainer">
                <div id="chatContainer" class="border border-primary rounded mt-2" style="max-height: 280px; overflow-y: auto;">
                     <div class="row" id="chat" placeholder="Chatnachricht">
                       </div>
                  </div>
                  <input type="text" class="border border-primary rounded col-10 mt-2"
                    placeholder="Chatnachricht eingeben" id="messageInput">
                  
                  <button type="button" class="btn button-long btn-sm col-1 mt-2"
                    id="sendbutton">Senden</button>
                </div>
              </div>
              <!-- Ergebnis class d-none wird entfernt um sichtbar zu sein -->
              <div class="d-none" id="result">
                <h3>Ergebnis</h3>
                <div id="resulttext"></div>
                <a href="home.php" class="btn button-long col-8 offset-1 mt-1" id="Next">Zurück zur Startseite</a>
              </div>
              <!-- Warteanzeige -->
              <div class="d-none" id="wait">
                <h3>Warte auf Mitspieler.....</h3>
              </div>
              <!-- Container für das erstellen eines neuen Spiels oder beitreten zu einem bestehendem-->
              <div class="row d-none col-lg-10 mx-auto d-none" id="joingamecontainer">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Name für das Spiel eingeben" id="gamenameInput">
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="newgamebutton">erstellen</button>
                  </div>
                </div>
                <!-- Liste mit offenen spielen diese werden als buttons in dieses div eingefügt -->
                <div class="list-group" id="gamelist">
                </div>
                <button type="button" class="btn btn-primary btn-sm col-10 offset-1 mt-2" id="joingamebutton">Spiel
                  beitreten</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
<?php
}else{
  echo "<h1>Du bist nicht angemeldet</h1>";
}
?>