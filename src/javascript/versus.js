//Elemente aus dem DOM holen
let questioncounter = 0 //zähler für die aktuelle Frage
let pointscounter = 0 //Zähler für die erreichten Punkte
let fragenzahl =3;
let AnswerButton1 = document.getElementById('Answer1') //Antwortbutton1
let AnswerButton2 = document.getElementById('Answer2') //Antwortbutton2
let AnswerButton3 = document.getElementById('Answer3') //Antwortbutton3
let AnswerButton4 = document.getElementById('Answer4') //Antwortbutton4
let NextButton = document.getElementById('Next') //Nextbutton
let Question = document.getElementById('question') //Frage text
let StartButton = document.getElementById('Start') //Startbutton
let answercontainer = document.getElementById('answercontainer') //COntainer (div) in dem sich die answerbuttons befinden
let resultpage = document.getElementById('result') //wird eingeblendet am schluss als ergebnis
let resuttext = document.getElementById('resulttext') // Zeigt am Schluss etwa ihr habt "3/3" Fragen richtig beantwortet
let explanation = document.getElementById('explanation') //Erklärungstext zu jeder Frage
let explanationcontainer = document.getElementById('explanationcontainer') //Container(div)in dem sich die Erklärung befindet
let chatcontainer = document.getElementById('chatcontainer') //Container (div) für den chat
let messageInput = document.getElementById('messageInput') //Inputfeld für die chatnachricht
let chat = document.getElementById('chat') //chat div hier wird der chat verlauf angezeigt
let sendbutton = document.getElementById('sendbutton') //button zum absenden einer Chatnachricht
let joinbutton = document.getElementById('Joingame') //Dient zum aufrufen der Seite mit den offenen Spielen
let joingamebutton = document.getElementById('joingamebutton') //Mit diesem Button kann man einem Spiel beitreten
let newgamebutton = document.getElementById('newgamebutton') //Mit diesem Button kann man ein neues Spiel erstellen
let joingamecontainer = document.getElementById('joingamecontainer') //Container der Seite mit offenen spielen
let gamelist = document.getElementById('gamelist') //Liste mit den offenen spielen
let waitforopponent = document.getElementById('wait') //Zeigt Warte auf Gegner
let room = '' // die Spielsitzungen werden als WebsocketRäume umgesetzt damit immer nur 2 Spieler gleichzeitig spielen können
let gamesarray = [] // Hier werden die offenen Spiele die aus der Datenbank geholt wurden gespeichert
let answered = false //Verhindert eine Endlosschleife bei den Answerbuttons
let ready = false // wird wahr wen sich der zweite Spieler dem spiel anschließt
let gamenameInput = document.getElementById('gamenameInput') //Eingabefeld für den Spielnamen
//let gameserver="http://13.49.243.225/game-server.php" //gameserver ip von aws server
let gameserver="../server/game-server.php"// lokaler gameserver
//let questionserver= "http://13.49.243.225/question-server.php"//questionserver ip von aws server
let questionserver= "../server/question-server.php"// lokaler question server
//let websocketserver="ws://13.49.243.225:8081"//websocket server auf aws server
let websocketserver = 'ws://127.0.0.1:8081' // lokaler websocketserver
let opponentpoints = 0

room = localStorage.getItem("gamenameübergabe");
let spielname= localStorage.getItem("spielname"); //wird zum löschen des spiels gebraucht

// Websocket für Multiplayer//////////////////////////////////////////////////////////////////////////////////
//Verbindung zu Websocketserver erstellen der PORT 8081 weil ich sonst einen Konflikt mit XAMPP hatte  ip adresse von aws
const socket = new WebSocket(websocketserver) 

socket.onopen = (event) => {
    console.log('WebSocket connection opened:', event)
    joingame();
}

//Mit dieser function wird der benutzer zum entsprechenden raum hinzugefügt mit subsribeToRoom und Warteseite eingeblendet
function joingame() {
    subscribeToRoom(room)
    joingamecontainer.classList.add('d-none')
    joinbutton.classList.add('d-none')
    waitforopponent.classList.remove('d-none')
}

//Buttons in Array verwalten so kann man foreach schleifen nutzen
const Answerbuttons = [
    AnswerButton1,
    AnswerButton2,
    AnswerButton3,
    AnswerButton4,
]

//Array mit den Fragen jede Frage hat ein Array mit Antworten mit attribut correct für die richtige Antwort
// Wird mit fetch von PHP geholt
function laden() {
    fetch(questionserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        //diese action wird im server abgefragt
        body: 'action=fragenladen',
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    `Network response was not ok: ${response.statusText}`
                )
            }
            return response.json() // JSON-Daten aus der Antwort extrahieren
        })
        .then((data) => {
            //Array daten zuweisen
            questions = data
        })
        .then(zuweisen)
        .catch((error) => {
            console.error('Fehler beim Abrufen der Daten:', error)
        })
}

//Eventlistener für next button
NextButton.addEventListener('click', next)
//Eventlistener für Startbutton
StartButton.addEventListener('click', startquiz)

//Hier wird zuerst die laden funktion aufgerufen und anschließend die entsprechenden buttons ein/aus geblendet
function startquiz() {
    laden()
    StartButton.classList.add('d-none')
    Question.classList.remove('d-none')
    answercontainer.classList.remove('d-none')
    chatcontainer.classList.remove('d-none')
}
// bei drücken des Next buttons wird die funktion zuweisen aufgerufen  oder die Fragen sind fertig -> finish
function next() {
    if (questioncounter >= fragenzahl) {
        sendfinishflag()

    } else {
        zuweisen()
    }
}

//Funktion zum Zuweisen der Fragen und Antworten zu den  Buttons
function zuweisen() {
    mixedanswers = questions[questioncounter].answers
    for (let i = 0; i < 4; i++) {
        explanation.innerHTML = questions[questioncounter].explanation
        Question.innerHTML = questions[questioncounter].questiontext
        Question.dataset.id = questions[questioncounter].questionid
        Answerbuttons[i].innerHTML = mixedanswers[i].answer
        Answerbuttons[i].dataset.answerid = mixedanswers[i].answerid
        //Event listener für auswahl
        Answerbuttons[i].addEventListener('click', antworten)
    }
    //inkrementieren des questioncounter
    questioncounter++
    //reset um wieder alles richtig einzublenden und die richtigen buttons freizugeben
    reset()
}
//Funktion zum zurücksetzen der class Attributte für richtige und falsche Antworten und freigeben der Buttons
function reset() {
    answered = false
    buttonpressed = false
    explanationcontainer.classList.add('d-none')
    waitforopponent.classList.add('d-none')
    Answerbuttons.forEach((button) => {
        button.classList.add('btn-outline-primary')
        button.classList.remove('btn-danger')
        button.classList.remove('btn-success')
    })
    Answerbuttons.forEach((button) => {
        button.disabled = false
    }),
        (NextButton.disabled = true)
}

// Ausblenden des answercontainers und einblenden des Ergebnistexts
function finish() {
    let winner = 1;
    let winnertext="";
    if (opponentpoints == pointscounter){
        winner=1;
        winnertext = "Unentschieden"
    }
    else if (opponentpoints >= pointscounter){
       winnertext= "Du hast verloren"
        winner =2
    }
    else if (opponentpoints <= pointscounter){
        winnertext = "Du hast gewonnen"
        winner =3
    }
    waitforopponent.classList.add("d-none")
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    resultpage.classList.remove('d-none')
    resuttext.innerHTML =
    winnertext+
        ' Du hast ' +
        pointscounter +
        ' Fragen richtig ' +
       " Dein Gegner hat "+
       opponentpoints+
       " Fragen richtig"
    questioncounter = 0
}

function sendfinishflag() {
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    chatcontainer.classList.add("d-none")
    waitforopponent.classList.remove("d-none")
    const message = pointscounter
    const finishmessage = JSON.stringify({ type: 'finish', room, message })
    socket.send(finishmessage)
}

// Funktion wird bei Antwortauswahl ausgeführt
async function antworten(e) {
    //Dieses Ereignis wird an den Mitspieler geschickt und würde dadurch zu einer Endlosschleife führen darum abgesichert mit answered
    if (answered === false) {
        answered = true
        //welcher button wurde gedrückt
        const selectedbutton = e.target
        let correctchoice = await answercheck(
            selectedbutton.dataset.answerid,
            Question.dataset.id
        )
        //Einblenden der Erklärung
        explanationcontainer.classList.remove('d-none')
        //Ausführen wen die Frage richtig ist
        if (correctchoice === 1) {
            selectedbutton.classList.remove('btn-outline-primary')
            selectedbutton.classList.add('btn-success')
            //deaktivieren der Answerbuttons
            Answerbuttons.forEach((button) => {
                button.disabled = true
            }),
                //NextButton aktivieren
                (NextButton.disabled = false)
            pointscounter++
            //Ausführen falls Antwort falsch war
        } else if (correctchoice === 0) {
            selectedbutton.classList.remove('btn-outline-primary')
            selectedbutton.classList.add('btn-danger')
            Answerbuttons.forEach((button) => {
                button.disabled = true
            }),
                (NextButton.disabled = false)
        }
       
    }
    
}

async function answercheck(answerid, questionid) {
    let actionstring =
        'action=answercheck&answerid=' + answerid + '&questionid=' + questionid

    try {
        const response = await fetch(questionserver, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: actionstring,
        })

        const data = await response.json()

        if (data === 1) {
            return 1
        } else {
            return 0
        }
    } catch (error) {
        console.error('Fehler beim Überprüfen der Antwort:', error)
        return  44// Rückgabe eines Standardwerts im Fehlerfall
    }
}


// Hier wird eine Nachricht vom Server ausgewertet
socket.onmessage = (event) => {
    const data = JSON.parse(event.data)
    //  Wenn 2 Spieler im Spiel sind wird ready message gesendet Spiel beginnt
    if (data.type === 'message') {
        if (data.message === 'ready') {
            deletegame()
            startquiz()
        }
        //Gegner hat das Spiel beendet Gegner Punkte in opponentpoints speichern
    } else if (data.type === 'finish') {
        opponentpoints = data.points
        // Beide Spieler haben das Spiel beendet
    } else if (data.type === 'gameover') {
        finish()
    }
}

socket.onclose = (event) => {
    console.log('WebSocket connection closed:', event)
}
//Eventlistener für Chat Send button
sendbutton.addEventListener('click', sendMessage)

//Einlesen des Inhalts des Chatinputfelds und senden an den Server
function sendMessage() {
    const message = messageInput.value
    //Ausgeben in eigenem Verlauf
    chat.innerHTML += 'Du:' + message + '</br>'
    //Mit JSON.stringify wird ein Datenstring erzeugt mit dem Der Server arbeiten kann
    //type zur unterscheidung ob normale nachricht oder anmeldung zu einem raum
    const message1 = JSON.stringify({ type: 'message', room, message })
    socket.send(message1)
}

// Zuweisen des Clients zu einem Raum
function subscribeToRoom(room) {
    // Subscribe to the room
    const subscribeMessage = JSON.stringify({ type: 'subscribe', room })
    socket.send(subscribeMessage)
}
//Funktioniert ähnlich wie die addnewgame Funktion nur das hier deletegame übergeben wird
function deletegame() {
    actionstring = 'action=deleteGame&gamename=' + spielname
    fetch(gameserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: actionstring,
    })
}
//############ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#ALT#AL
// default questions
// let questions = [
//     {
//         explanation:"a",
//         questiontext: 'Was ist 3-2',
//         answers: [
//             { answer: 'eins', correct: true },
//             { answer: 'zwei', correct: false },
//             { answer: 'drei', correct: false },
//             { answer: 'vier', correct: false },
//         ],
//     },
//     {
//         questiontext: 'Was ist 1+1',
//         explanation:"a",
//         answers: [
//             { answer: 'zwei', correct: true },
//             { answer: 'eins', correct: false },
//             { answer: 'drei', correct: false },
//             { answer: 'vier', correct: false },
//         ],
//     },
//     {
//         questiontext: 'Was ist 6/2',
//         explanation:"a",
//         answers: [
//             { answer: 'drei', correct: true },
//             { answer: 'zwei', correct: false },
//             { answer: 'eins', correct: false },
//             { answer: 'vier', correct: false },
//         ],
//     },
// ]

//default questions
