//Elemente aus dem DOM holen
let meldebutton = document.getElementById('Meldebutton')
let meldecontainer = document.getElementById('Meldecontainer')
let meldungabsendenbutton = document.getElementById('Meldungabsendenbutton')
let meldungabbrechenbutton = document.getElementById('Meldungabbrechenbutton')
let questioncounter = 0 //zähler für die aktuelle Frage
let pointscounter = 0 //Zähler für die erreichten Punkte
let AnswerButton1 = document.getElementById('Answer1') //Antwortbutton1
let AnswerButton2 = document.getElementById('Answer2') //Antwortbutton2
let AnswerButton3 = document.getElementById('Answer3') //Antwortbutton3
let AnswerButton4 = document.getElementById('Answer4') //Antwortbutton4
let NextButton = document.getElementById('Next') //Nextbutton
let BeendenButton = document.getElementById('Beenden') //Beendenbutton
let Question = document.getElementById('question') //Frage text
let StartButton = document.getElementById('Start') //Startbutton
let answercontainer = document.getElementById('answercontainer') //COntainer (div) in dem sich die answerbuttons befinden
let resultpage = document.getElementById('result') //wird eingeblendet am schluss als ergebnis
let resuttext = document.getElementById('resulttext') // Zeigt am Schluss etwa ihr habt "3/3" Fragen richtig beantwortet
let explanation = document.getElementById('explanation') //Erklärungstext zu jeder Frage
let explanationcontainer = document.getElementById('explanationcontainer') //Container(div)in dem sich die Erklärung befindet
// let chatcontainer = document.getElementById('chatcontainer') //Container (div) für den chat
// let messageInput = document.getElementById('messageInput') //Inputfeld für die chatnachricht
// let chat = document.getElementById('chat') //chat div hier wird der chat verlauf angezeigt
// let sendbutton = document.getElementById('sendbutton') //button zum absenden einer Chatnachricht
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
//let gameserver="http://13.53.246.106/../server/game-server.php" //gameserver ip von aws server
let gameserver = '../server/game-server.php' // lokaler gameserver
//let questionserver= "http://13.53.246.106/../server/question-server.php"//questionserver ip von aws server
let questionserver = '../server/question-server.php' // lokaler question server
//let websocketserver="ws://13.53.246.106:8081"//websocket server auf aws server
let websocketserver = 'ws://127.0.0.1:8081' // lokaler websocketserver
let opponentpoints = 0
let questions = null
let spielname=null
let fragenzahl=null
let kurs=null
let modus=null
let opponent=null

async function getlocalstorage(){
    spielname = localStorage.getItem('spielname') //wird zum löschen des spiels gebraucht
    room = localStorage.getItem('gamenameübergabe')
    fragenzahl = localStorage.getItem("fragenzahl");//Auslesen der Fragenzahl
    kurs = localStorage.getItem("kurs");// Auslesen des Kurses
    modus = localStorage.getItem("modus");// Auslesen des Kurses   
}

//Buttons in Array verwalten so kann man foreach schleifen nutzen
const Answerbuttons = [
    AnswerButton1,
    AnswerButton2,
    AnswerButton3,
    AnswerButton4,
]
console.log('fragen in versus' + fragenzahl)
// Websocket für Multiplayer//////////////////////////////////////////////////////////////////////////////////
//Verbindung zu Websocketserver erstellen der PORT 8081 weil ich sonst einen Konflikt mit XAMPP hatte  ip adresse von aws
const socket = new WebSocket(websocketserver)

socket.onopen = (event) => {
    console.log('WebSocket connection opened:', event)
    joingame()
}

//Mit dieser function wird der benutzer zum entsprechenden raum hinzugefügt mit subsribeToRoom und Warteseite eingeblendet
async function joingame() {
    await getlocalstorage();
   waitforopponent.classList.remove('d-none')
    subscribeToRoom(room, fragenzahl, kurs,modus,benutzername)
    joingamecontainer.classList.add('d-none')
    joinbutton.classList.add('d-none')
    
     
}    

//Eventlistener für Beendenbutton
BeendenButton.addEventListener('click', sendinterruptflag)
//Eventlistener für next button
NextButton.addEventListener('click', next)
//Eventlistener für Startbutton
StartButton.addEventListener('click', startquiz)

//Hier wird zuerst die laden funktion aufgerufen und anschließend die entsprechenden buttons ein/aus geblendet
function startquiz() {
    zuweisen()
    StartButton.classList.add('d-none')
    Question.classList.remove('d-none')
    answercontainer.classList.remove('d-none')
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
    mixedanswers = questions[Object.keys(questions)[questioncounter]].answers
    for (let i = 0; i < 4; i++) {
        explanation.innerHTML =
            questions[Object.keys(questions)[questioncounter]].explanation
        Question.innerHTML =
            questions[Object.keys(questions)[questioncounter]].questiontext
        Question.dataset.id =
            questions[Object.keys(questions)[questioncounter]].questionid
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
    meldebutton.classList.remove('d-none')
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
    let winner = 1
    let winnertext = ''
    if (opponentpoints == pointscounter) {
        winner = 1
        winnertext = 'Unentschieden'
    } else if (opponentpoints >= pointscounter) {
        winnertext = 'Du hast verloren'
        winner = 2
    } else if (opponentpoints <= pointscounter) {
        winnertext = 'Du hast gewonnen'
        winner = 3
    }
    waitforopponent.classList.add('d-none')
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    resultpage.classList.remove('d-none')
    meldebutton.classList.add('d-none')
    resuttext.innerHTML =
        winnertext +
        ' Du hast ' +
        pointscounter +
        ' Fragen richtig ' +
        ' Dein Gegner hat ' +
        opponentpoints +
        ' Fragen richtig'
    questioncounter = 0
}

function sendfinishflag() {
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    waitforopponent.classList.remove('d-none')
    const message = pointscounter
    const finishmessage = JSON.stringify({ type: 'finish', room, message })
    socket.send(finishmessage)
}

function sendinterruptflag() {
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    chatcontainer.classList.add('d-none')
    resultpage.classList.remove('d-none')
    meldebutton.classList.add('d-none')
    resuttext.innerHTML =
        'Du hast aufgegeben und damit automatisch das Spiel verloren'
    const interruptmessage = JSON.stringify({
        type: 'interrupt',
        room,
    })
    socket.send(interruptmessage)
}
function interruptetbyopponent() {
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    chatcontainer.classList.add('d-none')
    resultpage.classList.remove('d-none')
    meldebutton.classList.add('d-none')
    resuttext.innerHTML =
        'Dein Gegner hat aufgegeben du hast automatisch gewonnen'
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
        return 44 // Rückgabe eines Standardwerts im Fehlerfall
    }
}

// Hier wird eine Nachricht vom Server ausgewertet
socket.onmessage = (event) => {
    const data = JSON.parse(event.data)
    //  Wenn 2 Spieler im Spiel sind wird ready message gesendet Spiel beginnt
    if (data.type === 'message') {
        if (data.message === 'ready') {
            deletegame()
            opponent=data.opponent
        }
        //Gegner hat das Spiel beendet Gegner Punkte in opponentpoints speichern
    } else if (data.type === 'finish') {
        opponentpoints = data.points
        // Beide Spieler haben das Spiel beendet
    } else if (data.type === 'gameover') {
        finish()
    } else if (data.type === 'questions') {
        console.log(typeof data)
        questions = data
        questions = JSON.parse(data.questions)
        console.log(questions)
        console.log(questions[Object.keys(questions)[1]])
        startquiz()
    } else if (data.type === 'interrupt') {
        interruptetbyopponent()
    }
}

socket.onclose = (event) => {
    console.log('WebSocket connection closed:', event)
}


//Bei Klick auf Meldebutton meldecontainer einblenden
meldebutton.addEventListener('click', meldecontainereinblenden)
// Hier wird der meldecontainer eingeblendet und der frage melden button ausgeblendet
// dem abrechenbutton wird die funktionalität zum ausblenden des meldecontainers hinzugefügt

function meldecontainereinblenden() {
    meldebutton.classList.add('d-none')
    meldecontainer.classList.remove('d-none')
    meldungabsendenbutton.addEventListener('click', meldungsenden)
    meldungabbrechenbutton.addEventListener('click', () => {
        meldecontainer.classList.add('d-none')
        meldebutton.classList.remove('d-none')
    })
}
//Abfrage des Fragenstatus
async function statuscheck() {
    const response = await fetch(questionserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=statuscheck&' + 'questionid=' + Question.dataset.id,
    })
    const data = await response.text()
    return data // Assuming the response is the status value
}
//Absenden der Meldung
async function meldungsenden() {
    let status = await statuscheck()
    console.log('status' + status)
    if (status === '1') {
        meldetext = document.getElementById('Meldetext').value
        fetch(questionserver, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            //diese action wird im server abgefragt
            body:
                'action=fragemelden&' +
                'questionid=' +
                Question.dataset.id +
                '&meldetext=' +
                meldetext,
        }).then(() => {
            meldecontainer.classList.add('d-none')
            meldebutton.classList.add('d-none')
            document
                .getElementById('Meldunggesendet')
                .classList.remove('d-none')
            document
                .getElementById('meldunggesendetclose')
                .addEventListener('click', () => {
                    document
                        .getElementById('Meldunggesendet')
                        .classList.add('d-none')
                })
        })
    } else {
        meldecontainer.classList.add('d-none')
        document
            .getElementById('Meldungnichtgesendet')
            .classList.remove('d-none')
            document.getElementById("meldungnichtgesendetclose").addEventListener("click",()=>{
            document.getElementById("Meldungnichtgesendet").classList.add("d-none")}) 
    }
}

// //Eventlistener für Chat Send button
// sendbutton.addEventListener('click', sendMessage)

// //Einlesen des Inhalts des Chatinputfelds und senden an den Server
// function sendMessage() {
//     const message = messageInput.value
//     //Ausgeben in eigenem Verlauf
//     chat.innerHTML += 'Du:' + message + '</br>'
//     //Mit JSON.stringify wird ein Datenstring erzeugt mit dem Der Server arbeiten kann
//     //type zur unterscheidung ob normale nachricht oder anmeldung zu einem raum
//     const message1 = JSON.stringify({ type: 'message', room, message })
//     socket.send(message1)
// }

// Zuweisen des Clients zu einem Raum
function subscribeToRoom(room, fragenzahl, kurs,modus,benutzername) {
    // Subscribe to the room
    const subscribeMessage = JSON.stringify({
        type: 'subscribe',
        room,
        fragenzahl,
        kurs,
        modus,
        benutzername
    })
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
//Array mit den Fragen jede Frage hat ein Array mit Antworten mit attribut correct für die richtige Antwort
// Wird mit fetch von PHP geholt
// function laden() {
//     fetch(questionserver, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         //diese action wird im server abgefragt
//         body: 'action=fragenladen&'+"fragenzahl="+ fragenzahl,
//     })
//         .then((response) => {
//             if (!response.ok) {
//                 throw new Error(
//                     `Network response was not ok: ${response.statusText}`
//                 )
//             }
//             return response.json() // JSON-Daten aus der Antwort extrahieren
//         })
//         .then((data) => {
//             //Array daten zuweisen
//             questions = data;
//             zuweisen()
//         })
//         .catch((error) => {
//             console.error('Fehler beim Abrufen der Daten:', error)
//         })
// }
