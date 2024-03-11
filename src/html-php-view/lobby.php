
<?php 
//Prüfe ob die POST-Variablen und SESSION-Variablen gesetzt sind
session_start();   

if(isset($_SESSION['BenutzerID'])) {
  include ("navbar.php");
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
    <!-- <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
    <script defer src="../javascript/lobby.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
</head>

<body>



  <!-- mt steht für margin top -->
  <div class="row mt-5" >
    <!-- übercontainer -->
    <div class="container">
      <!-- Reihe in der die card dargestellt werden soll -->
      <div class="row">
        <!-- Dient zur Zentrierung der Card -->
        <div class="col-lg-8 mx-auto">
          <div class="card">
            <div class="card-body">
              <!-- Button zum starten eines neuen Spiels  die klasse btn-outline-primary ist bootstrap standard col-11 steht für 11 von 12 spalten 
                und eine spalte offset mt für margin top 1-->
              <button  type="button" class="btn btn-outline-primary col-11 offset-1  mt-1"id="Joingame">Spiel erstellen oder beitreten</button>
              <!-- Container für das erstellen eines neuen Spiels oder beitreten zu einem bestehendem-->
              <div class="row d-none col-lg-10 mx-auto d-none" id="joingamecontainer">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="Name für das Spiel eingeben"id="gamenameInput">
                  
                  <!-- Spielmodus Dropdown -->
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="spielmodusDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                      Spielmodus
                    </button>
                    <ul class="dropdown-menu" id="spielmodusliste"aria-labelledby="spielmodusDropdownButton">
                    </ul>
                  </div>
                  <!-- Modul Dropdown -->
                  <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="kursDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                      Kurs
                    </button>
                    <ul class="dropdown-menu" id="kursliste" aria-labelledby="kursDropdownButton">
                    </ul>
                  </div>
                   <!-- fragen Dropdown -->
                   <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="fragenDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                      Anzahl Fragen
                    </button>
                    <ul class="dropdown-menu" id="fragenzahl" aria-labelledby="kursDropdownButton">
                      <li><a class = "dropdown-item" href="#" data-fragen="5">5 Fragen</a></li>
                      <li><a class = "dropdown-item" href="#"data-fragen="10">10 Fragen</a></li>
                      <li><a class = "dropdown-item" href="#"data-fragen="15">15 Fragen</a></li>
                    </ul>
                  </div>
                  <!-- Button zum erstellen eines neuen Spiels -->
                  <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="newgamebutton">erstellen</button>
                  </div>
                </div>  
                <!-- Liste mit offenen spielen diese werden als buttons in dieses div eingefügt -->
                <table id="gamelist" class="tableLobby">
                  <thead>
                    <tr>
                      <th>Spielname</th>
                      <th>Modus</th>
                      <th>Modul</th>
                      <th>Fragen</th>
                    </tr>
                  </thead>
                    <tbody id="gamelistbody">
                     # 
                    </tbody>
                </table>
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