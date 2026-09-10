TÖRN-BRIEFING — Einrichtung auf einem Webspace
==============================================

Was du brauchst
---------------
Einen ganz normalen Webspace-Tarif mit PHP (Version 8 oder neuer). Das
haben alle gängigen Anbieter im Standardpaket. Etwas anderes ist nicht
nötig: keine Datenbank buchen, kein Server konfigurieren.


Schritt 1 — Dateien hochladen
-----------------------------
Nach der Bestellung bekommst du Zugangsdaten für den Dateimanager oder
für FTP. Lade den kompletten Inhalt dieses Ordners in das Verzeichnis,
das im Browser erscheint. Je nach Anbieter heißt es
httpdocs, htdocs, public_html oder www.

Danach muss es dort so aussehen:

    index.html        die Seite selbst (zeigt nur an, weiß nichts)
    api.php           nimmt Anfragen entgegen
    spiel.php         führt die Prüfungen und vergibt die Punkte
    fragen.php        alle Fragen, Lösungen und Begründungen
    config.php        deine Einstellungen
    pruefung.php      einmalige Selbstprüfung, danach löschen
    .htaccess
    daten/            leerer Ordner, hier entsteht die Datenbank
    daten/.htaccess


Schritt 2 — Selbstprüfung aufrufen
----------------------------------
Rufe im Browser deine-domain.de/pruefung.php auf. Die Seite sagt dir in
einer Liste, ob alles passt. Erst weitermachen, wenn alles grün ist.

Falls "Ordner daten beschreibbar" rot bleibt: Im Dateimanager mit der
rechten Maustaste auf den Ordner daten, Rechte oder CHMOD auf 755
oder 775 setzen.


Schritt 3 — config.php anpassen
-------------------------------
Öffne config.php im Dateimanager des Anbieters. Dort stehen vier Dinge:

  1. Die Crew mit Namen und Kennwörtern.
  2. Die Reihenfolge der Prüfungen mit ihren Flaggenbuchstaben.
     Achtung: Die Buchstaben ergeben den Hafennamen. Wer die Reihenfolge
     ändert, muss sie mitziehen.
  3. Die Auflösung: Hafen, Region und die Texte.
  4. Deinen Verwaltungsschlüssel. Den unbedingt ändern, irgendetwas
     Langes, das niemand rät.

Diese Datei bleibt auf dem Server. Kein Browser bekommt sie jemals zu
sehen, deshalb dürfen Kennwörter und der Hafen dort im Klartext stehen.
Wichtig: Die Datei muss config.php heißen. Wenn du sie in config.txt
umbenennst, wäre sie lesbar.


Schritt 4 — https einschalten
-----------------------------
Bei jedem Anbieter gibt es ein kostenloses SSL-Zertifikat (Let's Encrypt),
meist ein Klick im Kundenmenü. Ohne https werden Kennwörter unverschlüsselt
übertragen. Danach die Seite über https://deine-domain.de aufrufen.


Schritt 5 — pruefung.php löschen
--------------------------------
Wenn alles läuft, die Datei pruefung.php löschen. Sie verrät sonst
Kleinigkeiten über deine Einrichtung.


Ergebnisse zurücksetzen
-----------------------
Alle auf null:
    https://deine-domain.de/api.php?a=reset&key=DEINSCHLUESSEL

Nur einen Spieler:
    https://deine-domain.de/api.php?a=reset&key=DEINSCHLUESSEL&user=benji

DEINSCHLUESSEL ist der Wert aus Punkt 4 der config.php.


Was der Server absichert
------------------------
Die Prüfungen laufen vollständig auf dem Server. Der Browser bekommt
immer nur die aktuelle Frage und die Antwortmöglichkeiten in zufälliger
Reihenfolge. Welche davon richtig ist, erfährt er erst, nachdem geklickt
wurde. Konkret heißt das:

- Die richtigen Antworten stehen in fragen.php und verlassen den Server
  nie. Die Entwicklertools des Browsers zeigen sie nicht an.
- Die Punktzahl rechnet der Server. Der Browser kann keine Punktzahl
  melden, diese Schnittstelle gibt es nicht mehr.
- Bei Schiffe versenken kennt nur der Server die Positionen der Flotte.
  Jeder Schuss wird einzeln beantwortet.
- Der Hafen wird erst ausgeliefert, wenn alle fünf Prüfungen stehen.
  Vorher steht davon kein Zeichen im Browser, auch nicht verschlüsselt.
- Ein Flaggenbuchstabe kommt erst nach der zugehörigen Prüfung.
- Jede Prüfung zählt genau einmal und nur in der richtigen Reihenfolge.
- Bei den Bordkommandos misst der Server die Zeit. Wer länger als
  fünfzehn Sekunden braucht, bekommt die Frage als falsch gewertet,
  auch wenn er die Uhr im Browser anhält.
- Kennwörter werden serverseitig geprüft, die Anmeldung hält per Cookie
  rund vier Monate.
- Die Datenbank liegt in daten/ und ist per .htaccess gesperrt.


Die Abbruchregel
----------------
Ein begonnener Durchgang ist verbindlich. Wer mitten in einer Prüfung
abbricht, die Seite neu lädt oder eine andere Prüfung startet, bekommt
den bis dahin erreichten Stand gewertet. Beispiel: drei von zehn
Vokabeln richtig und dann abgebrochen ergibt 21 Punkte, und die Prüfung
ist damit erledigt. Der Tempobonus bei den Vokabeln wird nur bei
vollständigem Durchgang gutgeschrieben.

Das ist Absicht: Sonst könnte man bei einer unbequemen Frage abbrechen
und es neu versuchen.


Fragen ändern oder ergänzen
---------------------------
Alles steht in fragen.php. Der Aufbau ist immer gleich: Frage, richtige
Antwort, die Antwortmöglichkeiten und der Erklärungstext. Bei den
Vokabeln und Kommandos bedeutet 'w' => 1, dass die Frage zum festen
Wertungsset gehört, das alle bekommen. Fragen mit 'w' => 0 tauchen nur
im Übungsmodus auf, in dem zufällig gezogen wird.

Wichtig: Die richtige Antwort muss wortgleich auch unter den
Antwortmöglichkeiten stehen.


Gleichzeitiger Zugriff
---------------------
Die Ergebnisse liegen in einer SQLite-Datenbank. Die sperrt beim
Schreiben kurz die Datei. Bei acht Leuten und winzigen Schreibvorgängen
ist das kein Thema: Getestet mit acht Spielern, die gleichzeitig alle
fünf Prüfungen durchspielen, rund 1500 Anfragen in vier Sekunden, ohne
einen einzigen Fehler. Zusätzlich wartet die Datenbank bis zu acht
Sekunden auf eine gesperrte Datei, statt sofort aufzugeben, und
Anfragen desselben Spielers werden ohnehin nacheinander abgearbeitet.


Umzug oder Sicherung
--------------------
Die komplette Ergebnisliste steckt in daten/toern.sqlite. Diese eine
Datei sichern reicht als Backup. Darin liegen zwei Tabellen: die
gewerteten Ergebnisse und das Protokoll der Durchgänge.
