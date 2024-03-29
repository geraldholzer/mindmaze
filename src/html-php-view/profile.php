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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
    <?php 
      $_SESSION['inGame']=false;//Wird benötigt um navbar zu aktivieren 
    include ("navbar.php");
    ?>
    

    <div class="container">
        <br>
        <div class="row">
            <div class="col-md-auto center-vertically">
                <img src="../../img/profil.png" width="80" height="80" class="img-fluid" />
            </div>
            <div id=UserName class="col-md-auto center-vertically"></div>
        </div>

        <!--Einklappbarer Bereich-->
        <div class="accordion" id="Profile">
            <!--"Stammdaten"-->
            <div class="card">
                <div class="card-header" id="Stammdaten">
                    <h5 class="mb-0">
                        <button class="button-short" type="button" data-toggle="collapse"
                            data-target="#collapseStammdaten" aria-expanded="true" aria-controls="collapseStammdaten" onclick="toggleStammdaten()">
                            Stammdaten
                        </button>
                    </h5>
                </div>

                <div id="collapseStammdaten" class="collapse show" aria-labelledby="Stammdaten" data-parent="#Profile">
                    <div class="card-body">
                        <div class="row">
                            <div id=UserMail class="col-md-auto center-vertically"></div>
                        </div>
                        <div class="row">
                            <div id=UserStudiengang class="col-md-auto center-vertically"></div>
                        </div>
                        <div class="row">
                            <div id=UserZugriffsrechte class="col-md-auto center-vertically"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!--"Password ändern"-->
            <div class="card">
                <div class="card-header" id="ChangePassword">
                    <h5 class="mb-0">
                        <button class="button-short" type="button" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            Passwort ändern
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="ChangePassword" data-parent="#Profile">
                    <div class="card-body">
                        <form id=passwordForm onsubmit="changePassword(); return false;">
                            <div class="form-group">
                                <label for="txtNewPassword" class="standard-text-general">Neues Passwort</label>
                                <input id="txtNewPassword" type="password" name="txtNewPassword" required><br><br>
                            </div>

                            <div class="form-group">
                                <label for="txtNewPasswordMatch" class="standard-text-general">Neues Passwort bestätigen</label>
                                <input id="txtNewPasswordMatch" type="password" name="txtNewPasswordMatch" required
                                    style="width: 20px; height: 30px;"><br><br>
                            </div>

                            <div class="form-group">
                                <input class="mt-3 button-short" type="submit" value="Speichern">
                            </div>
                        </form>

                        <p id="successMessage" style="display: none;">Passwort wurde erfolgreich geändert.</p>
                        <p id="errorWriteMessage" style="display: none;">Ändern nicht möglich, bitte versuchen sie es später noch einmal!</p>
                        <p id="errorUnmatchMessage" style="display: none;">Die beiden Passwörter stimmen nicht überein!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changePassword() {           
            //Meldungen alle deaktivieren
            document.getElementById("successMessage").style.display      = "none";
            document.getElementById("errorWriteMessage").style.display   = "none";
            document.getElementById("errorUnmatchMessage").style.display = "none";
            
            //Aufruf von PHP-Code zum Speichern des neuen Passworts in Datenbank
            bodystring = 'txtNewPassword=' + document.getElementById('txtNewPassword').value + '&txtNewPasswordMatch=' + document.getElementById('txtNewPasswordMatch').value;

            fetch("../server/profile-server.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: bodystring,
            })      
            .then(response => response.text()) // Die Antwort als Text lesen
            .then(data => {
                // Hier wird auf die Antwort reagiert
                if (data.trim() === "true") {
                    // Aktion, wenn das Passwort korrekt geändert wurde
                    document.getElementById("passwordForm").reset();
                    document.getElementById("passwordForm").style.display = "none";
                    document.getElementById("ChangePassword").style.display = "none";
                    document.getElementById("successMessage").style.display = "block";
                }
                //Fehler beim Schreiben in die Datenbank
                else if (data.trim() === "error_write") {
                    document.getElementById("errorWriteMessage").style.display = "block";
                }
                //Die beiden eingegebenen Passwörter stimmen nicht überein
                else if (data.trim() === "error_unmatch"){
                    document.getElementById("errorUnmatchMessage").style.display = "block";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });  
        }

        //Immer wenn die Stammdaten ein/ausgeblendet werden sicherstellen, dass die "passwort ändern" Sektion auch wieder sichbar
        //sollte sie nach dem Speichern eines neuen Passworts ausgeblendet worden sein
        function toggleStammdaten() {
            document.getElementById("passwordForm").reset();
            document.getElementById("passwordForm").style.display   = "block";
            document.getElementById("ChangePassword").style.display = "block";

            //Meldungen alle deaktivieren
            document.getElementById("successMessage").style.display      = "none";
            document.getElementById("errorWriteMessage").style.display   = "none";
            document.getElementById("errorUnmatchMessage").style.display = "none";
        }

        document.addEventListener("DOMContentLoaded", function () {
            $.ajax({
                url: '../server/profile-server.php', // Datei, die die PHP-Funktion enthält
                type: 'GET',
                dataType: 'json',
                data: {
                    action: 'getUserData' // optional: Parameter für die PHP-Funktion
                },
                success: function (response) {
                    console.log(response);
                    // Erfolgreiche Antwort von der PHP-Funktion erhalten
                    $('#UserName').html('<h2 class="heading-question">' + response.Vorname + ' ' + response.Nachname + '</h2>');
                    $('#UserMail').html('<h5 class="standard-text-general"> E-Mail: ' + response.Email + '</h5>');
                    $('#UserStudiengang').html('<h5 class="standard-text-general"> Studiengang: ' + response.StudiengangBeschreibung + '</h5>');
                    $('#UserZugriffsrechte').html('<h5 class="standard-text-general"> Zugriffsrechte: ' + response.ZugriffsrechteBeschreibung + '</h5>');
                },
                error: function (xhr, status, error) {
                    // Fehler bei der Anfrage
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
</body>