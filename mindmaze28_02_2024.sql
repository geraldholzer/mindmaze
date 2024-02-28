-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 28. Feb 2024 um 13:25
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

--
-- Daten für Tabelle `antworten`
--

INSERT INTO `antworten` (`AntwortID`, `FragenID`, `Text`, `Korrekt`) VALUES
(1, 0, '5', 1),
(2, 0, '3', 0),
(3, 0, '2', 0),
(4, 0, '8', 0),
(5, 1, '13', 0),
(6, 1, '5', 1),
(7, 1, '3', 0),
(8, 1, '12', 0),
(9, 5, '\r\nunverzüglich', 1),
(10, 5, 'nach einer Woche', 0),
(11, 5, 'niemals', 0),
(12, 5, 'nach einem Monat', 0),
(13, 2, '\r\nNur dann, wenn schuldhaft gehandelt wird und keine Rechtfertigung vorliegt.', 1),
(14, 2, 'nein', 0),
(15, 2, 'ja', 0),
(16, 2, '\r\nNur dann, wenn fahrlässig gehandelt wird.', 0),
(17, 6, '§ 631 BGB', 0),
(18, 6, '§ 611 BGB', 0),
(19, 6, '§ 433 BGB', 1),
(20, 6, '§ 13 BGB', 0),
(21, 7, 'Nacherfüllung', 1),
(22, 7, 'Minderung', 0),
(23, 7, 'Schadensersatz', 0),
(24, 7, 'Rücktritt', 0),
(25, 8, 'Schenkung', 0),
(26, 8, 'Mietvertrag', 0),
(27, 8, 'Kaufvertrag', 0),
(28, 8, 'Werkvertrag', 1),
(29, 9, 'bei Kaufverträgen', 0),
(30, 9, 'bei allen Verträgen über Waren und/oder Dienstleistungen', 1),
(31, 9, 'nie', 0),
(32, 9, 'bei Mietverträgen', 0),
(33, 10, 'bei wettbewerbsbeschränkenden Verhaltensweisen von Unternehmen', 1),
(34, 10, 'bei Gewährleistungsansprüchen von Kunden', 0),
(35, 10, 'bei unzulässigen AGB-Klauseln', 1),
(36, 10, 'bei unzulässigen Werbemaßnahmen', 0),
(37, 11, 'Vorgabe de Hardware für Nutzung von Software', 0),
(38, 11, 'Einräumung der Nutzungsrechte', 1),
(39, 11, 'Dauer der Nutzung der Software', 0),
(40, 11, 'finanzielle Fragen der Nutzung', 0),
(41, 12, 'individueller Vertrag', 0),
(42, 12, 'vertragliche Regelungen eines Verwenders für eine Vielzahl von Verträgen mit unterschiedlichen Vertragspartnern', 1),
(43, 12, 'unverbindliche rechtliche Informationen eines Anbieters von Waren oder Dienstleistungen', 0),
(44, 12, 'Vertragsbedingungen für Bestellung im E-Commerce', 0),
(45, 13, 'Die AGB sind nicht wirksam in den geschlossenen Vertrag einbezogen worden.', 1),
(46, 13, 'Es gilt das Bürgerliche Gesetzbuch (BGB).', 0),
(47, 13, 'keine', 0),
(48, 13, 'Die AGB müssen lesbar gemacht werden.', 0),
(49, 14, 'Unterlassung', 1),
(50, 14, 'Minderung', 0),
(51, 14, 'Nachbesserung', 0),
(52, 14, 'Garantie', 0),
(53, 15, 'Open Source Software darf unter Beachtung der Lizenzvorgaben beliebig oft kopiert, verbreitet und genutzt werden.', 1),
(54, 15, 'der Zweck der Software', 0),
(55, 15, 'keiner', 0),
(56, 15, 'der Ort der Programmierung', 0),
(57, 3, 'Fotografie', 0),
(58, 3, 'Buch', 0),
(59, 3, 'Logo', 0),
(60, 3, 'technische Erfindung', 1);

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
  `Änderungsdatum` date NOT NULL,
  `MeldeGrund` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `fragen`
--

INSERT INTO `fragen` (`FragenID`, `KursID`, `FragentypID`, `FrageText`, `InfoText`, `Status`, `Änderungsdatum`, `MeldeGrund`) VALUES
(0, 2, 1, 'Wie viele Bücher hat das Bürgerliche Gesetzbuch (BGB)?', 'Nachzulesen im BGB', 1, '2024-02-20', NULL),
(1, 2, 1, 'Wie viele „Kaufleute“-Begriffe kennt das Handelsgesetzbuch (HGB)?', 'Im HGB nachzulesen', 1, '2024-02-20', NULL),
(2, 2, 1, 'Ist das Hacking von IT-Systemen immer strafbar?', 'IM STGB nachzulesen', 1, '2024-02-20', NULL),
(3, 2, 1, 'Was kann nach dem Patentrecht geschützt sein?', 'Details im Patentgesetz', 1, '2024-02-22', NULL),
(5, 2, 1, 'Wann muss ein Kaufmann einen bei Lieferung sofort erkannten Mangel an einer gelieferten Ware nach dem Gesetz rügen?', 'Nachzulesen im HGB', 1, '2024-02-20', NULL),
(6, 2, 1, 'Welche der nachfolgend genannten gesetzlichen Vorschriften regelt den Kaufvertrag?', 'Details im BGB', 1, '2024-02-22', NULL),
(7, 2, 1, 'Welches Recht muss der Käufer bei einem Mangel einer Kaufsache zuerst geltend machen?', 'Details im BGB', 1, '2024-02-22', NULL),
(8, 2, 1, 'Welche Vertragsart findet auf den Erstellungsvertrag von Software Anwendung?', 'Details im BGB', 1, '2024-02-22', NULL),
(9, 2, 1, 'Wann muss eine öffentliche Einrichtung das Vergaberecht beachten?', 'Details im BGB', 1, '2024-02-22', NULL),
(10, 2, 1, 'Wann greift das Kartellrecht ein?', 'Details im BGB', 1, '2024-02-22', NULL),
(11, 2, 1, 'Was regelt die Lizenz bei der Softwareüberlassung?', 'Details im BGB', 1, '2024-02-22', NULL),
(12, 2, 1, 'Was sind Allgemeine Geschäftsbedingungen (AGB)?', 'Details im BGB', 1, '2024-02-22', NULL),
(13, 2, 1, 'Was kann die Folge sein, wenn Allgemeine Geschäftsbedingungen (AGB) nicht lesbar sind?', 'Details im BGB', 1, '2024-02-22', NULL),
(14, 2, 1, 'Welcher Anspruch kann zur Durchsetzung von Ansprüchen aus dem Urheberrecht geltend macht werden?', 'Details im BGB', 1, '2024-02-22', NULL),
(15, 2, 1, 'Was ist der Unterschied zwischen Open Source Software und „normaler“ Software?', 'Details im BGB', 1, '2024-02-22', NULL);

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

--
-- Daten für Tabelle `fragentyp`
--

INSERT INTO `fragentyp` (`FragentypID`, `Beschreibung`) VALUES
(1, 'multiplechoice');

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
  MODIFY `AntwortID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
  MODIFY `BenutzerID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `fragen`
--
ALTER TABLE `fragen`
  MODIFY `FragenID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT für Tabelle `fragentyp`
--
ALTER TABLE `fragentyp`
  MODIFY `FragentypID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT für Tabelle `kurse`
--
ALTER TABLE `kurse`
  MODIFY `KursID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `spiele`
--
ALTER TABLE `spiele`
  MODIFY `SpieleID` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
