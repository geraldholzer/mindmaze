-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 18. Feb 2024 um 17:55
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `mindmaze`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `antworten`
--

CREATE TABLE `antworten` (
  `AntwortID` int(12) NOT NULL,
  `FragenID` int(12) DEFAULT NULL,
  `Text` varchar(255) DEFAULT NULL,
  `Korrekt` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
  `BenutzerID` int(12) NOT NULL,
  `StudiengangID` int(12) DEFAULT NULL,
  `ZugriffsrechteID` int(12) DEFAULT NULL,
  `Vorname` varchar(50) DEFAULT NULL,
  `Nachname` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Passwort` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `benutzer`
--

INSERT INTO `benutzer` (`BenutzerID`, `StudiengangID`, `ZugriffsrechteID`, `Vorname`, `Nachname`, `Email`, `Passwort`) VALUES
(1, 1, 1, 'Gerald', 'Holzer', 'test@fakemail.com', '1234');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fragen`
--

CREATE TABLE `fragen` (
  `FragenID` int(12) NOT NULL,
  `KursID` int(12) DEFAULT NULL,
  `FragentypID` int(12) DEFAULT NULL,
  `FrageText` varchar(255) DEFAULT NULL,
  `InfoText` varchar(255) DEFAULT NULL,
  `Status` tinyint(1) DEFAULT NULL,
  `Änderungsdatum` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Trigger `fragen`
--
DELIMITER $$
CREATE TRIGGER `update_status_timestamp` BEFORE UPDATE ON `fragen` FOR EACH ROW BEGIN
    IF NEW.Status <> OLD.Status THEN
        SET NEW.Änderungsdatum = CURRENT_TIMESTAMP;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fragentyp`
--

CREATE TABLE `fragentyp` (
  `FragentypID` int(12) NOT NULL,
  `Beschreibung` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurse`
--

CREATE TABLE `kurse` (
  `KursID` int(12) NOT NULL,
  `BenutzerID` int(12) DEFAULT NULL,
  `Beschreibung` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `kurse`
--

INSERT INTO `kurse` (`KursID`, `BenutzerID`, `Beschreibung`) VALUES
(2, 1, 'IT-Recht');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spiele`
--

CREATE TABLE `spiele` (
  `SpieleID` int(12) NOT NULL,
  `SpielmodiID` int(12) DEFAULT NULL,
  `BenutzerIDSpieler1` int(12) DEFAULT NULL,
  `BenutzerIDSpieler2` int(12) DEFAULT NULL,
  `SitzungAktiv` tinyint(1) DEFAULT NULL,
  `BenutzerFragen` tinyint(1) DEFAULT NULL,
  `KursID` int(11) DEFAULT NULL,
  `Spielname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `spiele`
--

INSERT INTO `spiele` (`SpieleID`, `SpielmodiID`, `BenutzerIDSpieler1`, `BenutzerIDSpieler2`, `SitzungAktiv`, `BenutzerFragen`, `KursID`, `Spielname`) VALUES
(6, 1, NULL, NULL, NULL, NULL, 2, 'game'),
(7, 1, NULL, NULL, NULL, NULL, 2, 'meinspiel'),
(12, 1, NULL, NULL, NULL, NULL, 2, 'fddd'),
(13, 1, NULL, NULL, NULL, NULL, 2, 'neues Spiel');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spielmodi`
--

CREATE TABLE `spielmodi` (
  `SpielmodiID` int(12) NOT NULL,
  `Beschreibung` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `spielmodi`
--

INSERT INTO `spielmodi` (`SpielmodiID`, `Beschreibung`) VALUES
(1, 'Kooperativ'),
(2, 'Versus');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `spielrunden`
--

CREATE TABLE `spielrunden` (
  `SpielRundenID` int(12) NOT NULL,
  `SpieleID` int(12) DEFAULT NULL,
  `FragenIDSpieler1` int(12) DEFAULT NULL,
  `FragenIDSpieler2` int(12) DEFAULT NULL,
  `AntwortIDSpieler1` int(12) DEFAULT NULL,
  `AntwortIDSpieler2` int(12) DEFAULT NULL,
  `AntwortTextSpieler1` varchar(255) DEFAULT NULL,
  `AntwortTextSpieler2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `statistik`
--

CREATE TABLE `statistik` (
  `BenutzerIDSieger` int(12) DEFAULT NULL,
  `BenutzerIDVerlierer` int(12) DEFAULT NULL,
  `SpielmodiID` int(12) DEFAULT NULL,
  `SpielDatum` date DEFAULT NULL,
  `StatistikID` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `studiengang`
--

CREATE TABLE `studiengang` (
  `StudiengangID` int(12) NOT NULL,
  `Beschreibung` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `studiengang`
--

INSERT INTO `studiengang` (`StudiengangID`, `Beschreibung`) VALUES
(1, 'Informatik');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `studiengangkurse`
--

CREATE TABLE `studiengangkurse` (
  `StudiengangID` int(12) DEFAULT NULL,
  `KursID` int(12) DEFAULT NULL,
  `StudiengangKursID` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zugriffsrechte`
--

CREATE TABLE `zugriffsrechte` (
  `ZugriffsrechteID` int(12) NOT NULL,
  `Beschreibung` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `zugriffsrechte`
--

INSERT INTO `zugriffsrechte` (`ZugriffsrechteID`, `Beschreibung`) VALUES
(1, 'Administrator');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `antworten`
--
ALTER TABLE `antworten`
  ADD PRIMARY KEY (`AntwortID`),
  ADD KEY `FK_antworten_fragen` (`FragenID`);

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD PRIMARY KEY (`BenutzerID`),
  ADD KEY `FK_Zugriffsrechte` (`ZugriffsrechteID`),
  ADD KEY `FK_benutzer_studiengang` (`StudiengangID`);

--
-- Indizes für die Tabelle `fragen`
--
ALTER TABLE `fragen`
  ADD PRIMARY KEY (`FragenID`),
  ADD KEY `FK_Fragen_Kurs` (`KursID`),
  ADD KEY `FK_fragen_fragentyp` (`FragentypID`);

--
-- Indizes für die Tabelle `fragentyp`
--
ALTER TABLE `fragentyp`
  ADD PRIMARY KEY (`FragentypID`);

--
-- Indizes für die Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD PRIMARY KEY (`KursID`),
  ADD KEY `FK_BenutzerID` (`BenutzerID`);

--
-- Indizes für die Tabelle `spiele`
--
ALTER TABLE `spiele`
  ADD PRIMARY KEY (`SpieleID`),
  ADD KEY `FK_spiele_spielmodi` (`SpielmodiID`),
  ADD KEY `FK_spiele_benutzer1` (`BenutzerIDSpieler1`),
  ADD KEY `FK_spiele_benutzer2` (`BenutzerIDSpieler2`),
  ADD KEY `FKKursID` (`KursID`);

--
-- Indizes für die Tabelle `spielmodi`
--
ALTER TABLE `spielmodi`
  ADD PRIMARY KEY (`SpielmodiID`);

--
-- Indizes für die Tabelle `spielrunden`
--
ALTER TABLE `spielrunden`
  ADD PRIMARY KEY (`SpielRundenID`),
  ADD KEY `FK_spieleid_spielrunden` (`SpieleID`),
  ADD KEY `FK_frageid1_spielrunden` (`FragenIDSpieler1`),
  ADD KEY `FK_frageid2_spielrunden` (`FragenIDSpieler2`),
  ADD KEY `FK_antwortid1_spielrunden` (`AntwortIDSpieler1`),
  ADD KEY `FK_antwortid2_spielrunden` (`AntwortIDSpieler2`);

--
-- Indizes für die Tabelle `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`StatistikID`),
  ADD KEY `FK_benutzer1_benutzer` (`BenutzerIDSieger`),
  ADD KEY `FK_benutzer2_benutzer` (`BenutzerIDVerlierer`),
  ADD KEY `FK_statistik_spielmodi` (`SpielmodiID`);

--
-- Indizes für die Tabelle `studiengang`
--
ALTER TABLE `studiengang`
  ADD PRIMARY KEY (`StudiengangID`);

--
-- Indizes für die Tabelle `studiengangkurse`
--
ALTER TABLE `studiengangkurse`
  ADD PRIMARY KEY (`StudiengangKursID`),
  ADD KEY `FK_KursID` (`KursID`),
  ADD KEY `FK_studiengangkurse_studiengangid` (`StudiengangID`);

--
-- Indizes für die Tabelle `zugriffsrechte`
--
ALTER TABLE `zugriffsrechte`
  ADD PRIMARY KEY (`ZugriffsrechteID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `antworten`
--
ALTER TABLE `antworten`
  MODIFY `AntwortID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `BenutzerID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `fragen`
--
ALTER TABLE `fragen`
  MODIFY `FragenID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `fragentyp`
--
ALTER TABLE `fragentyp`
  MODIFY `FragentypID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `kurse`
--
ALTER TABLE `kurse`
  MODIFY `KursID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `spiele`
--
ALTER TABLE `spiele`
  MODIFY `SpieleID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `spielmodi`
--
ALTER TABLE `spielmodi`
  MODIFY `SpielmodiID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `spielrunden`
--
ALTER TABLE `spielrunden`
  MODIFY `SpielRundenID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `statistik`
--
ALTER TABLE `statistik`
  MODIFY `StatistikID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `studiengang`
--
ALTER TABLE `studiengang`
  MODIFY `StudiengangID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `studiengangkurse`
--
ALTER TABLE `studiengangkurse`
  MODIFY `StudiengangKursID` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `zugriffsrechte`
--
ALTER TABLE `zugriffsrechte`
  MODIFY `ZugriffsrechteID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `antworten`
--
ALTER TABLE `antworten`
  ADD CONSTRAINT `FK_antworten_fragen` FOREIGN KEY (`FragenID`) REFERENCES `fragen` (`FragenID`);

--
-- Constraints der Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  ADD CONSTRAINT `FK_Zugriffsrechte` FOREIGN KEY (`ZugriffsrechteID`) REFERENCES `zugriffsrechte` (`ZugriffsrechteID`),
  ADD CONSTRAINT `FK_benutzer_studiengang` FOREIGN KEY (`StudiengangID`) REFERENCES `studiengang` (`StudiengangID`);

--
-- Constraints der Tabelle `fragen`
--
ALTER TABLE `fragen`
  ADD CONSTRAINT `FK_Fragen_Kurs` FOREIGN KEY (`KursID`) REFERENCES `kurse` (`KursID`),
  ADD CONSTRAINT `FK_fragen_fragentyp` FOREIGN KEY (`FragentypID`) REFERENCES `fragentyp` (`FragentypID`);

--
-- Constraints der Tabelle `kurse`
--
ALTER TABLE `kurse`
  ADD CONSTRAINT `FK_BenutzerID` FOREIGN KEY (`BenutzerID`) REFERENCES `benutzer` (`BenutzerID`);

--
-- Constraints der Tabelle `spiele`
--
ALTER TABLE `spiele`
  ADD CONSTRAINT `FKKursID` FOREIGN KEY (`KursID`) REFERENCES `kurse` (`KursID`),
  ADD CONSTRAINT `FK_spiele_benutzer1` FOREIGN KEY (`BenutzerIDSpieler1`) REFERENCES `benutzer` (`BenutzerID`),
  ADD CONSTRAINT `FK_spiele_benutzer2` FOREIGN KEY (`BenutzerIDSpieler2`) REFERENCES `benutzer` (`BenutzerID`),
  ADD CONSTRAINT `FK_spiele_spielmodi` FOREIGN KEY (`SpielmodiID`) REFERENCES `spielmodi` (`SpielmodiID`);

--
-- Constraints der Tabelle `spielrunden`
--
ALTER TABLE `spielrunden`
  ADD CONSTRAINT `FK_antwortid1_spielrunden` FOREIGN KEY (`AntwortIDSpieler1`) REFERENCES `antworten` (`AntwortID`),
  ADD CONSTRAINT `FK_antwortid2_spielrunden` FOREIGN KEY (`AntwortIDSpieler2`) REFERENCES `antworten` (`AntwortID`),
  ADD CONSTRAINT `FK_frageid1_spielrunden` FOREIGN KEY (`FragenIDSpieler1`) REFERENCES `fragen` (`FragenID`),
  ADD CONSTRAINT `FK_frageid2_spielrunden` FOREIGN KEY (`FragenIDSpieler2`) REFERENCES `fragen` (`FragenID`),
  ADD CONSTRAINT `FK_spieleid_spielrunden` FOREIGN KEY (`SpieleID`) REFERENCES `spiele` (`SpieleID`);

--
-- Constraints der Tabelle `statistik`
--
ALTER TABLE `statistik`
  ADD CONSTRAINT `FK_benutzer1_benutzer` FOREIGN KEY (`BenutzerIDSieger`) REFERENCES `benutzer` (`BenutzerID`),
  ADD CONSTRAINT `FK_benutzer2_benutzer` FOREIGN KEY (`BenutzerIDVerlierer`) REFERENCES `benutzer` (`BenutzerID`),
  ADD CONSTRAINT `FK_statistik_spielmodi` FOREIGN KEY (`SpielmodiID`) REFERENCES `spielmodi` (`SpielmodiID`),
  ADD CONSTRAINT `FK_statistiksieger_spiele` FOREIGN KEY (`BenutzerIDSieger`) REFERENCES `spiele` (`BenutzerIDSpieler1`);

--
-- Constraints der Tabelle `studiengangkurse`
--
ALTER TABLE `studiengangkurse`
  ADD CONSTRAINT `FK_KursID` FOREIGN KEY (`KursID`) REFERENCES `kurse` (`KursID`),
  ADD CONSTRAINT `FK_StudiengangID` FOREIGN KEY (`StudiengangID`) REFERENCES `studiengang` (`StudiengangID`),
  ADD CONSTRAINT `FK_studiengangkurse_studiengangid` FOREIGN KEY (`StudiengangID`) REFERENCES `studiengang` (`StudiengangID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
