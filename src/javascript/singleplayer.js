//Elemente aus dem DOM holen
let questioncounter = 0
let pointscounter = 0
let fragenzahl =3;
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
//let questionserver= "http://13.53.246.106/../server/question-server.php"//questionserver ip von aws server
let questionserver= "../server/question-server.php"// lokaler question server
//Buttons in Array verwalten
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
}
// bei drücken des Next buttons wird die funktion zuweisen aufgerufen auser die fragen sind fertig dan finish
function next() {
    if (questioncounter >= fragenzahl) {
        finish()
    } else {
        zuweisen()
    }
}

// Funktion zum mixen der Antworten
function shuffleFisherYates(array) {
    let i = array.length
    while (i--) {
        const ri = Math.floor(Math.random() * i)
        ;[array[i], array[ri]] = [array[ri], array[i]]
    }
    return array
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
    explanationcontainer.classList.add('d-none')
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
