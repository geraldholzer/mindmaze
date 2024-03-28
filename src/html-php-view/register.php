<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link rel="stylesheet" href="../css/main.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    //Noch umzusetzen: Prüfen ob POST Variablen set sind + Umleitung auf Startseite
    
    //Prüfen ob die POST-Variablen gesetzt sind
    if (isset($_POST["txtFirstNameRegister"], $_POST["txtLastNameRegister"], $_POST['selectStudiengang'], $_POST["txtMailRegister"], $_POST["txtPasswordRegister"], $_POST['csrf_token']) == false) {    //Prüfen ob alle benötigten POST Variablen gesetzt sind
        die("<H1>Hoppla! Da scheint etwas schiefgelaufen zu sein!</H1>");                                   //Ausgeben einer Fehlermeldung
    }

    if(!$_POST['csrf_token'] === $_SESSION['csrf_token']){
        die("<h2>Die Session ist ungültig</h2>");
    }

    //Session Start
    $validRegistration = true;  //Diese Variable wird anfangs auf true gesetzt. Bei einem Fehler der eingegebenen Werte (Benutzername, Email, Passwort), wird die Variable auf false gesetzt.
    
    $studiengang = filter_input(INPUT_POST, 'selectStudiengang', FILTER_SANITIZE_NUMBER_INT);

    //Überprüfen des eingegebenen Nachnamens
    if (empty($_POST["txtLastNameRegister"])) {                                   //Prüfe ob der eingegebene Nachname leer ist
        echo ("<p>Ungültiger Nachname!</p>");                            //Ausgeben einer Fehlermeldung
        $validRegistration = false;                                         //Ändern der Validierungsvariable auf false
    } else {
        //Übernehmen der POST-Variable txtNamRegister in die Session-Variable "name"
        $nachname = filter_input(INPUT_POST, 'txtLastNameRegister', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    //Überprüfen des eingegebenen Vornamens
    if (empty($_POST["txtFirstNameRegister"])) {                                   //Prüfe ob der eingegebene Nachname leer ist
        echo ("<p>Ungültiger Vorname!</p>");                            //Ausgeben einer Fehlermeldung
        $validRegistration = false;                                         //Ändern der Validierungsvariable auf false
    } else {
        //Übernehmen der POST-Variable txtNamRegister in die Session-Variable "name"
    
        $vorname = filter_input(INPUT_POST, 'txtFirstNameRegister', FILTER_SANITIZE_SPECIAL_CHARS);
    }


    //Überprüfen der eingegebenen E-Mailadresse
    if (empty($_POST["txtMailRegister"])) {                                   //Prüfe ob die eingegebene Emailadresse leer ist
        echo ("<p>Ungültige E-Mailadresse!</p>");                            //Fehlermeldung ausgeben
        $validRegistration = false;                                         //Ändern der Validierungsvariable auf false
    } else {                                                                   //Wenn die übergebene Mailadresse nicht leer ist:
        $mailPattern = "/([0-9a-zA-Z._\-])+@iu-study\.org$/";               //RegEx Pattern zum validieren der Emailadresse: Hier sollen Zahlen und Buchstaben, sowie Unterstrich, Bindestrich und Punkte erlaubt sein. Das Pattern muss enden mit @iu-study.org
        //(?i)^[a-zA-Z0-9._-]+@(iu-study\.org|iu\.org)$(?-i)
        $email = filter_input(INPUT_POST, 'txtMailRegister', FILTER_SANITIZE_EMAIL);
        if (!preg_match($mailPattern, $email)) {                   //Wenn die eingegebene Emailadresse nicht mit dem Pattern übereinstimmt:
            echo ("<p>Ungültige E-Mailadresse!</p>");                        //Ausgeben einer Fehlermeldung
            $validRegistration = false;                                     //Ändern der Validierungsvariable auf false
        } else {                                                              //Hier kann bei Bedarf noch Code eingefügt werden, der ausgelöst wird, wenn die eingegebene Mailadresse den Regularien entspricht
        }
    }


    //Überprüfen des eingegebenen Passworts
    if (empty($_POST["txtPasswordRegister"])) {                                                                       //Prüfe ob das eingegebene Passwort leer ist
        echo ("<p>Ungültiges Passwort!</p>");                                                                        //Ausgeben einer Fehlermeldung
        $validRegistration = false;                                                                                 //Ändern der Validierungsvariable auf false
    } else {
        $passwort = password_hash(htmlspecialchars($_POST["txtPasswordRegister"]), PASSWORD_DEFAULT);   //Übernehmen des Hash-Wertes der POST-Variable txtPasswordRegister in die Session-Variable "password"
    }

    //Auswerten der Validierungsvariable
    if ($validRegistration == false) {                    //Prüfe ob die Validierungsvariable == false ist
        echo ("<p>Die Registrierung war nicht erfolgreich</p>"); //Abbrechen des Codes und Ausgabe einer Fehlermeldung
    } else {




        //Einrichten der Datenbankverbindung
        include "../html-php-view/dbconnect.php"; // Bitte die Datei anpassen, um die Verbindung zur Datenbank herzustellen
        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Fehlermodus auf Ausnahme setzen
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vorbereiten der SQL-Abfrage
            $stmt = $pdo->prepare("SELECT * FROM benutzer WHERE Email = :email");
            $stmt->bindParam(':email', $_POST["txtMailRegister"]);
            $stmt->execute();

            // Überprüfen, ob ein Ergebnis zurückgegeben wurde
            if ($stmt->rowCount() > 0) {
                echo ("<p>Es ist bereits ein Benutzer mit der E-Mail-Adresse " . $_POST["txtMailRegister"] . " registriert!</p>");
            } else {
                // Vorbereiten und Ausführen des INSERT-Statements mit vorbereiteten Anweisungen
                $stmt = $pdo->prepare("INSERT INTO benutzer (Nachname, Passwort, Vorname, Email, StudiengangID, ZugriffsrechteID) VALUES (:nachname, :passwort, :vorname, :email, :studiengangID, 1)");
                $stmt->bindParam(':nachname', $nachname);
                $stmt->bindParam(':passwort', $passwort);
                $stmt->bindParam(':vorname', $vorname);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':studiengangID', $studiengang);
                $stmt->execute();

                echo "<p>Vielen Dank, die Benutzerregistrierung war erfolgreich!</p>";
            }
        } catch (PDOException $e) {
            // Bei einem Fehler Ausgabe der Fehlermeldung
            echo "Error: " . $e->getMessage();
        }

        // Schließen der Verbindung
        $pdo = null;
    }
    //Schließen der Sitzung
    session_destroy(); //Schließen der Session
    

    //Automatische Weiterleitung auf die Startseite
    echo '<div id="countdown">Die Weiterleitung erfolgt in 10 Sekunden...</div>';
    echo "<span>Oder klicke </span> <a href='../../index.php'>hier</a>";
    echo
        '<script>
            var seconds = 10;
            function updateCountdown() {
                document.getElementById("countdown").innerHTML = "Die Weiterleitung auf die Startseite erfolgt in " + seconds + " Sekunden...";
                if (seconds === 0) {
                    window.location.href = "../../index.php";
                } else {
                    seconds--;
                    setTimeout(updateCountdown, 1000);
                }
            }
            setTimeout(updateCountdown, 1000); // Starte den Countdown
        </script>';

    ?>


</body>

</html>