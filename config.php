<?php
/* =========================================================================
   Törn-Briefing — Einstellungen
   Diese Datei liegt auf dem Server und wird NIEMALS an den Browser
   ausgeliefert. Hier stehen deshalb Kennwörter und der Zielhafen im
   Klartext. Das ist Absicht und sicher, solange die Datei .php heißt.
   ========================================================================= */

/* --- 1. Die Crew. Namen und Kennwörter frei ändern. ---------------------- */
$CREW = [
  'felix'     => ['name' => 'Felix',     'pass' => 'Palstek-7Kn'],
  'benni'     => ['name' => 'Benni',     'pass' => 'Luvgierig!22'],
  'benji'     => ['name' => 'Benji',     'pass' => 'Reff2-Sturm'],
  'lorenz'    => ['name' => 'Lorenz',    'pass' => 'Ankerkette#9'],
  'sebastian' => ['name' => 'Sebastian', 'pass' => 'Webleinstek+5'],
  'micha'     => ['name' => 'Micha',     'pass' => 'Fender-Backbord3'],
  'freddy'    => ['name' => 'Freddy',    'pass' => 'Kielwasser!8'],
  'simon'     => ['name' => 'Simon',     'pass' => 'Grossschot-42'],
];

/* --- 2. Reihenfolge der Prüfungen und ihre Signalflagge ------------------
   Die Buchstaben ergeben von oben nach unten den Namen des Hafens.
   Wer die Reihenfolge ändert, muss die Buchstaben mitziehen.            */
$GAMES = [
  'vokabeln'  => 'P',
  'mob'       => 'O',
  'versenken' => 'R',
  'kommando'  => 'T',
  'anker'     => 'O',
];

/* --- 3. Die Auflösung. Wird erst ausgeliefert, wenn alle fünf
       Prüfungen bestanden sind. Vorher verlässt kein Zeichen davon
       den Server. ------------------------------------------------------ */
$HAFEN = [
  'place'   => 'Porto Rosa',
  'country' => 'Sizilien',
  'lead'    => 'Alle fünf Flaggen stehen. Das Fall ist dicht.',
  'tease'   => 'Und bevor jetzt jemand den Portwein kaltstellt: Es geht nicht nach Porto in Portugal. Kein Douro, keine Azulejos, kein Fado. Es geht nach',
  'body'    => 'Dort liegt das Boot, dort wird übernommen, dort geht es los. Seesack statt Hartschalenkoffer, @Benji. Wer trotzdem einen Rollkoffer über den Steg zieht, schläft im Vorschiff.',
  'note'    => 'Der Berg da hinten, der Funken spuckt, liegt übrigens gleich um die Ecke.',
];

/* --- 4. Dein Verwaltungsschlüssel. UNBEDINGT ÄNDERN. ---------------------
   Damit kannst du Ergebnisse zurücksetzen, siehe LIESMICH.txt.          */
$ADMIN_KEY = 'bitte-hier-etwas-eigenes-eintragen';

/* --- 5. Ablageort der Ergebnisdatenbank --------------------------------- */
$DB_PFAD = __DIR__ . '/daten/toern.sqlite';
