//Elemente aus dem DOM holen
let meldebutton = document.getElementById('Meldebutton')
let meldecontainer = document.getElementById('Meldecontainer')
let meldungabsendenbutton = document.getElementById('Meldungabsendenbutton')
let meldungabbrechenbutton = document.getElementById('Meldungabbrechenbutton')
let questioncounter = 0
let pointscounter = 0
let fragenzahl = 3
let AnswerButton1 = document.getElementById('Answer1')
let AnswerButton2 = document.getElementById('Answer2')
let AnswerButton3 = document.getElementById('Answer3')
let AnswerButton4 = document.getElementById('Answer4')
let NextButton = document.getElementById('Next')
let Question = document.getElementById('question')
let StartButton = document.getElementById('Start')
let answercontainer = document.getElementById('answercontainer')
let resultpage = document.getElementById('result')
let resuttext = document.getElementById('resulttext')
let explanation = document.getElementById('explanation')
let explanationcontainer = document.getElementById('explanationcontainer')
let kurs = ''
let kursdropdown = document.getElementById('kursdropdown')
let fragendropdown = document.getElementById('fragendropdown')
//let questionserver= "http://13.53.246.106/../server/question-server.php"//questionserver ip von aws server
let questionserver = '../server/question-server.php' // lokaler question server
//let gameserver="http://13.53.246.106/../server/game-server.php" //gameserver ip von aws server
let gameserver = '../server/game-server.php' // lokaler gameserver

//Buttons in Array verwalten
const Answerbuttons = [
    AnswerButton1,
    AnswerButton2,
    AnswerButton3,
    AnswerButton4,
]

// Accessing BenutzerID from the PHP session
console.log("BenutzerID from JavaScript file:", BenutzerID);

//Funktion zum holen der verfügbaren kurse und laden in das Dropdownfeld
async function loadKursDropdown() {
    let kursdropdown = document.getElementById('kursliste')
    while (kursdropdown.firstChild) {
        kursdropdown.removeChild(kursdropdown.lastChild)
    }
  await  fetch(gameserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        //diese action wird im server abgefragt
        body: 'action=getKursDropdown',
    })
        .then((response) => response.json())
        .then((data) => {
            data.forEach((kurs) => {
                var li = document.createElement('li')
                var a = document.createElement('a')
                a.setAttribute('class', 'dropdown-item')
                a.setAttribute('href', '#')
                a.textContent = kurs

                // Link zum Listenelement hinzufügen
                li.appendChild(a)
                kursdropdown.appendChild(li)
            })
        })
}

loadKursDropdown()

//Wert auslesen aus dropdownfeld und in Variable speichern
document
    .getElementById('kursliste')
    .addEventListener('click', async function (event) {
       await loadKursDropdown()
        if (event.target.classList.contains('dropdown-item')) {
            var selectedValue = event.target.textContent.trim() // Wert des ausgewählten Elements
            document.getElementById('kursDropdownButton').innerHTML =
                selectedValue
            kurs = selectedValue
        }
    })
//Wert auslesen aus dropdownfeld und in Variable speichern
document
    .getElementById('fragenzahl')
    .addEventListener('click', function (event) {
        if (event.target.classList.contains('dropdown-item')) {
            var selectedValue = event.target.textContent.trim() // Wert des ausgewählten Elements
            document.getElementById('fragenDropdownButton').innerHTML =
                selectedValue
            fragenzahl = event.target.dataset.fragen
        }
    })

//Array mit den Fragen jede Frage hat ein Array mit Antworten mit attribut correct für die richtige Antwort
// Wird mit fetch von PHP geholt
function laden() {
    fetch(questionserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        //diese action wird im server abgefragt
        body:
            'action=fragenladen&' +
            'fragenzahl=' +
            fragenzahl +
            '&kurs=' +
            kurs,
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
    meldebutton.classList.remove('d-none')
    answercontainer.classList.remove('d-none')
    kursdropdown.classList.add('d-none')
    fragendropdown.classList.add('d-none')
}
// bei drücken des Next buttons wird die funktion zuweisen aufgerufen auser die fragen sind fertig dan finish
function next() {
    if (questioncounter >= fragenzahl) {
        finish()
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
    explanationcontainer.classList.add('d-none')
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
    explanationcontainer.classList.add('d-none')
    StartButton.classList.add('d-none')
    Question.classList.add('d-none')
    answercontainer.classList.add('d-none')
    answercontainer.classList.add('d-none')
    meldebutton.classList.add('d-none')
    resultpage.classList.remove('d-none')
    resuttext.innerHTML =
        'Du hast ' +
        pointscounter +
        ' von ' +
        questioncounter +
        ' Fragen richtig beantwortet'
    questioncounter = 0
}

// Funktion wird bei Antwortauswahl ausgeführt
async function antworten(e) {
    //welcher button wurde gedrückt
    const selectedbutton = e.target
    //Aufruf der answercheck methode erst wenn eine Rückmeldung kommt geht es in der funktion weiter
    let correctchoice = await answercheck(
        selectedbutton.dataset.answerid,
        Question.dataset.id
    )
    explanationcontainer.classList.remove('d-none')

    if (correctchoice === 1) {
        selectedbutton.classList.remove('btn-outline-primary')
        selectedbutton.classList.add('btn-success')

        Answerbuttons.forEach((button) => {
            button.disabled = true
        }),
            //NextButton aktivieren
            (NextButton.disabled = false)
        pointscounter++
    } else if (correctchoice === 0) {
        selectedbutton.classList.remove('btn-outline-primary')
        selectedbutton.classList.add('btn-danger')
        Answerbuttons.forEach((button) => {
            button.disabled = true
        }),
            (NextButton.disabled = false)
    }
}
// Funktion zum Auswerten ob die gegebene Antwort stimmt
// Es ist eine async function weil auf die auswertung der antwort gewartet wird sonst response immer schon ausgegeben bevor ausgewertet wurde
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
        //Server gibt 1 zurück falls die Antwort richtig war 0 falls falsch
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
