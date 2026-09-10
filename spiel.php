<?php
/* =========================================================================
   Törn-Briefing — Prüfungsablauf auf dem Server
   Der Browser bekommt nur Fragetexte und Antwortmöglichkeiten. Welche
   Antwort richtig ist, wie viele Punkte es gibt und wo die Schiffe liegen,
   verlässt diesen Server nie.
   ========================================================================= */
declare(strict_types=1);
require_once __DIR__ . '/fragen.php';

const VOKABELN_N = 10;
const KOMMANDO_N = 8;
const KOMMANDO_UHR = 15;      // Sekunden je Kommando
const NACHSICHT = 2.0;        // Zuschlag für Leitungswege

function tabellen(PDO $db): void {
  $db->exec('CREATE TABLE IF NOT EXISTS laeufe (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      nutzer TEXT NOT NULL, spiel TEXT NOT NULL, modus TEXT NOT NULL,
      status TEXT NOT NULL, zustand TEXT NOT NULL, punkte INTEGER NOT NULL DEFAULT 0,
      start INTEGER NOT NULL, aktiv INTEGER NOT NULL)');
  $db->exec('CREATE INDEX IF NOT EXISTS laeufe_offen ON laeufe (nutzer, status)');
}

function jetzt(): float { return microtime(true); }

function offenerLauf(PDO $db, string $nutzer): ?array {
  $st = $db->prepare('SELECT * FROM laeufe WHERE nutzer = ? AND status = ? ORDER BY id DESC LIMIT 1');
  $st->execute([$nutzer, 'offen']);
  $l = $st->fetch();
  if (!$l) return null;
  $l['zustand'] = json_decode($l['zustand'], true) ?: [];
  return $l;
}

function laufSpeichern(PDO $db, array $l): void {
  $st = $db->prepare('UPDATE laeufe SET zustand = ?, punkte = ?, status = ?, aktiv = ? WHERE id = ?');
  $st->execute([json_encode($l['zustand'], JSON_UNESCAPED_UNICODE), (int)$l['punkte'], $l['status'], time(), (int)$l['id']]);
}

/* --- Punktzahl aus dem aktuellen Stand. Gilt auch für Abbrüche. --------- */
function punkteBerechnen(array $l, bool $vollstaendig): int {
  $z = $l['zustand'];
  switch ($l['spiel']) {
    case 'vokabeln':
      $p = 7 * ($z['richtig'] ?? 0);
      if ($vollstaendig) {
        $t = (float)($z['zeit'] ?? 0);
        $p += max(0.0, min(30.0, 30.0 * (150.0 - $t) / 90.0));
      }
      return (int)round($p);
    case 'kommando': return (int)round(12.5 * ($z['richtig'] ?? 0));
    case 'mob':      return (int)round((100.0 / 6.0) * ($z['richtig'] ?? 0));
    case 'anker':    return 25 * ($z['richtig'] ?? 0);
    case 'versenken':
      $gesamt = 0; foreach ($z['schiffe'] as $s) $gesamt += $s['len'];
      $treffer = (int)($z['treffer'] ?? 0);
      $schuesse = (int)($z['schuesse'] ?? 0);
      if ($treffer >= $gesamt) {
        return (int)max(0, min(100, round(100 * (VERSENKEN_BUDGET - $schuesse) / (VERSENKEN_BUDGET - VERSENKEN_PAR))));
      }
      return (int)round(60.0 * $treffer / $gesamt);
  }
  return 0;
}

/* --- Lauf beenden. $vollstaendig=false bedeutet Abbruch. ---------------- */
function laufAbschliessen(PDO $db, array $l, bool $vollstaendig): array {
  $punkte = punkteBerechnen($l, $vollstaendig);
  $l['punkte'] = $punkte;
  $l['status'] = 'fertig';
  laufSpeichern($db, $l);
  if ($l['modus'] === 'wertung') {
    $st = $db->prepare('INSERT OR IGNORE INTO ergebnisse (nutzer, spiel, punkte, zeit) VALUES (?,?,?,?)');
    $st->execute([$l['nutzer'], $l['spiel'], $punkte, time()]);
  }
  return ['punkte' => $punkte, 'zeilen' => abschlussTexte($l, $vollstaendig), 'abbruch' => !$vollstaendig];
}

function verfallenLassen(PDO $db, string $nutzer): ?array {
  $l = offenerLauf($db, $nutzer);
  if (!$l) return null;
  return laufAbschliessen($db, $l, false);
}

/* --- Schlusstexte ------------------------------------------------------- */
function abschlussTexte(array $l, bool $voll): array {
  $z = $l['zustand'];
  $r = (int)($z['richtig'] ?? 0);
  if (!$voll) {
    $vorn = 'Durchgang abgebrochen. Gewertet wird, was bis dahin stand.';
    switch ($l['spiel']) {
      case 'versenken':
        return [$vorn, 'Getroffen: ' . (int)$z['treffer'] . ' Felder mit ' . (int)$z['schuesse'] . ' Schuss.'];
      default:
        return [$vorn, $r . ' richtige Antworten bis zum Abbruch.'];
    }
  }
  switch ($l['spiel']) {
    case 'vokabeln':
      $t = (int)round((float)($z['zeit'] ?? 0));
      $bonus = (int)round(max(0.0, min(30.0, 30.0 * (150.0 - (float)($z['zeit'] ?? 0)) / 90.0)));
      return [$r . ' von ' . VOKABELN_N . ' richtig in ' . $t . ' Sekunden reiner Denkzeit.',
              'Das macht ' . (7 * $r) . ' Punkte für Wissen und ' . $bonus . ' für Tempo. Ab jetzt gilt an Bord: wer „das Dingsda da vorne“ sagt, gibt eine Runde aus.'];
    case 'kommando':
      return [$r . ' von ' . KOMMANDO_N . ' Kommandos saßen.',
              'An Bord wird das laut und einmal gerufen. Wer zweimal fragen muss, kocht abends.'];
    case 'mob':
      return [$r . ' von 6 Schritten richtig.',
              'Merksatz: rufen, zeigen, Knopf drücken, zurückkommen, Motor aus, mit der Winsch bergen.'];
    case 'anker':
      return [$r . ' von 4 Buchten richtig gelesen.',
              'Merksatz: Schutz liegt in Luv. Erst der Wind, dann der Grund, dann der Platz zum Schwojen.'];
    case 'versenken':
      $gesamt = 0; foreach ($z['schiffe'] as $s) $gesamt += $s['len'];
      $s = (int)$z['schuesse'];
      if ((int)$z['treffer'] >= $gesamt) {
        return ['Ganze Flotte versenkt mit ' . $s . ' Schuss.',
                $s <= VERSENKEN_PAR ? 'Das war Zielwasser statt Bier. Volle Punktzahl.'
                                    : 'Mit ' . ($s - VERSENKEN_PAR) . ' Schuss weniger wären es 100 Punkte gewesen.'];
      }
      $uebrig = [];
      foreach ($z['schiffe'] as $sch) if ($sch['hits'] < $sch['len']) $uebrig[] = $sch['n'];
      return ['Munition alle. ' . (int)$z['treffer'] . ' von ' . $gesamt . ' Feldern getroffen.',
              'Übrig: ' . implode(', ', $uebrig) . '. Die fahren jetzt ohne dich.'];
  }
  return [];
}

/* --- Neuen Lauf anlegen -------------------------------------------------- */
function zustandAnlegen(string $spiel, string $modus): array {
  global $F_VOKABELN, $F_KOMMANDO, $F_ANKER, $F_FLOTTE;
  switch ($spiel) {
    case 'vokabeln': {
      $idx = array_keys($F_VOKABELN);
      if ($modus === 'wertung') {
        $idx = array_values(array_filter($idx, fn($i) => $F_VOKABELN[$i]['w'] === 1));
      } else { shuffle($idx); $idx = array_slice($idx, 0, VOKABELN_N); }
      return ['reihe' => $idx, 'pos' => 0, 'richtig' => 0, 'zeit' => 0.0, 'start' => jetzt(), 'opt' => []];
    }
    case 'kommando': {
      $idx = array_keys($F_KOMMANDO);
      if ($modus === 'wertung') {
        $idx = array_values(array_filter($idx, fn($i) => $F_KOMMANDO[$i]['w'] === 1));
      } else { shuffle($idx); $idx = array_slice($idx, 0, KOMMANDO_N); }
      return ['reihe' => $idx, 'pos' => 0, 'richtig' => 0, 'start' => jetzt(), 'opt' => []];
    }
    case 'mob':
      return ['pos' => 0, 'richtig' => 0, 'opt' => []];
    case 'anker':
      return ['reihe' => array_keys($F_ANKER), 'pos' => 0, 'richtig' => 0];
    case 'versenken': {
      $n = VERSENKEN_N;
      for ($versuch = 0; $versuch < 300; $versuch++) {
        $belegt = array_fill(0, $n * $n, -1);
        $schiffe = []; $ok = true;
        foreach ($F_FLOTTE as $si => $s) {
          $gesetzt = false;
          for ($t = 0; $t < 500 && !$gesetzt; $t++) {
            $waag = random_int(0, 1) === 1;
            $r = random_int(0, $waag ? $n - 1 : $n - $s['len']);
            $c = random_int(0, $waag ? $n - $s['len'] : $n - 1);
            $felder = [];
            for ($k = 0; $k < $s['len']; $k++) $felder[] = $waag ? $r * $n + $c + $k : ($r + $k) * $n + $c;
            $frei = true;
            foreach ($felder as $f) {
              $fr = intdiv($f, $n); $fc = $f % $n;
              for ($dr = -1; $dr <= 1 && $frei; $dr++) for ($dc = -1; $dc <= 1; $dc++) {
                $rr = $fr + $dr; $cc = $fc + $dc;
                if ($rr < 0 || $cc < 0 || $rr >= $n || $cc >= $n) continue;
                if ($belegt[$rr * $n + $cc] !== -1) { $frei = false; break; }
              }
            }
            if (!$frei) continue;
            foreach ($felder as $f) $belegt[$f] = $si;
            $schiffe[] = ['n' => $s['n'], 'len' => $s['len'], 'cells' => $felder, 'hits' => 0];
            $gesetzt = true;
          }
          if (!$gesetzt) { $ok = false; break; }
        }
        if ($ok) return ['schiffe' => $schiffe, 'felder' => [], 'schuesse' => 0, 'treffer' => 0];
      }
      throw new RuntimeException('Flotte lässt sich nicht verteilen');
    }
  }
  throw new InvalidArgumentException('unbekannte Prüfung');
}

/* --- Mischung der Antworten festlegen und Frageuhr starten -------------- */
function schrittVorbereiten(array &$l): void {
  global $F_VOKABELN, $F_KOMMANDO, $F_MOB;
  $z = &$l['zustand'];
  $anzahl = null;
  switch ($l['spiel']) {
    case 'vokabeln': $anzahl = count($F_VOKABELN[$z['reihe'][$z['pos']]]['o']); break;
    case 'kommando': $anzahl = count($F_KOMMANDO[$z['reihe'][$z['pos']]]['o']); break;
    case 'mob':      $anzahl = count($F_MOB[$z['pos']]['opts']); break;
  }
  if ($anzahl !== null) { $idx = range(0, $anzahl - 1); shuffle($idx); $z['opt'] = $idx; }
  $z['frageStart'] = jetzt();
  unset($z);
}

/* --- Der aktuelle Schritt, so wie ihn der Browser sehen darf ------------- */
function schritt(array $l): array {
  global $F_VOKABELN, $F_KOMMANDO, $F_MOB, $F_ANKER;
  $z = $l['zustand'];
  $basis = ['spiel' => $l['spiel'], 'modus' => $l['modus']];
  $misch = fn(array $texte) => array_map(fn($i) => $texte[$i], $z['opt']);

  switch ($l['spiel']) {
    case 'vokabeln': {
      $f = $F_VOKABELN[$z['reihe'][$z['pos']]];
      return $basis + ['typ' => 'mc', 'nr' => $z['pos'] + 1, 'von' => count($z['reihe']),
        'frage' => $f['q'], 'optionen' => $misch($f['o']),
        'grafik' => $f['mark'] ? ['art' => 'boot', 'mark' => $f['mark']] : null,
        'zeitStand' => (int)round((float)$z['zeit'])];
    }
    case 'kommando': {
      $f = $F_KOMMANDO[$z['reihe'][$z['pos']]];
      return $basis + ['typ' => 'mc', 'nr' => $z['pos'] + 1, 'von' => count($z['reihe']),
        'frage' => $f['q'], 'optionen' => $misch($f['o']), 'grafik' => null, 'uhr' => KOMMANDO_UHR];
    }
    case 'mob': {
      $f = $F_MOB[$z['pos']];
      return $basis + ['typ' => 'mc', 'nr' => $z['pos'] + 1, 'von' => count($F_MOB),
        'frage' => $f['q'], 'optionen' => $misch(array_map(fn($o) => $o['t'], $f['opts'])), 'grafik' => null];
    }
    case 'anker':
      return $basis + ['typ' => 'anker', 'nr' => $z['pos'] + 1, 'von' => count($z['reihe']),
        'bucht' => $z['reihe'][$z['pos']]];
    case 'versenken': {
      $gesamt = 0; foreach ($z['schiffe'] as $s) $gesamt += $s['len'];
      return $basis + ['typ' => 'grid', 'n' => VERSENKEN_N, 'budget' => VERSENKEN_BUDGET,
        'schuesse' => $z['schuesse'], 'treffer' => $z['treffer'], 'gesamt' => $gesamt,
        'felder' => (object)$z['felder'],
        'flotte' => array_map(fn($s) => ['n' => $s['n'], 'len' => $s['len'], 'hits' => $s['hits'],
                                         'dead' => $s['hits'] >= $s['len']], $z['schiffe'])];
    }
  }
  return $basis;
}

/* --- Antwort verarbeiten ------------------------------------------------ */
function antwortVerarbeiten(PDO $db, array &$l, array $ein): array {
  $db->beginTransaction();
  try {
    $r = antwortIntern($db, $l, $ein);
    $db->commit();
    return $r;
  } catch (Throwable $e) {
    if ($db->inTransaction()) $db->rollBack();
    throw $e;
  }
}

function antwortIntern(PDO $db, array &$l, array $ein): array {
  global $F_VOKABELN, $F_KOMMANDO, $F_MOB, $F_ANKER;
  $z = &$l['zustand'];
  $spiel = $l['spiel'];
  $rueck = [];

  if ($spiel === 'versenken') {
    $feld = (int)($ein['feld'] ?? -1);
    $n = VERSENKEN_N;
    if ($feld < 0 || $feld >= $n * $n || isset($z['felder'][(string)$feld])) {
      unset($z); return ['feedback' => null, 'weiter' => schritt($l)];
    }
    $z['schuesse']++;
    $treffer = false; $versenkt = null;
    foreach ($z['schiffe'] as $i => $s) {
      if (in_array($feld, $s['cells'], true)) {
        $treffer = true; $z['treffer']++;
        $z['schiffe'][$i]['hits']++;
        $z['felder'][(string)$feld] = 'treffer';
        if ($z['schiffe'][$i]['hits'] >= $s['len']) {
          foreach ($s['cells'] as $c) $z['felder'][(string)$c] = 'versenkt';
          $versenkt = ['n' => $s['n'], 'cells' => $s['cells']];
        }
        break;
      }
    }
    if (!$treffer) $z['felder'][(string)$feld] = 'wasser';
    $gesamt = 0; foreach ($z['schiffe'] as $s) $gesamt += $s['len'];
    $fertig = ($z['treffer'] >= $gesamt) || ($z['schuesse'] >= VERSENKEN_BUDGET);
    $rueck['feedback'] = ['treffer' => $treffer, 'versenkt' => $versenkt];
    unset($z);
    $l['punkte'] = punkteBerechnen($l, $fertig);
    if ($fertig) { $rueck['ergebnis'] = laufAbschliessen($db, $l, true); $rueck['weiter'] = null; }
    else { laufSpeichern($db, $l); $rueck['weiter'] = schritt($l); }
    return $rueck;
  }

  if ($spiel === 'anker') {
    $id = $z['reihe'][$z['pos']];
    $daten = $F_ANKER[$id];
    $wahl = strtoupper(trim((string)($ein['wahl'] ?? '')));
    if (!isset($daten['why'][$wahl])) { unset($z); return ['feedback' => null, 'weiter' => schritt($l)]; }
    $richtig = ($wahl === $daten['richtig']);
    if ($richtig) $z['richtig']++;
    $text = $daten['why'][$wahl];
    if (!$richtig) {
      $text .= ' Richtig wäre ' . $daten['richtig'] . ': ' . str_replace('Richtig. ', '', $daten['why'][$daten['richtig']]);
    }
    $rueck['feedback'] = ['richtig' => $richtig, 'text' => $text, 'loesung' => $daten['richtig']];
    $z['pos']++;
    $ende = $z['pos'] >= count($z['reihe']);
    unset($z);
    $l['punkte'] = punkteBerechnen($l, $ende);
    if ($ende) { $rueck['ergebnis'] = laufAbschliessen($db, $l, true); $rueck['weiter'] = null; }
    else { laufSpeichern($db, $l); $rueck['weiter'] = schritt($l); }
    return $rueck;
  }

  /* --- Multiple Choice: vokabeln, kommando, mob --- */
  $wahlAnzeige = (int)($ein['wahl'] ?? -1);
  $original = ($wahlAnzeige >= 0 && isset($z['opt'][$wahlAnzeige])) ? $z['opt'][$wahlAnzeige] : -1;
  $dauer = jetzt() - (float)($z['frageStart'] ?? jetzt());

  if ($spiel === 'vokabeln') {
    $f = $F_VOKABELN[$z['reihe'][$z['pos']]];
    $z['zeit'] = (float)$z['zeit'] + $dauer;
    $richtigIdx = array_search($f['a'], $f['o'], true);
    $ok = ($original === $richtigIdx);
    $text = $f['fb'];
  } elseif ($spiel === 'kommando') {
    $f = $F_KOMMANDO[$z['reihe'][$z['pos']]];
    $richtigIdx = array_search($f['a'], $f['o'], true);
    $zuSpaet = $dauer > (KOMMANDO_UHR + NACHSICHT);
    $ok = (!$zuSpaet && $original === $richtigIdx);
    $text = ($zuSpaet || $original < 0 ? 'Zeit abgelaufen. An Deck hätte das jetzt gescheppert. ' : '') . $f['fb'];
  } else {
    $f = $F_MOB[$z['pos']];
    $richtigIdx = (int)$f['richtig'];
    $ok = ($original === $richtigIdx);
    $text = $original >= 0 ? $f['opts'][$original]['fb'] : $f['opts'][$richtigIdx]['fb'];
  }
  if ($ok) $z['richtig']++;
  $loesungAnzeige = array_search($richtigIdx, $z['opt'], true);
  $rueck['feedback'] = ['richtig' => $ok, 'text' => $text, 'loesungIndex' => $loesungAnzeige];

  $z['pos']++;
  $anzahl = ($spiel === 'mob') ? count($F_MOB) : count($z['reihe']);
  $ende = $z['pos'] >= $anzahl;
  unset($z);
  $l['punkte'] = punkteBerechnen($l, $ende);
  if ($ende) { $rueck['ergebnis'] = laufAbschliessen($db, $l, true); $rueck['weiter'] = null; }
  else { schrittVorbereiten($l); laufSpeichern($db, $l); $rueck['weiter'] = schritt($l); }
  return $rueck;
}
