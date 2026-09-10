<?php
/* =========================================================================
   Fragen, richtige Antworten und Begründungen.
   Diese Datei liegt auf dem Server und wird nie an den Browser geschickt.
   Automatisch erzeugt - Änderungen hier wirken sofort im Spiel.
   ========================================================================= */

/* --- Schiffsvokabeln. 'w' => 1 markiert das feste Wertungsset. --- */
$F_VOKABELN = [
  ['w' => 1, 'mark' => [200, 120, 28], 'q' => 'Wie heißt das markierte Teil?', 'a' => 'Mast', 'o' => ['Mast', 'Vorstag', 'Baum', 'Salinge'], 'fb' => 'Der Mast. Wer ihn Stange nennt, zahlt in die Bordkasse.'],
  ['w' => 1, 'mark' => [280, 201, 24], 'q' => 'Und dieses hier?', 'a' => 'Großbaum', 'o' => ['Großbaum', 'Spinnakerbaum', 'Reling', 'Achterstag'], 'fb' => 'Der Großbaum. Das Teil, das bei einer unkontrollierten Halse Köpfe sucht.'],
  ['w' => 1, 'mark' => [150, 132, 24], 'q' => 'Das Drahtseil von der Mastspitze zum Bug ist das…', 'a' => 'Vorstag', 'o' => ['Vorstag', 'Großschot', 'Fall', 'Wanten'], 'fb' => 'Vorstag. Daran hängt das Vorsegel und indirekt der Mast.'],
  ['w' => 1, 'mark' => [411, 262, 22], 'q' => 'Das Blatt unter dem Heck ist…', 'a' => 'Ruderblatt', 'o' => ['Ruderblatt', 'Kiel', 'Propeller', 'Echolot'], 'fb' => 'Ruderblatt. Beim Ankern im Flachen das teuerste Bauteil am Boot.'],
  ['w' => 1, 'mark' => [256, 268, 26], 'q' => 'Und das große Blatt in der Mitte unten?', 'a' => 'Kiel', 'o' => ['Kiel', 'Ruderblatt', 'Schwert', 'Bilge'], 'fb' => 'Kiel. Der Grund, warum wir nicht umkippen, und der Grund, warum wir nicht überall hinfahren.'],
  ['w' => 1, 'mark' => [359, 203, 20], 'q' => 'Die Trommel an Deck, mit der man Leinen unter Last dichtholt, heißt…', 'a' => 'Winsch', 'o' => ['Winsch', 'Klampe', 'Block', 'Spill'], 'fb' => 'Winsch. Kurbel rein, drei Rundtörns drauf, plötzlich bist du stark.'],
  ['w' => 0, 'mark' => [258, 128, 32], 'q' => 'Das markierte Segel heißt…', 'a' => 'Großsegel', 'o' => ['Großsegel', 'Genua', 'Spinnaker', 'Sturmfock'], 'fb' => 'Das Großsegel, kurz das Groß. Es hängt am Mast und liegt auf dem Baum.'],
  ['w' => 0, 'mark' => [158, 152, 28], 'q' => 'Und das vordere Segel?', 'a' => 'Genua', 'o' => ['Genua', 'Großsegel', 'Besan', 'Blister'], 'fb' => 'Genua oder Fock, je nach Größe. Bei uns wird sie zum Reffen eingerollt.'],
  ['w' => 0, 'mark' => [92, 224, 20], 'q' => 'Das vordere Ende des Bootes heißt…', 'a' => 'Bug', 'o' => ['Bug', 'Heck', 'Kiel', 'Süll'], 'fb' => 'Bug vorn, Heck hinten. Wer das verwechselt, steht beim Anlegen an der falschen Klampe.'],
  ['w' => 0, 'mark' => [428, 228, 20], 'q' => 'Und das hintere Ende?', 'a' => 'Heck', 'o' => ['Heck', 'Bug', 'Achterstag', 'Kombüse'], 'fb' => 'Heck. In Italien geht es damit zuerst an den Steg, deshalb übt ihr rückwärts fahren.'],
  ['w' => 1, 'mark' => null, 'q' => 'Unterschied zwischen Fall und Schot?', 'a' => 'Fall zieht hoch, Schot zieht dicht', 'o' => ['Fall zieht hoch, Schot zieht dicht', 'Schot zieht hoch, Fall zieht dicht', 'Beides dasselbe', 'Fall ist Draht, Schot ist Tauwerk'], 'fb' => 'Fälle setzen Segel, Schoten trimmen sie.'],
  ['w' => 1, 'mark' => null, 'q' => 'Die Bilge ist…', 'a' => 'der tiefste Punkt im Rumpf, wo sich Wasser sammelt', 'o' => ['der tiefste Punkt im Rumpf, wo sich Wasser sammelt', 'die Küche', 'der Ankerkasten', 'die Toilette'], 'fb' => 'Bilge. Einmal am Tag reinschauen, sonst wird der Abend interessant.'],
  ['w' => 1, 'mark' => null, 'q' => 'Die Kombüse ist…', 'a' => 'die Küche an Bord', 'o' => ['die Küche an Bord', 'die Toilette', 'die Vorschiffskabine', 'der Leinenstauraum'], 'fb' => 'Kombüse. Der Ort, an dem sich entscheidet, ob der Törn schön wird.'],
  ['w' => 1, 'mark' => null, 'q' => 'Wozu dienen Fender?', 'a' => 'Als Puffer zwischen Boot und Steg oder Nachbarboot', 'o' => ['Als Puffer zwischen Boot und Steg oder Nachbarboot', 'Zum Festmachen am Poller', 'Als Rettungsmittel', 'Zum Abstützen des Mastes'], 'fb' => 'Fender. Beim Ablegen wieder einholen, sonst fährst du wie ein Weihnachtsbaum aus dem Hafen.'],
  ['w' => 0, 'mark' => null, 'q' => 'Eine Klampe ist…', 'a' => 'der Beschlag an Deck, auf dem Leinen belegt werden', 'o' => ['der Beschlag an Deck, auf dem Leinen belegt werden', 'ein Knoten', 'ein Segelbeschlag am Mast', 'ein Teil des Ruders'], 'fb' => 'Klampe an Bord, Poller am Steg. Beide bekommen die Leine im Kreuz, nicht im Knoten.'],
  ['w' => 0, 'mark' => null, 'q' => 'Die Wanten sind…', 'a' => 'die seitlichen Drahtseile, die den Mast halten', 'o' => ['die seitlichen Drahtseile, die den Mast halten', 'die Leinen zum Segeltrimmen', 'die Handläufe am Kajütdach', 'die Streben unter Deck'], 'fb' => 'Wanten seitlich, Vorstag und Achterstag längs. Zusammen ergibt das ein stehendes Gut, das den Mast oben hält.'],
  ['w' => 0, 'mark' => null, 'q' => 'Der Niedergang ist…', 'a' => 'die Treppe vom Cockpit nach unten', 'o' => ['die Treppe vom Cockpit nach unten', 'der Weg zum Bug', 'das Absacken des Bootes im Wellental', 'der Ankerkasten'], 'fb' => 'Niedergang. Beim Runtergehen bei Welle immer mit dem Gesicht zur Treppe, wie auf einer Leiter.'],
  ['w' => 0, 'mark' => null, 'q' => 'Die Backskiste ist…', 'a' => 'der große Stauraum unter der Cockpitbank', 'o' => ['der große Stauraum unter der Cockpitbank', 'die Kiste für das Geschirr', 'die Kabine im Vorschiff', 'die Kiste mit den Seekarten'], 'fb' => 'Backskiste. Darin liegt alles, was man erst sucht, wenn man es dringend braucht.'],
  ['w' => 0, 'mark' => null, 'q' => 'Ein Seeventil ist…', 'a' => 'ein Absperrhahn für eine Öffnung im Rumpf', 'o' => ['ein Absperrhahn für eine Öffnung im Rumpf', 'ein Überdruckventil am Motor', 'ein Ventil im Schlauchboot', 'ein Teil der Bordtoilette am Waschbecken'], 'fb' => 'Seeventile sitzen an jedem Durchbruch unter der Wasserlinie. Beim Verlassen des Bootes zu, beim Duschen offen.'],
  ['w' => 0, 'mark' => null, 'q' => 'Das Echolot misst…', 'a' => 'die Wassertiefe unter dem Boot', 'o' => ['die Wassertiefe unter dem Boot', 'die Geschwindigkeit', 'die Windstärke', 'die Entfernung zur Küste'], 'fb' => 'Echolot Tiefe, Logge Fahrt, Windmesser Wind. Wichtig: Zeigt euer Gerät die Tiefe ab Kiel oder ab Wasserlinie?'],
  ['w' => 0, 'mark' => null, 'q' => 'Der Baumniederholer…', 'a' => 'zieht den Großbaum nach unten und hält den Segeldruck', 'o' => ['zieht den Großbaum nach unten und hält den Segeldruck', 'hebt den Baum an', 'sichert den Baum gegen Halsen', 'holt das Vorsegel dicht'], 'fb' => 'Ohne Niederholer geht der Baum bei achterlichem Wind hoch und das Segel verliert die Form.'],
  ['w' => 0, 'mark' => null, 'q' => 'Das Ankerspill ist…', 'a' => 'die Winde, die Ankerkette holt und steckt', 'o' => ['die Winde, die Ankerkette holt und steckt', 'der Kasten für den Anker', 'die Leine am Anker', 'das Gewicht an der Kette'], 'fb' => 'Spill. Es zieht das Boot zum Anker, nicht den Anker zum Boot. Deshalb fährt der Motor beim Ankerauf leicht mit.'],
  ['w' => 0, 'mark' => null, 'q' => 'Die Persenning ist…', 'a' => 'die Schutzhülle über Segel oder Cockpit', 'o' => ['die Schutzhülle über Segel oder Cockpit', 'ein zusätzliches Vorsegel', 'die Plane über der Backskiste', 'der Sonnenschirm am Heck'], 'fb' => 'Persenning drüber, wenn das Boot liegt. Sonne frisst Segeltuch schneller als Wind.'],
  ['w' => 0, 'mark' => null, 'q' => 'Kielwasser ist…', 'a' => 'die Spur, die das Boot im Wasser hinterlässt', 'o' => ['die Spur, die das Boot im Wasser hinterlässt', 'das Wasser in der Bilge', 'das Kühlwasser des Motors', 'das Wasser im Kieltank'], 'fb' => 'Am Kielwasser sieht man, ob jemand geradeaus steuert. Deshalb schaut der Skipper manchmal grundlos nach achtern.'],
  ['w' => 0, 'mark' => null, 'q' => 'Eine Seemeile sind…', 'a' => '1852 Meter', 'o' => ['1852 Meter', '1000 Meter', '1609 Meter', '2000 Meter'], 'fb' => 'Eine Seemeile ist eine Bogenminute auf dem Breitengrad. Praktisch: Am Kartenrand ist jede Minute genau eine Meile.'],
  ['w' => 0, 'mark' => null, 'q' => 'Der Lümmelbeschlag verbindet…', 'a' => 'den Großbaum mit dem Mast', 'o' => ['den Großbaum mit dem Mast', 'das Ruder mit der Pinne', 'den Anker mit der Kette', 'die Wanten mit dem Rumpf'], 'fb' => 'Lümmelbeschlag. Bestes Wort an Bord, und wer danach fragt, hat sofort einen Spitznamen weg.'],
];

/* --- Bordkommandos --- */
$F_KOMMANDO = [
  ['w' => 1, 'q' => 'Der Rudergänger ruft „Klar zur Wende?“. Die Crew antwortet…', 'a' => '„Ist klar!“', 'o' => ['„Ist klar!“', '„Ree!“', '„Rund achtern!“', '„Aye Sir!“'], 'fb' => 'Erst die Rückmeldung, dann kommt vom Rudergänger das „Ree!“ und das Ruder geht rüber.'],
  ['w' => 0, 'q' => 'Und wie lautet das Kommando kurz vor der Halse?', 'a' => '„Rund achtern!“', 'o' => ['„Rund achtern!“', '„Ree!“', '„Klar bei Fall!“', '„Aufschießen!“'], 'fb' => 'Bei der Wende „Ree“, bei der Halse „Rund achtern“. Und beim zweiten unbedingt alle Köpfe unter dem Baum durch.'],
  ['w' => 1, 'q' => '„Fier auf!“ heißt an der Winsch…', 'a' => 'Leine kontrolliert nachlassen', 'o' => ['Leine kontrolliert nachlassen', 'Leine ruckartig durchholen', 'Leine belegen', 'Leine loswerfen'], 'fb' => 'Fieren ist nachlassen, dichtholen ist das Gegenteil.'],
  ['w' => 0, 'q' => '„Dichtholen!“ heißt…', 'a' => 'die Schot durchsetzen, das Segel wird flacher', 'o' => ['die Schot durchsetzen, das Segel wird flacher', 'die Schot loslassen', 'das Segel bergen', 'den Anker hieven'], 'fb' => 'Dichtholen bringt das Segel näher an die Mittschiffslinie. Zu viel davon und das Boot legt sich hin statt zu laufen.'],
  ['w' => 1, 'q' => 'Du schaust nach vorn. Backbord ist…', 'a' => 'links', 'o' => ['links', 'rechts', 'achtern', 'oben'], 'fb' => 'Merkhilfe: früher war das Steuer immer rechts (Steuerbord) und die Küche mit dem Backofen immer links (Backbord).'],
  ['w' => 0, 'q' => 'Welche Lampe zeigt euer Boot nachts nach Steuerbord?', 'a' => 'grün', 'o' => ['grün', 'rot', 'weiß', 'gelb'], 'fb' => 'Steuerbord grün, Backbord rot, Heck weiß. Wer nachts nur Grün sieht, schaut auf die rechte Seite eines Bootes.'],
  ['w' => 1, 'q' => '„Luv“ ist die Seite…', 'a' => 'aus der der Wind kommt', 'o' => ['aus der der Wind kommt', 'in die der Wind weht', 'an der die Sonne steht', 'an der die Bierkiste steht'], 'fb' => 'Luv kommt, Lee geht. Wem schlecht wird, der geht nach Lee. Das ist der praktisch wichtigste Teil der Lektion.'],
  ['w' => 0, 'q' => '„Anluven“ bedeutet…', 'a' => 'näher an den Wind steuern', 'o' => ['näher an den Wind steuern', 'vom Wind wegdrehen', 'das Segel reffen', 'aufstoppen'], 'fb' => 'Anluven näher ran, abfallen weiter weg. Wer beides verwechselt, macht aus einer Wende eine Halse.'],
  ['w' => 0, 'q' => '„Abfallen“ bedeutet…', 'a' => 'den Kurs vom Wind wegdrehen', 'o' => ['den Kurs vom Wind wegdrehen', 'näher an den Wind steuern', 'die Fahrt verringern', 'ankern'], 'fb' => 'Abfallen heißt mehr Wind von hinten. Bei viel Wind ist das der Weg aus dem Druck heraus.'],
  ['w' => 1, 'q' => '„Belegen!“ bedeutet…', 'a' => 'die Leine auf der Klampe festmachen', 'o' => ['die Leine auf der Klampe festmachen', 'die Leine aufschießen', 'das Segel reffen', 'den Anker fallen lassen'], 'fb' => 'Belegen ist festmachen. Aufschießen ist das ordentliche Aufrollen danach.'],
  ['w' => 0, 'q' => 'Eine Leine „aufschießen“ heißt…', 'a' => 'sie ordentlich in Buchten aufrollen', 'o' => ['sie ordentlich in Buchten aufrollen', 'sie festmachen', 'sie über Bord werfen', 'sie kürzen'], 'fb' => 'Eine aufgeschossene Leine läuft im Ernstfall ohne Knoten aus. Deshalb nervt der Skipper damit.'],
  ['w' => 1, 'q' => '„Reffen“ heißt…', 'a' => 'die Segelfläche verkleinern', 'o' => ['die Segelfläche verkleinern', 'das Segel ganz bergen', 'den Baum sichern', 'das Vorsegel ausrollen'], 'fb' => 'Rechtzeitig reffen, @Simon, damit das Boot nicht in den Wind gezogen wird. Wer überlegt, ob er reffen soll, hätte schon reffen sollen.'],
  ['w' => 1, 'q' => '„Leinen los!“ im Hafen richtet sich an…', 'a' => 'die Crew, die die Festmacher loswirft', 'o' => ['die Crew, die die Festmacher loswirft', 'den Rudergänger', 'den Hafenmeister', 'den Motor'], 'fb' => 'Und zwar erst, wenn der Motor läuft und alle wissen, wohin.'],
  ['w' => 1, 'q' => '„Auf Rundtörn legen“ heißt…', 'a' => 'einmal um Winsch oder Klampe, um Zug zu halten', 'o' => ['einmal um Winsch oder Klampe, um Zug zu halten', 'mit dem Beiboot um die Bucht fahren', 'den Anker auffieren', 'das Großsegel bergen'], 'fb' => 'Ein Rundtörn nimmt die Last, ohne dass du die Leine schon festmachst.'],
  ['w' => 0, 'q' => '„Fender an Steuerbord!“ heißt…', 'a' => 'Fender an der rechten Bordwand ausbringen', 'o' => ['Fender an der rechten Bordwand ausbringen', 'Fender an der linken Bordwand ausbringen', 'alle Fender einholen', 'Fender ins Cockpit legen'], 'fb' => 'Und zwar auf Höhe der breitesten Stelle. Ein Fender zwei Meter über dem Steg ist Dekoration.'],
  ['w' => 0, 'q' => 'Wozu dient die Spring beim Anlegen?', 'a' => 'Sie verhindert, dass das Boot am Steg vor und zurück wandert', 'o' => ['Sie verhindert, dass das Boot am Steg vor und zurück wandert', 'Sie hält das Boot vom Steg weg', 'Sie sichert den Anker', 'Sie ersetzt die Vorleine'], 'fb' => 'Vorleine und Achterleine halten längs, die Spring hält die Längsbewegung an. Ohne sie tanzt das Boot die ganze Nacht.'],
  ['w' => 0, 'q' => '„Kette auf dreißig Meter stecken!“ heißt…', 'a' => 'dreißig Meter Ankerkette ausfahren', 'o' => ['dreißig Meter Ankerkette ausfahren', 'in dreißig Metern Tiefe ankern', 'dreißig Meter vom Ufer wegbleiben', 'die Kette auf dreißig Meter kürzen'], 'fb' => 'Stecken heißt ausgeben. Die Faustregel bleibt: mindestens die vierfache Wassertiefe.'],
  ['w' => 0, 'q' => 'Ihr liegt auf Steuerbordbug. Das heißt…', 'a' => 'der Wind kommt von der rechten Seite', 'o' => ['der Wind kommt von der rechten Seite', 'der Wind kommt von der linken Seite', 'ihr fahrt nach Steuerbord', 'ihr habt Steuerbord als Zielrichtung'], 'fb' => 'Und das ist mehr als Theorie: Wer auf Steuerbordbug liegt, hat gegenüber dem anderen Segler Vorfahrt.'],
  ['w' => 0, 'q' => '„Beidrehen“ bedeutet…', 'a' => 'das Boot mit backstehender Fock quasi zum Stillstand bringen', 'o' => ['das Boot mit backstehender Fock quasi zum Stillstand bringen', 'so schnell wie möglich wenden', 'vor Anker gehen', 'rückwärts fahren'], 'fb' => 'Beigedreht liegt das Boot ruhig und driftet langsam. Der beste Trick für Pause, Reff oder ein Problem an Deck.'],
  ['w' => 0, 'q' => '„Aufstoppen“ heißt…', 'a' => 'die Fahrt aus dem Boot nehmen', 'o' => ['die Fahrt aus dem Boot nehmen', 'Vollgas geben', 'den Anker werfen', 'das Ruder festbinden'], 'fb' => 'Ein Segelboot hat keine Bremse. Aufstoppen geht über den Wind oder über den Rückwärtsgang, beides will geübt sein.'],
  ['w' => 0, 'q' => '„Klarschiff!“ vor dem Ablegen heißt…', 'a' => 'alles verstaut und seeklar, nichts liegt lose herum', 'o' => ['alles verstaut und seeklar, nichts liegt lose herum', 'das Deck schrubben', 'die Segel setzen', 'die Crew antreten lassen'], 'fb' => 'Alles, was bei Krängung fliegen kann, fliegt auch. Meistens die Kaffeetasse, manchmal das Handy.'],
  ['w' => 0, 'q' => 'Wozu dient die Muringleine im Mittelmeerhafen?', 'a' => 'Sie liegt am Grund und hält beim Heckanleger den Bug', 'o' => ['Sie liegt am Grund und hält beim Heckanleger den Bug', 'Sie hält das Heck am Steg', 'Sie ersetzt den Anker beim Ankern in der Bucht', 'Sie sichert den Baum'], 'fb' => 'Heck an den Steg, Muring vom Steg nach vorn durchhieven, dann steht das Boot. Sie ist meistens glitschig und immer voller Muscheln.'],
  ['w' => 0, 'q' => 'Ein Segelboot fährt unter Motor mit gesetztem Segel. Es gilt als…', 'a' => 'Maschinenfahrzeug', 'o' => ['Maschinenfahrzeug', 'Segelfahrzeug', 'manövrierunfähig', 'Fahrzeug mit Vorrang'], 'fb' => 'Motor an heißt Motorboot, Segel hin oder her. Deshalb gehört tagsüber der schwarze Kegel gesetzt.'],
  ['w' => 0, 'q' => 'Welcher Knoten macht eine feste Schlaufe, die sich nicht zuzieht?', 'a' => 'der Palstek', 'o' => ['der Palstek', 'der Achtknoten', 'der Webleinstek', 'der Kreuzknoten'], 'fb' => 'Der Palstek ist der König der Knoten. Wer ihn kann, wird an Bord nie zum Deckwaschen eingeteilt.'],
  ['w' => 0, 'q' => 'Wozu dient ein Achtknoten am Ende einer Schot?', 'a' => 'Er verhindert, dass die Schot aus dem Block rutscht', 'o' => ['Er verhindert, dass die Schot aus dem Block rutscht', 'Er verbindet zwei Leinen', 'Er macht die Leine am Poller fest', 'Er kürzt die Leine'], 'fb' => 'Ein Stopperknoten. Ohne ihn peitscht die Schot bei Böe aus dem Beschlag und ihr fangt das Vorsegel im Wind wieder ein.'],
  ['w' => 0, 'q' => 'Wie schnell ist ein Boot mit sechs Knoten Fahrt?', 'a' => 'sechs Seemeilen pro Stunde', 'o' => ['sechs Seemeilen pro Stunde', 'sechs Kilometer pro Stunde', 'sechs Meter pro Sekunde', 'sechs Seemeilen pro Tag'], 'fb' => 'Ein Knoten ist eine Seemeile pro Stunde, also gut 1,85 km/h. Sechs Knoten sind rund elf Stundenkilometer, und das fühlt sich an Bord schnell an.'],
];

/* --- Mann über Bord. Der Index der richtigen Antwort ist 'richtig'. --- */
$F_MOB = [
  ['q' => 'Es ist 14:40. Jemand geht über die Reling. Was tust du in der ersten Sekunde?', 'richtig' => 0, 'opts' => [
      ['t' => '„Mann über Bord!“ rufen, Rettungsmittel werfen, jemanden als Ausguck abstellen', 'fb' => 'Richtig. Der Ausguck ist das Wichtigste: einer zeigt dauernd auf den Kopf im Wasser und macht sonst gar nichts.'],
      ['t' => 'Erst mal die Segel bergen, damit Ruhe an Deck einkehrt', 'fb' => 'Sehr ordentlich. In der Zeit ist das Boot 150 Meter weiter und der Kopf im Wasser sieht aus wie jede andere Welle.'],
      ['t' => 'Sofort ins Wasser springen und hinterher', 'fb' => 'Jetzt sind zwei im Wasser und einer weniger an Bord. Steht so in jedem zweiten Unfallbericht, meistens mit deinem Namen drüber.'],
  ]],
  ['q' => 'Der Ausguck zeigt. Was passiert am Kartentisch?', 'richtig' => 0, 'opts' => [
      ['t' => 'MOB-Taste am Plotter drücken', 'fb' => 'Richtig. Ein Knopf, eine Position, ein Rückweg. Zwei Sekunden.'],
      ['t' => 'Erst mal Position von Hand ins Logbuch schreiben', 'fb' => 'Wunderbare Handschrift. Leider hat sich die Position beim dritten Buchstaben schon 40 Meter verschoben.'],
      ['t' => 'Nichts, das GPS läuft ja mit', 'fb' => 'Das GPS weiß, wo du bist. Wo er ist, interessiert es kein bisschen.'],
  ]],
  ['q' => 'Segeln unter Groß und Genua, halber Wind. Wie kommst du zurück?', 'richtig' => 1, 'opts' => [
      ['t' => 'Sofort aufschießen und den Motor unter dem Groß volle Kraft geben', 'fb' => 'Erste Schot im Propeller, Motor aus, jetzt treibt ihr beide. Immerhin gemeinsam.'],
      ['t' => 'Q-Wende oder Hafenmanöver fahren, Motor bei Bedarf, aber vorher Leinen an Deck kontrollieren', 'fb' => 'Richtig. Ein Manöver, das ihr könnt, plus ein Blick nach achtern. Das rettet den Propeller.'],
      ['t' => 'Halsen, weil es schneller geht', 'fb' => 'Bei fünf Windstärken und Panik ist die Halse die Variante, bei der gleich noch der Baum jemanden umlegt. Dann habt ihr zwei Fälle.'],
  ]],
  ['q' => 'Du bist zurück in der Nähe. Wie näherst du dich an?', 'richtig' => 1, 'opts' => [
      ['t' => 'Mit Fahrt drauf zuhalten und im letzten Moment abdrehen', 'fb' => 'Filmreif. Praktisch fährst du entweder vorbei oder drüber. Beides schlecht.'],
      ['t' => 'Boot in Luv der Person aufstoppen, Aufnahme an der Leeseite', 'fb' => 'Richtig. Das Boot treibt langsam zur Person, sie bleibt in Sicht und im Windschatten.'],
      ['t' => 'Die Person das Boot anschwimmen lassen', 'fb' => 'Nach zehn Minuten im Wasser schwimmt niemand mehr sinnvoll. Das Boot kommt zum Menschen.'],
  ]],
  ['q' => 'Boot liegt, Person ist längsseits. Was ist mit dem Motor?', 'richtig' => 1, 'opts' => [
      ['t' => 'Läuft im Leerlauf weiter, falls wir doch noch manövrieren müssen', 'fb' => 'Neben einem Menschen im Wasser dreht die Schraube nicht. Auch nicht ein bisschen.'],
      ['t' => 'Aus oder mindestens Getriebe auf neutral und Hand weg vom Hebel', 'fb' => 'Richtig. Das ist die Sekunde, in der die meisten Unfälle im Unfall passieren.'],
      ['t' => 'Egal, Hauptsache schnell', 'fb' => 'Genau dieser Satz steht in erschreckend vielen Unfallberichten. Meistens im Konjunktiv.'],
  ]],
  ['q' => 'Letzter Schritt: die Person kommt an Bord. Womit?', 'richtig' => 1, 'opts' => [
      ['t' => 'Zu zweit an den Armen hochziehen', 'fb' => 'Ein nasser Erwachsener wiegt gefühlt 120 Kilo und ihr steht auf schwankendem Deck. Ergebnis: zwei Rückenschäden und er hängt immer noch im Wasser.'],
      ['t' => 'Badeleiter runter, Bergegurt oder Großfall über die Winsch, waagerecht bergen wenn möglich', 'fb' => 'Richtig. Winsch statt Bizeps. Und wer lange im kalten Wasser lag, wird möglichst waagerecht geborgen.'],
      ['t' => 'Beiboot aussetzen und die Person hineinziehen', 'fb' => 'Zwanzig Minuten Aufbauzeit, danach habt ihr ein zweites wackliges Boot neben dem ersten.'],
  ]],
];

/* --- Ankerplatz. Grafik und Buchten liegen im Browser, hier nur die
       richtige Lösung und die Begründungen. --- */
$F_ANKER = [
  'ank1' => ['richtig' => 'D', 'why' => ['C' => 'Zu weit draußen. Der Wind hat die ganze Bucht Anlauf, die Welle auch.', 'D' => 'Richtig. Dicht unter dem Nordufer. Das Land liegt in Luv, die Welle baut sich gar nicht erst auf.', 'A' => 'Neben der Landzunge, aber nach Süden und Westen offen. Da läuft Schwell rein.', 'B' => 'Vor dem Taleinschnitt. Da fallen nachts Böen herunter, deutlich stärker als draußen.']],
  'ank2' => ['richtig' => 'A', 'why' => ['D' => 'Drei Meter Wasser bei zwei Metern Tiefgang. Bei etwas Welle klopft der Kiel auf den Grund.', 'A' => 'Richtig. Acht Meter, also 32 Meter Kette. Passt, hält, und ihr liegt frei.', 'B' => '25 Meter tief. Dafür bräuchtet ihr 100 Meter Kette. Ihr habt 40.', 'C' => 'Mitten im Fahrwasser zwischen den Tonnen. Nachts kommt die Fähre und ihr steht in der Zeitung.']],
  'ank3' => ['richtig' => 'B', 'why' => ['D' => 'Seegras. Der Anker pflügt eine Furche und hält nichts. Außerdem geschützt, und die Küstenwache hat Zeit und ein Fernglas.', 'B' => 'Richtig. Sand, sechs Meter, kurze Kette, sicherer Halt.', 'C' => 'Sand ja, aber 28 Meter tief. So viel Kette hast du nicht.', 'A' => 'Seegras in neun Metern. Hält so gut wie ein Bierdeckel auf einer nassen Theke.']],
  'ank4' => ['richtig' => 'C', 'why' => ['B' => 'Am Rand seines Schwojkreises. Beim ersten Winddreher küsst du sein Vorschiff und lernst neue italienische Vokabeln.', 'C' => 'Richtig. Außerhalb des Kreises, weg von den Felsen, genug Platz zum Schwojen.', 'D' => 'Direkt neben den Felsen. Bei Wind aus der falschen Richtung wird aus dem Ruderblatt Konfetti.', 'A' => 'Immer noch in seinem Kreis, nur weiter unten.']],
];

/* --- Schiffe versenken --- */
$F_FLOTTE = [
  ['n' => 'Kühlschiff „Bierkiste“', 'len' => 5],
  ['n' => 'Yacht „Ausrede“', 'len' => 4],
  ['n' => 'Tender „Nachzügler“', 'len' => 3],
  ['n' => 'Beiboot „Blechkanne“', 'len' => 3],
  ['n' => 'Schlauchboot „Pantoffel“', 'len' => 2],
];
const VERSENKEN_BUDGET = 45;
const VERSENKEN_PAR = 24;
const VERSENKEN_N = 8;
