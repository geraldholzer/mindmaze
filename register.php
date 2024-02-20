<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php

//Noch umzusetzen: Prüfen ob POST Variablen set sind + Umleitung auf Startseite

        //Prüfen ob die POST-Variablen gesetzt sind
        if(isset($_POST["txtNameRegister"],$_POST["txtMailRegister"],$_POST["txtPasswordRegister"])==false){    //Prüfen ob alle benötigten POST Variablen gesetzt sind
            die("<H1>Hoppla! Da scheint etwas schiefgelaufen zu sein!</H1>");                                   //Ausgeben einer Fehlermeldung
        }

        //Session Start
        session_start();
        $validRegistration = true;  //Diese Variable wird anfangs auf true gesetzt. Bei einem Fehler der eingegebenen Werte (Benutzername, Email, Passwort), wird die Variable auf false gesetzt.
        
        
        //Überprüfen des eingegebenen Benutzernamens
        if(empty($_POST["txtNameRegister"])){                                   //Prüfe ob der eingegebene Benutzername leer ist
            echo("<p>Ungültiger Benutzername!</p>");                            //Ausgeben einer Fehlermeldung
            $validRegistration = false;                                         //Ändern der Validierungsvariable auf false
        }else{
            $_SESSION['name'] =  htmlspecialchars($_POST["txtNameRegister"]);   //Übernehmen der POST-Variable txtNamRegister in die Session-Variable "name"
        }


        //Überprüfen der eingegebenen E-Mailadresse
        if(empty($_POST["txtMailRegister"])){                                   //Prüfe ob die eingegebene Emailadresse leer ist
            echo("<p>Ungültige E-Mailadresse!</p>");                            //Fehlermeldung ausgeben
            $validRegistration = false;                                         //Ändern der Validierungsvariable auf false
        }
        else{                                                                   //Wenn die übergebene Mailadresse nicht leer ist:
            $mailPattern = "/([0-9a-zA-Z._\-])+@iu-study\.org$/";               //RegEx Pattern zum validieren der Emailadresse: Hier sollen Zahlen und Buchstaben, sowie Unterstrich, Bindestrich und Punkte erlaubt sein. Das Pattern muss enden mit @iu-study.org
            //(?i)^[a-zA-Z0-9._-]+@(iu-study\.org|iu\.org)$(?-i)

            $_SESSION['mail'] =  htmlspecialchars($_POST["txtMailRegister"]);   //Übernehmen der POST-Variable txtMailRegister in die Session-Variable "mail" 
            if(!preg_match($mailPattern, $_SESSION['mail'])){                   //Wenn die eingegebene Emailadresse nicht mit dem Pattern übereinstimmt:
                echo("<p>Ungültige E-Mailadresse!</p>");                        //Ausgeben einer Fehlermeldung
                $validRegistration = false;                                     //Ändern der Validierungsvariable auf false
            }
            else {                                                              //Hier kann bei Bedarf noch Code eingefügt werden, der ausgelöst wird, wenn die eingegebene Mailadresse den Regularien entspricht
            }
        }


        //Überprüfen des eingegebenen Passworts
        if(empty($_POST["txtPasswordRegister"])){                                                                       //Prüfe ob das eingegebene Passwort leer ist
            echo("<p>Ungültiges Passwort!</p>");                                                                        //Ausgeben einer Fehlermeldung
            $validRegistration = false;                                                                                 //Ändern der Validierungsvariable auf false
        }else{
            $_SESSION['password'] = password_hash(htmlspecialchars($_POST["txtPasswordRegister"]), PASSWORD_DEFAULT);   //Übernehmen des Hash-Wertes der POST-Variable txtPasswordRegister in die Session-Variable "password"
        }

        //Auswerten der Validierungsvariable
        if($validRegistration == false){                    //Prüfe ob die Validierungsvariable == false ist
            echo("<p>Die Registrierung war nicht erfolgreich</p>"); //Abbrechen des Codes und Ausgabe einer Fehlermeldung
        }else{

            //Einrichten der Datenbankverbindung
            $servername = "localhost";                                                                      //Server der Datenbank
            $username = "root";                                                                             //Benutzer der Datenbank
            $password = "";                                                                                 //Passwort der Datenbank
            $dbname = "mindmaze";                                                                           //Name der Datenbank
            $con = new mysqli($servername, $username, $password, $dbname);                                  //Initialisieren der Datenbankverbindung
            if ($con->connect_error) {                                                                      //Prüfe ob die Verbindung zur DB fehlgeschlagen ist
                die("Es konnte keine Verbindung zur Datenbank hergestellt werden". $con->connect_error);    //Ausgeben einer Fehlermeldung
            }

            
            //Durchführen der SQL Abfrage
            $sql = "SELECT * FROM benutzer WHERE mail = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $_SESSION['mail']);
            $stmt->execute();

            $result = $stmt->get_result();
            $allResults = $result->fetch_all();

            
            if ($allResults) {                                                                                              //Prüfen ob $allResults Elemente enthält
                echo("<p>Es ist bereits ein Benutzer mit unter der E-Mailadresse " . $_SESSION['mail'] . " registriert! </p>");   //Ausgeben einer Fehlermeldung
            } 
            else { 
                $sql = "INSERT INTO benutzer (name, passwort, mail, zugriffsrechte) VALUES ('" . $_SESSION['name'] . 
                "','" . $_SESSION['password'] . "','" . $_SESSION['mail'] . "', '1')";
                $con->query($sql);
                echo "<p>Vielen Dank, die Benutzerregistrierung war erfolgreich!</p>";
            } 
            $con->close();     //Schließen der Datenbankverbindung

        }


        //Schließen der Sitzung
        session_destroy(); //Schließen der Session


        //Automatische Weiterleitung auf die Startseite
        echo '<div id="countdown">Die Weiterleitung erfolgt in 10 Sekunden...</div>';
        echo "<span>Oder klicke </span> <a href='index.php'>hier</a>";
        echo 
        '<script>
            var seconds = 10;
            function updateCountdown() {
                document.getElementById("countdown").innerHTML = "Die Weiterleitung auf die Startseite erfolgt in " + seconds + " Sekunden...";
                if (seconds === 0) {
                    window.location.href = "index.php";
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