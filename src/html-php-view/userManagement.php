<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">

</head>

<style>
    .button-short:disabled {
        /* Übernehmen Sie alle Stile der vorhandenen Button-Klasse */
        /* Fügen Sie hier Ihre eigenen benutzerdefinierten Stile hinzu */
        /* Zum Beispiel eine andere Hintergrundfarbe für deaktivierte Buttons */
        background-color: #f2f2f2;
        border-color: #f2f2f2;
        color: #ccc;
    }
</style>


<body>
    <?php
    session_start();
    //Prüfe, ob Session(Mail) gesetzt ist
    if (!(isset ($_SESSION["Email"]))) {
        die ("<H1>Hoppla! Da scheint etwas schiefgelaufen zu sein!</H1>"); //Ausgeben einer Fehlermeldung
    }
    //Prüfe ob User über die benötiten Zugriffsrechte verfügt
    if ($_SESSION['ZugriffsrechteID'] != 3) {
        echo "<h3>Du scheinst nicht über die notwendigen Zugriffsrechte zu verfügen!</h3>";
        die();
    }

    include ("navbar.php");

    include "../html-php-view/dbconnect.php";
    $con = new mysqli($servername, $username, $password, $dbname);
    if ($con->connect_error) {
        die ("Es konnte keine Verbindung zur Datenbank hergestellt werden" . $con->connect_error);
    }
    // Baue die WHERE-Bedingung basierend auf den eingereichten Filterwerten auf
    $qryVorname = isset ($_GET['Vorname']) ? $_GET['Vorname'] : "";
    $qryNachname = isset ($_GET['Nachname']) ? $_GET['Nachname'] : "";
    $qryEmail = isset ($_GET['Email']) ? $_GET['Email'] : "";
    $whereClause = "WHERE 1"; // Initialisiere die WHERE-Klausel mit 1, um alle Datensätze zu erhalten
    
    // Füge die Bedingungen für Vorname, Nachname und E-Mail hinzu, wenn sie nicht leer sind
    if (!empty ($qryVorname)) {
        $whereClause .= " AND Vorname like '" . "%" . $qryVorname . "%" . "'";
    }
    if (!empty ($qryNachname)) {
        $whereClause .= " AND Nachname like '" . "%" . $qryNachname . "%" . "'";
    }
    if (!empty ($qryEmail)) {
        $whereClause .= " AND Email like '" . "%" . $qryEmail . "%" . "'";
    }

    //Ermitteln der Anzahl aller Datensätze, wird zur Berechnung der Seiten benötigt
    $sql = "SELECT COUNT(*) AS total FROM benutzer $whereClause";
    $result = mysqli_query($con, $sql);

    // Überprüfe, ob die Abfrage erfolgreich war
    if ($result) {
        // Erhalte die Zeile mit den Ergebnissen
        $row = mysqli_fetch_assoc($result);

        // Gesamtanzahl der Datensätze
        $totalRecords = $row['total'];

    } else {
        // Fehlerbehandlung, wenn die Abfrage fehlschlägt
        echo "Fehler: " . mysqli_error($con);
    }



    // Setze die Standardwerte für die Suchfelder
    
    $maxRecords = isset ($_GET['MaxRecords']) ? $_GET['MaxRecords'] : 10;        //Anzahl der maximalen Anzahl von Datensätzen auf einer Seite
    $page = isset ($_GET['Page']) ? $_GET['Page'] : 1;                          //Aktuelle Seite
    $offset = $maxRecords * ($page - 1);                                        //Versatz für die SQL Abfrage
    $lastPage = ceil($totalRecords / $maxRecords);                              //Seitenzahl der letzten Seiten
    $nextPage = $page + 1;                                                      //Seitenzahl der nächsten Seite
    $previousPage = $page - 1;                                                  //Seitenzahl der vorangegangenen Seite
    

    //SQL Abfrage zur Ermittlung der auf einer Seite angezeigten Datensätze
    $sql = "SELECT * FROM benutzer $whereClause LIMIT $maxRecords OFFSET $offset";
    $result = $con->query($sql);
    ?>

    <div class="container">
        <div class="row">
            <div class="col-1"> </div>
            <div class="col-10">
                <h1 class="mt-3">Benutzer</h1>
                <!--Über dieses Formular kann die maximale Anzahl von Datensätzen geändert werden. Sichtbar ist nur ein Inputfeld + Button!-->
                <form id="frmMaxRecords" action="userManagement.php" method="get">
                    <select name="MaxRecords">
                        <option value="5" <?php if ($maxRecords == 5) {
                            echo ("selected");
                        } ?>>5</option>
                        <option value="10" <?php if ($maxRecords == 10) {
                            echo ("selected");
                        } ?>>10</option>
                        <option value="25" <?php if ($maxRecords == 25) {
                            echo ("selected");
                        } ?>>25</option>
                    </select>
                    <input name="Page" value="1" type="hidden" />
                    <input name="Vorname" value="<?php echo ($qryVorname) ?>" type="hidden" />
                    <input name="Nachname" value="<?php echo ($qryNachname) ?>" type="hidden" />
                    <input name="Email" value="<?php echo ($qryEmail) ?>" type="hidden" />
                    <button class="button-short" type="submit">Ok</button>
                </form>
                <!--Hauptformular mit Datensätzen und Comboboxen zur Änderung der Berechtigungen!-->
                <form class="mt-3" method="post">

                    <table class='table table-striped'>
                        <tr>
                            <th>ID</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>E-mail</th>
                            <th>Zugriffsrechte</th>
                            <th>Passwort ändern</th>
                            <th></th>
                        </tr>

                        <?php
                        // Zeige die Benutzer und ihre aktuellen Zugriffsrechte an
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["BenutzerID"] . "</td>";
                            echo "<td>" . $row["Vorname"] . "</td>";
                            echo "<td>" . $row["Nachname"] . "</td>";
                            echo "<td>" . $row["Email"] . "</td>";
                            echo "<td>";
                            echo "<select id='rechte_" . $row["BenutzerID"] . "'>"; // name='ZugriffsrechteID[" . $row["BenutzerID"] . "]'
                            echo "<option value='1' " . ($row["ZugriffsrechteID"] == 1 ? "selected" : "") . ">Nutzer</option>";
                            echo "<option value='2' " . ($row["ZugriffsrechteID"] == 2 ? "selected" : "") . ">Tutor</option>";
                            echo "<option value='3' " . ($row["ZugriffsrechteID"] == 3 ? "selected" : "") . ">Admin</option>";
                            echo "</select>";
                            echo "</td>";
                            echo "<td><input id='password_" . $row["BenutzerID"] . "' name='password' type='text'></td>";
                            echo "<td><button onclick='changePassword(" . $row["BenutzerID"] . "); event.preventDefault()'>Bestätigen</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </form>


                <script>
                    function changePassword(userID) {
                        // Das Passwortfeld abrufen und den Wert extrahieren
                        var password = document.getElementById("password_" + userID).value;
                        var rechte = document.getElementById("rechte_" + userID).value;
                        var data = new FormData();
                        data.append('BenutzerID', userID);
                        data.append('newPassword', password);
                        data.append('Zugriffsrechte', rechte);
               
                        // Fetch-Anfrage senden, um das Passwort zu ändern
                        fetch('../server/change-password.php', {
                            method: 'POST',
                            body: data
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.text();
                            })
                            .then(data => {
                                // Erfolgreiche Antwort verarbeiten
                                console.log('Antwort vom Server:', data);
                                // Hier kannst du weitere Aktionen durchführen, z. B. eine Erfolgsmeldung anzeigen
                            })
                            .catch(error => {
                                // Fehler verarbeiten
                                console.error('Fetch fehlgeschlagen:', error);
                            });
                            document.getElementById("password_" + userID).innerHTML = empty;
                    }
                </script>


                <div class=container>
                    <div class="row">

                        <div class="col">
                            <!--Formular für den "Zurück" Button!-->

                            <form action="userManagement.php" method="get">
                                <input type="hidden" name="Page" value="<?php echo $previousPage ?>">
                                <input type="hidden" name="MaxRecords" value="<?php echo ($maxRecords) ?>" />
                                <input name="Vorname" value="<?php echo ($qryVorname) ?>" type="hidden" />
                                <input name="Nachname" value="<?php echo ($qryNachname) ?>" type="hidden" />
                                <input name="Email" value="<?php echo ($qryEmail) ?>" type="hidden" />
                                <!-- Hier die Seitenzahl entsprechend aktualisieren -->
                                <button class="button-short" type="submit" <?php if ($page == 1) {
                                    echo "disabled";
                                } ?>>Zurück</button>
                            </form>
                        </div>

                        <div class="col">
                            <!--Formular für die Combobox mit Seitenauswahl!-->
                            <form id="pages" action="userManagement.php" method="get">
                                <input type="hidden" name="MaxRecords" value="<?php echo ($maxRecords) ?>" />
                                <input name="Vorname" value="<?php echo ($qryVorname) ?>" type="hidden" />
                                <input name="Nachname" value="<?php echo ($qryNachname) ?>" type="hidden" />
                                <input name="Email" value="<?php echo ($qryEmail) ?>" type="hidden" />
                                <select name="Page" id="changePage">
                                    <!--Eintragen der Optionen in das Select Feld: Zahlen von 1 bis "letzte Seite"!-->
                                    <?php for ($i = 1; $i <= $lastPage; $i++) {
                                        echo "<option value='" . $i . "'";
                                        if ($i == $page) {
                                            echo " selected";
                                        }
                                        echo ">" . $i . "</option>";
                                    } ?>f

                                </select>
                                <!--Der Event-Listener schickt das Formular ab, sobald die Combobox verändert wurde!-->
                                <script>
                                    document.getElementById("changePage").addEventListener("change", function () {
                                        document.getElementById("pages").submit();
                                    });
                                </script>
                            </form>
                        </div>
                        <div class="col">
                            <!--Formular für den "Weiter" Button!-->
                            <form action="userManagement.php" method="get">
                                <input type="hidden" name="Page" value=<?php echo $nextPage ?>>
                                <input type="hidden" name="MaxRecords" value="<?php echo ($maxRecords) ?>" />
                                <input name="Vorname" value="<?php echo ($qryVorname) ?>" type="hidden" />
                                <input name="Nachname" value="<?php echo ($qryNachname) ?>" type="hidden" />
                                <input name="Email" value="<?php echo ($qryEmail) ?>" type="hidden" />
                                <button class="button-short" type="submit" <?php if ($page == $lastPage) {
                                    echo "disabled";
                                } ?>>Weiter</button>
                            </form>
                        </div>

                    </div>
                </div>

                <form action="userManagement.php" method="GET">
                    <input type="hidden" name="MaxRecords" value="<?php echo ($maxRecords) ?>">
                    <label for="Vorname">Vorname:</label><br>
                    <input type="text" id="Vorname" name="Vorname" value="<?php echo ($qryVorname) ?>"><br><br>

                    <label for="Nachname">Nachname:</label><br>
                    <input type="text" id="Nachname" name="Nachname" value="<?php echo ($qryNachname) ?>"><br><br>

                    <label for="Email">E-mail:</label><br>
                    <input type="text" id="Email" name="Email" value="<?php echo ($qryEmail) ?>"><br><br>

                    <input class="button-short" accept="" type="submit" value="Filtern">
                </form>
            </div>
            <div class="col-1"> </div>
        </div>
    </div>

</body>

</html>