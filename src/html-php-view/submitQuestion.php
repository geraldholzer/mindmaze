<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Frage einreichen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
    <?php
    session_start();
    include("navbar.php");
    ?>
    <div class="container">
        <div class="row">
            <h1>Neue Frage einreichen</h1>
        </div>
        <div class="row mt-4">
            <div class="container">
                <div class="form-group col-5 mt-4">
                    <label for="selectModul">Modul</label>
                    <select class="form-control" id="selectModul">
                        <?php
                        // $servername = "localhost";
                        // $username = "root";
                        // $password = "";
                        // $dbname = "mindmaze";
                        include "../html-php-view/dbconnect.php";
                        $con = new mysqli($servername, $username, $password, $dbname);
                        if ($con->connect_error) {
                            die("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
                        }
                        $sql = "SELECT * FROM kurse";
                        $result = $con->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['KursID'] . "'>" . $row["Beschreibung"] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-5 mt-4">
                    <label for="textQuestion">Frage</label>
                    <input type="text" class="form-control" id="textQuestion" placeholder="Frage">
                </div>
                <div class="form-group col-5 mt-4">
                    <label for="textInfotext">Infotext (Optional)</label>
                    <input type="text" class="form-control" id="textInfotext" placeholder="Infotext">
                </div>
                <div class="form-group col-5 mt-4">
                    <label for="textAnswer">Antwort</label>
                    <input type="text" class="form-control" id="textAnswer" placeholder="Antwort">
                </div>
                <div class="form-group col-5 mt-4">
                    <button class="button-short mt-4" id="submitBtn">Abschicken</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('submitBtn').addEventListener('click', function () {
            // Daten sammeln
            var modul = document.getElementById('selectModul').value;
            var frage = document.getElementById('textQuestion').value;
            var infotext = document.getElementById('textInfotext').value;

            var antwort = document.getElementById('textAnswer').value;

            // Daten vorbereiten
            var data = new FormData();
            data.append('modul', modul);
            data.append('frage', frage);
            data.append('antwort', antwort);
            data.append('infotext', infotext);

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
</body>

</html>