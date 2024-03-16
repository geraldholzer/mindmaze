<?php 
// Prüfe, ob die POST-Variablen und SESSION-Variablen gesetzt sind
session_start();   

if(isset($_SESSION['BenutzerID'])) {
    $_SESSION['inGame']=true;
    include("navbar.php");
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
    <!-- <script src="../../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
    <script defer src="../javascript/singleplayer.js"></script>
    <!-- Popper.js and Bootstrap JS CDN links -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"></script>
</head>

<body>

<script>
        // Gib die PHP-Sessionvariable in eine JavaScript-Variable aus
        var BenutzerID = <?php echo json_encode($_SESSION['BenutzerID']); ?>;
        
        // Jetzt kannst du BenutzerID in deinem JavaScript-Code verwenden
        console.log("BenutzerID:", BenutzerID);
</script>

<!-- mt steht für margin top -->
<div class="row mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body"> 
                        <!-- Meldungscontainer -->
                        <div class="row mb-1">
                            <!-- Meldebutton -->
                            <div class="col-1 offset-11">
                                <button type="button" class="btn button-long btn-block btn-sm d-none" id="Meldebutton">Melden</button>
                            </div>
                        </div>
                        <!-- Frage und Fragemelden button -->
                        <div class="row mb-1">
                            <div class="col-10 offset-3">  
                                <div class="col-8 text-center">
                                    <p class="card-text d-none" id="question">Wer bin ich</p>
                                </div>
                            </div>
                        </div> 
                        <!-- Meldung für erfolgreiches Abschicken -->
                        <div class="alert alert-success d-none" role="alert" id="Meldunggesendet">
                            <h4 class="alert-heading">Frage gemeldet</h4>
                            <p>Danke für die Mitarbeit</p>
                            <button type="button" class="btn-close"  id="meldunggesendetclose" aria-label="Close"></button>
                        </div>
                        <!-- Meldung für nicht erfolgreiches Abschicken -->
                        <div class="alert alert-primary d-flex align-items-center d-none" role="alert" id="Meldungnichtgesendet">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <div>
                                Frage wurde bereits gemeldet
                            </div>
                            <button type="button" id="meldungnichtgesendetclose" class="btn-close" aria-label="Close"></button>
                        </div>
                        <!-- Meldungscontainer mit Absendebutton und Eingabefeld -->
                        <div class="col-10 offset-3 mx-auto d-none mb-2" id="Meldecontainer">
                            <input type="text" id="Meldetext" class="form-control-lg col-12 mb-1 meldegrund" placeholder="Grund für die Meldung eingeben">
                            <button type="button" class="btn button-long btn-block btn-sm" id="Meldungabsendenbutton">Meldung absenden</button>
                            <button type="button" class="btn button-long btn-block btn-sm" id="Meldungabbrechenbutton">Abbrechen</button>
                        </div>        

                        <!-- Dropdowns für Kurs und Anzahl Fragen -->
                        <div class="container mt-5">
                            <div class="row justify-content-center">
                                <div class="col-md-4 text-center">
                                    <!-- Fragen Dropdown -->
                                    <div class="dropdown" id="fragendropdown">
                                        <button class="btn btn-secondary dropdown-toggle w-100 text-center" type="button" id="fragenDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Anzahl Fragen
                                        </button>
                                        <ul class="dropdown-menu" id="fragenzahl" aria-labelledby="fragenDropdownButton">
                                            <li><a class="dropdown-item" href="#" data-fragen="5">5 Fragen</a></li>
                                            <li><a class="dropdown-item" href="#" data-fragen="10">10 Fragen</a></li>
                                            <li><a class="dropdown-item" href="#" data-fragen="15">15 Fragen</a></li>
                                        </ul>
                                    </div>
                                    <!-- Kurs Dropdown --> 
                                    <div class="dropdown" id="kursdropdown">
                                        <button class="btn btn-secondary dropdown-toggle mt-1 w-100" type="button" id="kursDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                                            Kurs
                                        </button>
                                        <ul class="dropdown-menu" id="kursliste" aria-labelledby="kursDropdownButton">
                                            <!-- Dropdown items -->
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Neue Zeile für den "Quiz starten" Button -->
                            <div class="row mt-3 justify-content-center">
                                <div class="col-md-4 text-center">
                                    <!-- Startbutton -->
                                    <button type="button" class="btn button-long col-11 w-100" id="Start">Quiz starten</button>
                                </div>
                            </div>
                        </div>

                        <!-- Antwortcontainer -->
                        <div class="container-fluid d-none" id="answercontainer">
                            <div class="row justify-content-center"> 
                                <button type="button" class="btn button-long col-5 mt-1" id="Answer1">Primary</button>
                                <button type="button" class="btn button-long col-5 mt-1" id="Answer2">Primary</button>
                            </div>
                            <div class="row justify-content-center"> 
                                <button type="button" class="btn button-long col-5 mt-1" id="Answer3">Primary</button>
                                <button type="button" class="btn button-long col-5 mt-1" id="Answer4">Primary</button>
                            </div>
                            <!-- Next und Beenden Button -->
                            <div class="row justify-content-center">
                                <button type="button" class="btn button-long col-8 mt-1" id="Next">Next</button>
                            </div>
                            <div class="row mb-1">
                                <!-- Beenden Button -->
                                <div class="col-1 offset-11 text-end">
                                    <button type="button" class="btn button-long btn-block btn-sm d-none" id="BeendenButton" data-bs-toggle="modal" data-bs-target="#exampleModal">Beenden</button>
                                </div>
                            </div>
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
                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="Beenden" onclick="window.location.href='./home.php'">Spiel beenden</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-none col-lg-10 mx-auto" id="explanationcontainer">
                                <div id="explanation">Standarderklärung</div>
                            </div>
                        </div>

                        <!-- Ergebnis class d-none wird entfernt, um sichtbar zu sein -->
                        <div class="d-none" id="result">
                            <h3>Ergebnis</h3>
                            <div id="resulttext"></div>
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
