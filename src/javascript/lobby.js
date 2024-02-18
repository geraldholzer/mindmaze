//Elemente aus dem DOM holen
let joinbutton = document.getElementById('Joingame') //Dient zum aufrufen der Seite mit den offenen Spielen
let newgamebutton = document.getElementById('newgamebutton') //Mit diesem Button kann man ein neues Spiel erstellen
let joingamecontainer = document.getElementById('joingamecontainer') //Container der Seite mit offenen spielen
let gamelist = document.getElementById('gamelist') //Liste mit den offenen spielen
let waitforopponent = document.getElementById('wait') //Zeigt Warte auf Gegner
let room = '' // die Spielsitzungen werden als WebsocketRäume umgesetzt damit immer nur 2 Spieler gleichzeitig spielen können
let modus = '' //Spielmodus
let kurs = '' //Gewählter Kurs
let gamesarray = [] // Hier werden die offenen Spiele die aus der Datenbank geholt wurden gespeichert
let gamenameInput = document.getElementById('gamenameInput') //Eingabefeld für den Spielnamen
//let gameserver="http://13.49.243.225/game-server.php" //gameserver ip von aws server
let websocketserver = 'ws://127.0.0.1:8081' // lokaler websocketserver
//let gameserver="http://13.49.243.225/game-server.php" //gameserver ip von aws server
let gameserver = '../server/game-server.php' // lokaler gameserver

//Seite für das erstellen oder beitreten zu einem spiel anzeigen
joinbutton.addEventListener('click', joingamepage)
//Ausblenden des Spielbeitreten buttons einblenden der Seite mit den Spielen loadGames wird aufgerufen zum laden aus der DB
function joingamepage() {
    joingamecontainer.classList.remove('d-none')
    joinbutton.classList.add('d-none')
    loadGames()
    loadModusDropdwon()
    loadKursDropdown()
}
//Funktion zum holen der verfügbaren kurse und laden in das Dropdownfeld
function loadKursDropdown() {
    let kursdropdown = document.getElementById('kursliste')
    while (kursdropdown.firstChild) {
        kursdropdown.removeChild(kursdropdown.lastChild)
    }
    fetch(gameserver, {
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

//Funktion zum holen der verfügbaren Spielmodi und laden in das Dropdownfeld
function loadModusDropdwon() {
    let modusdropdown = document.getElementById('spielmodusliste')
    while (modusdropdown.firstChild) {
        modusdropdown.removeChild(modusdropdown.lastChild)
    }
    fetch(gameserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        //diese action wird im server abgefragt
        body: 'action=getModusDropdown',
    })
        .then((response) => response.json())
        .then((data) => {
            data.forEach((modus) => {
                var li = document.createElement('li')
                var a = document.createElement('a')
                a.setAttribute('class', 'dropdown-item')
                a.setAttribute('href', '#')
                a.textContent = modus

                // Link zum Listenelement hinzufügen
                li.appendChild(a)
                modusdropdown.appendChild(li)
            })
        })
}
//Funktion zum laden der offenen Spiele  aus der Datenbank
function loadGames() {
    //leeren der gamelist
    while (gamelist.firstChild) {
        gamelist.removeChild(gamelist.lastChild)
    }
    //Mit fetch API wird aus game-server.php die gamelist geholt
    fetch(gameserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        //diese action wird im server abgefragt
        body: 'action=getGameList',
    }) //empfangene Daten in gamesarray speichern
        .then((response) => response.json())
        .then((data) => {
            gamesarray = data
            //Für jedes game im gamesarray wird ein button erstellt
            gamesarray.forEach((game) => {
                let tr = document.createElement('tr')
                let name = document.createElement('td')
                name.innerHTML = game.name
                let modus = document.createElement('td')
                modus.innerHTML = game.modus
                let kurs = document.createElement('td')
                kurs.innerHTML = game.kurs
                let button = document.createElement('button')
                button.innerHTML = 'Beitreten'
                übergabestring = game.name + game.modus + game.kurs
                button.addEventListener('click', function () {
                    joingame(übergabestring, game.modus,game.name)
                })
                tr.appendChild(name)
                tr.appendChild(modus)
                tr.appendChild(kurs)
                tr.appendChild(button)
                document.getElementById('gamelist').appendChild(tr)
            })
        })

        .catch((error) => {
            console.error('Error:', error)
        })
}

//neues spiel erstellen
newgamebutton.addEventListener('click', addnewgame)

//Hier wird wieder die fetch API genutzt
function addnewgame() {
    let game = gamenameInput.value
    let vorhanden = gamesarray.find(function (spiel) {
        return game == spiel.name
    })
    if (vorhanden) {
        alert('Spiel bereits vorhanden neuen Namen wählen')
    } else {
        //Dieser String wird übergeben action und gamename werden im Server abgefragt anschließend wird mit loadGames die liste neu geladen
        ;(actionstring =
            'action=addGame&gamename=' +
            encodeURIComponent(game) +
            '&modus=' +
            encodeURIComponent(modus) +
            '&kurs=' +
            encodeURIComponent(kurs)),
            fetch(gameserver, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: actionstring,
            }).then(loadGames)
    }
}
//Funktioniert ähnlich wie die addnewgame Funktion nur das hier deletegame übergeben wird
function deletegame() {
    let game = room //aktuell ausgewähltes Spiel verwenden
    actionstring = 'action=deleteGame&gamename=' + game
    fetch(gameserver, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: actionstring,
    }).then(loadGames)
}

//Mit dieser function wird der Benutzer zum entsprechenden raum hinzugefügt mit subsribeToRoom und Warteseite eingeblendet
function joingame(übergabestring, modus,spielname) {
    localStorage.setItem('gamenameübergabe', übergabestring)
    localStorage.setItem('spielname', spielname)

    if (modus === 'Kooperativ') {
        window.location.href = 'co-op.html'
    } else if (modus === 'Versus') {
        window.location.href = 'versus.html'
    } else {
        alert('ok')
    }
}
//Wert auslesen aus dropdownfeld und in Variable speichern
document
    .getElementById('spielmodusliste')
    .addEventListener('click', function (event) {
        if (event.target.classList.contains('dropdown-item')) {
            var selectedValue = event.target.textContent.trim() // Wert des ausgewählten Elements
            document.getElementById('spielmodusDropdownButton').innerHTML =
                selectedValue
            modus = selectedValue
        }
    })
//Wert auslesen aus dropdownfeld und in Variable speichern
document
    .getElementById('kursliste')
    .addEventListener('click', function (event) {
        if (event.target.classList.contains('dropdown-item')) {
            var selectedValue = event.target.textContent.trim() // Wert des ausgewählten Elements
            document.getElementById('kursDropdownButton').innerHTML =
                selectedValue
            kurs = selectedValue
        }
    })
