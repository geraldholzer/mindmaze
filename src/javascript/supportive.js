//Elemente aus dem DOM holen
let meldebutton = document.getElementById('Meldebutton')
let meldecontainer = document.getElementById('Meldecontainer')
let meldungabsendenbutton = document.getElementById('Meldungabsendenbutton')
let meldungabbrechenbutton = document.getElementById('Meldungabbrechenbutton')
let questioncounter = 0 //zähler für die aktuelle Frage
let NextButton = document.getElementById('Next') //Nextbutton
let BeendenButton = document.getElementById('Beenden') //Beendenbutton
let Question = document.getElementById('question') //Frage text
let StartButton = document.getElementById('Start') //Startbutton
let resultpage = document.getElementById('result') //wird eingeblendet am schluss als ergebnis
let resuttext = document.getElementById('resulttext') // Zeigt am Schluss etwa ihr habt "3/3" Fragen richtig beantwortet
let explanation = document.getElementById('explanation') //Erklärungstext zu jeder Frage
let explanationcontainer = document.getElementById('explanationcontainer') //Container(div)in dem sich die Erklärung befindet
let chatcontainer = document.getElementById('chatcontainer') //Container (div) für den chat
let messageInput = document.getElementById('messageInput') //Inputfeld für die chatnachricht
let chat = document.getElementById('chat') //chat div hier wird der chat verlauf angezeigt
let sendbutton = document.getElementById('sendbutton') //button zum absenden einer Chatnachricht
let joinbutton = document.getElementById('Joingame') //Dient zum aufrufen der Seite mit den offenen Spielen
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
//let websocketserver="ws://13.53.246.106:8081" //websocket server auf aws server
let websocketserver = 'ws://127.0.0.1:8081' // lokaler websocketserver
let spielname=null
let fragenzahl=null
let kurs=null
let modus =null
let opponent=null

async function getlocalstorage(){
    spielname = localStorage.getItem('spielname') //wird zum löschen des spiels gebraucht
    room = localStorage.getItem('gamenameübergabe')
    fragenzahl = localStorage.getItem("fragenzahl");//Auslesen der Fragenzahl
    kurs = localStorage.getItem("kurs");// Auslesen des Kurses
    modus = localStorage.getItem("modus");// Auslesen des Kurses   
}
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
    subscribeToRoom(room,fragenzahl,kurs,modus,benutzername)
    joingamecontainer.classList.add('d-none')
    joinbutton.classList.add('d-none')
    BeendenButton.classList.add('d-none')
    NextButton.classList.add('d-none')
    waitforopponent.classList.remove('d-none')
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
    waitforopponent.classList.add('d-none')
    chatcontainer.classList.remove('d-none')
    NextButton.classList.remove("d-none")
    BeendenButton.classList.remove("d-none")
}
// bei drücken des Next buttons wird die funktion zuweisen aufgerufen  oder die Fragen sind fertig -> finish
function next() {
    if (questioncounter >= fragenzahl) {
        finish()
    } else {
        zuweisen()
    }
}

//Funktion zum Zuweisen der Fragen und Antworten zu den  Buttons
function zuweisen() {
    for (let i = 0; i < 4; i++) {
        explanation.innerHTML = questions[Object.keys(questions)[questioncounter]].explanation
        Question.innerHTML = questions[Object.keys(questions)[questioncounter]].questiontext
        Question.dataset.id = questions[Object.keys(questions)[questioncounter]].questionid
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
}

// Ausblenden des answercontainers und einblenden des Ergebnistexts
function finish() {
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    resultpage.classList.remove('d-none')
    meldebutton.classList.add('d-none')
    NextButton.classList.add('d-none')
    chatcontainer.classList.add('d-none')
    BeendenButton.classList.add("d-none")
    meldebutton.classList.add('d-none')
    resuttext.innerHTML =
    "Das Spiel ist Beendet"
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
    'Du hast aufgegeben und damit das Spiel beendet'
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
    'Dein Mitspieler hat aufgegeben das Spiel ist beendet'
}
//Rücksetzen button pressed sonst Endlosschleife
let buttonpressed = false
// Hier wird eine Nachricht vom Server ausgewertet
socket.onmessage = (event) => {
    const data = JSON.parse(event.data)
    //Nextfunktion aufrufen wenn Mitspieler Next gedrückt hat
    if (data.message == 'nextbuttonclick') {
        next()
    }
        //Wenn zwei Spieler verbunden sind wird das Spiel gestartet der Server sendet hierzu "ready"
     else if (data.message == 'ready') {
        //deletegame()
        opponent=data.opponent
    } else if (data.type === 'questions') {        
        console.log(typeof data)
        questions = data
        questions = JSON.parse(data.questions)
        console.log("Fragen: "+questions)
        console.log(questions[Object.keys(questions)[1]])
    
        startquiz();
    } else if (data.type === 'interrupt') {
        interruptetbyopponent()
    }else {
        var newMessage = document.createElement("div");
        newMessage.textContent = opponent+":" + data.message;
        newMessage.style.borderRadius = "10px";
        newMessage.style.padding = "5px"
        newMessage.style.margin= "2px"
        newMessage.style.backgroundColor="lightblue"
        newMessage.style.boxShadow = "2px 2px 5px rgba(0, 0, 0, 0.6)"; 
        newMessage.classList.add("col-5")
        var padding=document.createElement("div");
        padding.classList.add("col-6")
        chat.appendChild(padding);
        chat.appendChild(newMessage);
        // chat.innerHTML += data.message + '</br>'
    }


}


socket.onclose = (event) => {
    console.log('WebSocket connection closed:', event)
}
//Eventlistener für Chat Send button
sendbutton.addEventListener('click', sendMessage)
messageInput.addEventListener("keyup",function(e){
    if (e.key === 'Enter') {
        sendMessage();
    }
})
//Einlesen des Inhalts des Chatinputfelds und senden an den Server
function sendMessage() {
    const message = messageInput.value
    messageInput.value="";
    var newMessage = document.createElement("div");
    newMessage.textContent = 'Du: ' + message;
    newMessage.style.borderRadius = "10px";
    newMessage.style.padding = "5px"
    newMessage.style.margin= "2px"
    newMessage.style.boxShadow = "2px 2px 5px rgba(0, 0, 0, 0.6)"; 
    newMessage.style.backgroundColor="#AB82FF"
    newMessage.style.color = 'white';
    newMessage.classList.add("col-5")
    var padding=document.createElement("div");
    var padding2=document.createElement("div");
    padding.classList.add("col-1") 
    chat.appendChild(padding);
    chat.appendChild(newMessage);
    padding2.classList.add("col-5") 
    chat.appendChild(padding2);
    //chat.innerHTML += 'Du:' + message + '</br>'
    //Mit JSON.stringify wird ein Datenstring erzeugt mit dem Der Server arbeiten kann
    //type zur unterscheidung ob normale nachricht oder anmeldung zu einem raum
    const message1 = JSON.stringify({ type: 'message', room, message })
    socket.send(message1)
}

// Bei drücken eines Buttons wird eine Nachricht an den Mitspieler gesendet mit der dann das click event ausgelöst wird
NextButton.addEventListener('click', function () {
    const message = 'nextbuttonclick'
    const message1 = JSON.stringify({ type: 'message', room, message })
    socket.send(message1)
})


// Zuweisen des Clients zu einem Raum
function subscribeToRoom(room,fragenzahl,kurs,modus,benutzername) {
    // Subscribe to the room
    const subscribeMessage = JSON.stringify({ type: 'subscribe', room ,fragenzahl,kurs,modus,benutzername})
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
    return data 
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
                //         body: 'action=fragenladen',
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
                //             questions = data
                //         })
                //         .then(zuweisen)
                //         .catch((error) => {
                //             console.error('Fehler beim Abrufen der Daten:', error)
                //         })
                // 