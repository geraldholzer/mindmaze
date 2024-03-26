<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stammdaten</title>
    <script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<style>
    /* CSS für die Formulare */
    .form-border {
        border: 2px solid black;
        padding: 20px;
        width: 300px;
        margin: 20px auto;
        height: 150px;
        /* 20px Abstand oben und unten, zentriert */
    }

    .border-black {
        border: 2px solid black;
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset ($_POST['StudiengangNeu'])) {
            $sql = "INSERT INTO studiengang (Beschreibung) VALUES ('" . $_POST['StudiengangNeu'] . "')";
            $con->query($sql);
        }
        if (isset ($_POST['KursNeu'])) {
            $sql = "INSERT INTO kurse   (Beschreibung) VALUES ('" . $_POST['KursNeu'] . "')";
            $con->query($sql);
        }

        //Studiengang<->Kurs Löschen
        if (isset ($_POST['LoescheStudiengangkursID'])) {
            $sql = "DELETE FROM studiengangkurse WHERE StudiengangkursID='" . $_POST['LoescheStudiengangkursID'] . "'";
            $con->query($sql);
        }



        //Studiengang Löschen
        if (isset ($_POST['LoescheStudiengangID'])) {
            $sql = "DELETE FROM studiengang WHERE StudiengangID='" . $_POST['LoescheStudiengangID'] . "'";
            $con->query($sql);
        }

        //Studiengang Löschen
        if (isset ($_POST['LoescheKursID'])) {
            $sql = "DELETE FROM kurse WHERE KursID='" . $_POST['LoescheKursID'] . "'";
            $con->query($sql);
        }

        if (isset ($_POST['Kurs']) && isset ($_POST['Studiengang'])) {
            // Überprüfen, ob bereits eine Verknüpfung existiert
            $existingQuery = "SELECT * FROM studiengangkurse WHERE StudiengangID = '" . $_POST['Studiengang'] . "' AND KursID = '" . $_POST['Kurs'] . "'";
            $existingResult = $con->query($existingQuery);

            //Prüfe ob bereits eine Verknüpfung existiert
            if ($existingResult->num_rows == 0) {
                $sql = "INSERT INTO studiengangkurse (StudiengangID, KursID) VALUES ('" . $_POST['Studiengang'] . "', '" . $_POST['Kurs'] . "')";
                $con->query($sql);
            } else {

            }
        }


        if (isset ($_POST['Kurs']) && isset ($_POST['Benutzer'])) {




            $sql = "UPDATE kurse SET BenutzerID = '" . $_POST["Benutzer"] . "' WHERE KursID = '" . $_POST['Kurs'] . "'";
            $con->query($sql);

        }


    } else {
        // Die Seite wurde nicht über die POST-Methode aufgerufen
        // Hier kannst du z.B. eine Fehlermeldung ausgeben oder 
        // auf eine andere Seite weiterleiten
    }

    //Ermitteln der Anzahl aller Datensätze, wird zur Berechnung der Seiten benötigt
    $sql = "SELECT COUNT(*) AS total FROM studiengang";
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

    $maxRecords = isset ($_GET['MaxRecords']) ? $_GET['MaxRecords'] : 10;        //Anzahl der maximalen Anzahl von Datensätzen auf einer Seite
    $page = isset ($_GET['Page']) ? $_GET['Page'] : 1;                          //Aktuelle Seite
    $offset = $maxRecords * ($page - 1);                                        //Versatz für die SQL Abfrage
    $lastPage = ceil($totalRecords / $maxRecords);                              //Seitenzahl der letzten Seiten
    $nextPage = $page + 1;                                                      //Seitenzahl der nächsten Seite
    $previousPage = $page - 1;                                                  //Seitenzahl der vorangegangenen Seite
    

    $sql = "SELECT * FROM Studiengang LIMIT $maxRecords OFFSET $offset";
    $StudiengangTabelle = $con->query($sql);
    $sql = "SELECT kurse.KursID as KursID, kurse.Beschreibung as Beschreibung, benutzer.Vorname as Vorname, benutzer.Nachname as Nachname FROM Kurse LEFT JOIN benutzer ON kurse.BenutzerID = benutzer.BenutzerID";
    $KurseTabelle = $con->query($sql);
    $sql = "SELECT * FROM Studiengang";
    $Studiengaenge = $con->query($sql);
    $sql = "SELECT * FROM Kurse";
    $Kurse = $con->query($sql);
    $sql = "SELECT * FROM benutzer WHERE ZugriffsrechteID = 2";
    $Benutzer = $con->query($sql);
    $sql =
        "SELECT 
    studiengangkurse.StudiengangkursID AS StudiengangkursID,
    kurse.KursID AS KursID, 
    kurse.Beschreibung AS Kursbeschreibung,
    studiengang.Beschreibung AS Studiengangbeschreibung,
    studiengang.StudiengangID AS StudiengangID 
FROM 
    studiengangkurse 
INNER JOIN 
    kurse ON studiengangkurse.KursID = kurse.KursID 
INNER JOIN 
    studiengang ON studiengangkurse.StudiengangID = studiengang.StudiengangID";
    $StudiengangKurseTabelle = $con->query($sql);
    ?>

    <div class="container">
        <div class="row">

            <div class="col-3">
                <!--Neuen Studiengang anlegen !-->
                <form class="form-border" method="post" action="backend-data.php">
                    <h4>Neuer Studiengang</h2>
                        <input name="StudiengangNeu"></input>
                        <button type="submit">Neu Anlegen</button>
                </form>
            </div>
            <div class="col-3">


                <!--Neuen Kurs anlegen !-->
                <form class="form-border" method="post" action="backend-data.php">
                    <h4>Neuer Kurs</h2>
                        <input name="KursNeu"></input>
                        <button type="submit">Neu Anlegen</button>
                </form>
            </div>
            <div class="col-3">

                <!-- Neue Kurs-Studiengang Beziehung anlegen !-->
                <form class="form-border" method="post" action="backend-data.php">
                    <h4>Studiengang - Kurse zuordnen</h2>

                        <select name="Studiengang">
                            <?php
                            while ($row = $Studiengaenge->fetch_assoc()) {
                                echo "<option value='" . $row["StudiengangID"] . "'>" . $row["Beschreibung"] . "</option>";
                            }
                            ?>
                        </select>

                        <select name="Kurs">
                            <?php
                            while ($row = $Kurse->fetch_assoc()) {
                                echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
                            }
                            ?>
                        </select>
                        <button type="submit">Neu Anlegen</button>
                </form>
                <!-- Zeiger zurücksetzen !-->
                <?php $Kurse->data_seek(0); ?>

            </div>
            <div class="col-3">
                <!-- Tutor zu einem Kurs hinzufügen !-->
                <form class="form-border" method="post" action="backend-data.php">
                    <h4>Tutor einsetzen</h2>



                        <select name="Kurs">
                            <?php
                            while ($row = $Kurse->fetch_assoc()) {
                                echo "<option value='" . $row["KursID"] . "'>" . $row["Beschreibung"] . "</option>";
                            }
                            ?>
                        </select>

                        <select name="Benutzer">
                            <?php
                            while ($row = $Benutzer->fetch_assoc()) {
                                echo "<option value='" . $row["BenutzerID"] . "'>" . $row["Vorname"] . " " . $row["Nachname"] . " " . $row["Email"] . "</option>";
                            }
                            ?>
                        </select>

                        <button type="submit">Neu Anlegen</button>

                </form>
            </div>

            <div class="container mt-5">
                <div class="row">
                    <div class="col-6">

                        <!-- Tabelle mit Studiengängen !-->
                        <h4>Studiengänge</h4>
                        <table class='table table-striped border-black'>
                            <tr>
                                <th>ID</th>
                                <th>Studiengang</th>
                                <th>Kurse</th>

                            </tr>

                            <?php
                            while ($row = $StudiengangTabelle->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["StudiengangID"] . "</td>";
                                echo "<td>" . $row["Beschreibung"] . "</td>";
                                echo "<td>";
                                $sql = "SELECT * FROM kurse INNER JOIN studiengangkurse ON studiengangkurse.KursID = kurse.KursID WHERE studiengangkurse.StudiengangID = " . $row['StudiengangID'];
                                $StudiengangKurse = $con->query($sql);

                                if ($StudiengangKurse->num_rows == 0) {
                                    echo "<form method='POST' action='backend-data.php'>";
                                    echo "<input type='hidden' name='LoescheStudiengangID' value='" . $row["StudiengangID"] . "'>";
                                    // Lösch-Button
                                    echo "<button type='submit' name='deleteButton'>Löschen</button>";
                                    echo "</form>";
                                }

                                while ($rowStudiengangkurse = $StudiengangKurse->fetch_assoc()) {
                                    echo $rowStudiengangkurse["Beschreibung"] . "<br>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>


                    <div class="col-6">
                        <!-- Tabelle mit Kursen !-->
                        <h4>Kurse</h4>
                        <table class='table table-striped border-black'>
                            <tr>
                                <th>ID</th>
                                <th>Kurse</th>
                                <th>Studiengang</th>
                                <th>Tutor</th>
                            </tr>

                            <?php
                            while ($rowKurse = $KurseTabelle->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $rowKurse["KursID"] . "</td>";
                                echo "<td>" . $rowKurse["Beschreibung"] . "</td>";
                                echo "<td>";
                                $sql = "SELECT * FROM studiengang INNER JOIN studiengangkurse ON studiengangkurse.StudiengangID = studiengang.StudiengangID WHERE studiengangkurse.KursID = " . $rowKurse['KursID'];
                                $KurseStudiengang = $con->query($sql);

                                if ($KurseStudiengang->num_rows == 0) {
                                    echo "<form method='POST' action='backend-data.php'>";
                                    echo "<input type='hidden' name='LoescheKursID' value='" . $rowKurse["KursID"] . "'>";
                                    // Lösch-Button
                                    echo "<button type='submit' name='deleteButton'>Löschen</button>";
                                    echo "</form>";
                                }

                                while ($rowKurseStudiengang = $KurseStudiengang->fetch_assoc()) {
                                    echo $rowKurseStudiengang["Beschreibung"] . "<br>";
                                }
                                echo "</td>";
                                echo "<td>" . $rowKurse["Vorname"] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-5">

                        <!-- Verknüpfungstabelle !-->
                        <table class='table table-striped border-black'>
                            <tr>
                                <th>ID</th>
                                <th>KursID</th>
                                <th>StudiengangID</th>
                                <th>Kurs</th>
                                <th>Studiengang</th>
                                <th>Zuordnung löschen</th>
                            </tr>

                            <?php
                            while ($Zuordnung = $StudiengangKurseTabelle->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $Zuordnung["StudiengangkursID"] . "</td>";

                                echo "<td>" . $Zuordnung["KursID"] . "</td>";
                                echo "<td>" . $Zuordnung["StudiengangID"] . "</td>";
                                echo "<td>" . $Zuordnung["Kursbeschreibung"] . "</td>";
                                echo "<td>" . $Zuordnung["Studiengangbeschreibung"] . "</td>";
                                echo "<td>";
                                echo "<form method='POST' action='backend-data.php'>";
                                echo "<input type='hidden' name='LoescheStudiengangkursID' value='" . $Zuordnung["StudiengangkursID"] . "'>";
                                // Lösch-Button
                                echo "<button type='submit' name='deleteButton'>Löschen</button>";
                                echo "</form>";
                                echo "</td>";

                                echo "</tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>


</body>

<?php $con->close(); ?>

</html>