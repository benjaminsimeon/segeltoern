<?php
/* =========================================================================
   Törn-Briefing — Server
   Nimmt Anmeldungen entgegen, speichert Ergebnisse und gibt die Auflösung
   erst frei, wenn alle fünf Prüfungen bestanden sind.
   ========================================================================= */
declare(strict_types=1);
require __DIR__ . '/config.php';
require __DIR__ . '/spiel.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

session_set_cookie_params([
  'lifetime' => 60 * 60 * 24 * 120,
  'path'     => '/',
  'httponly' => true,
  'samesite' => 'Lax',
  'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
]);
session_name('toern');
session_start();

function raus(array $daten, int $code = 200): never {
  http_response_code($code);
  echo json_encode($daten, JSON_UNESCAPED_UNICODE);
  exit;
}
function eingabe(): array {
  $roh = file_get_contents('php://input');
  if ($roh === '' || $roh === false) return [];
  $d = json_decode($roh, true);
  return is_array($d) ? $d : [];
}

/* --- Datenbank ---------------------------------------------------------- */
function db(): PDO {
  global $DB_PFAD;
  static $pdo = null;
  if ($pdo !== null) return $pdo;
  $ordner = dirname($DB_PFAD);
  if (!is_dir($ordner)) @mkdir($ordner, 0775, true);
  $pdo = new PDO('sqlite:' . $DB_PFAD, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
  // Bis zu 8 Sekunden auf eine gesperrte Datei warten, statt sofort
  // aufzugeben. Das ist der entscheidende Schalter bei gleichzeitigem Zugriff.
  $pdo->exec('PRAGMA busy_timeout=8000');
  // WAL erlaubt Lesen während geschrieben wird. Auf manchen Webspaces mit
  // Netzlaufwerk geht das nicht, dann bleibt es beim Normalbetrieb.
  try { $pdo->exec('PRAGMA journal_mode=WAL'); } catch (Throwable $e) { /* egal */ }
  $pdo->exec('PRAGMA synchronous=NORMAL');
  $pdo->exec('CREATE TABLE IF NOT EXISTS ergebnisse (
      nutzer TEXT NOT NULL,
      spiel  TEXT NOT NULL,
      punkte INTEGER NOT NULL,
      zeit   INTEGER NOT NULL,
      PRIMARY KEY (nutzer, spiel))');
  tabellen($pdo);
  return $pdo;
}
function punkteVon(string $nutzer): array {
  $st = db()->prepare('SELECT spiel, punkte FROM ergebnisse WHERE nutzer = ?');
  $st->execute([$nutzer]);
  $out = [];
  foreach ($st as $z) $out[$z['spiel']] = (int)$z['punkte'];
  return $out;
}
function allePunkte(): array {
  $out = [];
  foreach (db()->query('SELECT nutzer, spiel, punkte FROM ergebnisse') as $z) {
    $out[$z['nutzer']][$z['spiel']] = (int)$z['punkte'];
  }
  return $out;
}

/* --- Zustand für den Browser -------------------------------------------
   Wichtig: Buchstaben werden NUR für bestandene Prüfungen mitgeschickt.
   Die restlichen bleiben auf dem Server.                                 */
function zustand(): array {
  global $CREW, $GAMES;
  $ich = $_SESSION['nutzer'] ?? null;
  if ($ich === null || !isset($CREW[$ich])) return ['ok' => false, 'grund' => 'nicht angemeldet'];

  $alle    = allePunkte();
  $meine   = $alle[$ich] ?? [];
  $letters = [];
  foreach ($GAMES as $spiel => $buchstabe) {
    if (isset($meine[$spiel])) $letters[$spiel] = $buchstabe;
  }
  $tafel = [];
  foreach ($CREW as $u => $c) {
    $p = $alle[$u] ?? [];
    $tafel[] = [
      'user'   => $u,
      'name'   => $c['name'],
      'total'  => array_sum($p),
      'done'   => array_map(fn($s) => isset($p[$s]), array_keys($GAMES)),
      'anzahl' => count($p),
    ];
  }
  usort($tafel, fn($a, $b) => [$b['total'], $b['anzahl'], $a['name']] <=> [$a['total'], $a['anzahl'], $b['name']]);

  return [
    'ok'      => true,
    'me'      => ['user' => $ich, 'name' => $CREW[$ich]['name']],
    'games'   => array_keys($GAMES),
    'scores'  => (object)$meine,
    'letters' => (object)$letters,
    'allDone' => count($meine) === count($GAMES),
    'board'   => $tafel,
  ];
}

/* --- Aktionen ------------------------------------------------------------ */
$a = $_GET['a'] ?? '';

if ($a === 'login') {
  $d = eingabe();
  $u = strtolower(trim((string)($d['user'] ?? '')));
  $p = (string)($d['pass'] ?? '');
  usleep(250000); // bremst stures Durchprobieren aus
  if (!isset($CREW[$u]) || !hash_equals($CREW[$u]['pass'], $p)) {
    raus(['ok' => false, 'grund' => 'Falscher Name oder falsches Kennwort. Nochmal, mit Gefühl.'], 401);
  }
  session_regenerate_id(true);
  $_SESSION['nutzer'] = $u;
  raus(zustand());
}

if ($a === 'logout') {
  $_SESSION = [];
  session_destroy();
  raus(['ok' => true]);
}

if ($a === 'state') {
  $ich = $_SESSION['nutzer'] ?? null;
  $verfallen = ($ich !== null && isset($CREW[$ich])) ? verfallenLassen(db(), $ich) : null;
  $z = zustand();
  if ($verfallen) $z['verfallen'] = $verfallen;
  raus($z, $z['ok'] ? 200 : 401);
}

/* --- Prüfung starten ----------------------------------------------------
   Ein noch offener Durchgang verfällt dabei mit dem bis dahin
   erreichten Stand. Das ist die verabredete Regel.                      */
if ($a === 'start') {
  $ich = $_SESSION['nutzer'] ?? null;
  if ($ich === null || !isset($CREW[$ich])) raus(['ok' => false, 'grund' => 'nicht angemeldet'], 401);

  $d     = eingabe();
  $spiel = (string)($d['game'] ?? '');
  $modus = ($d['modus'] ?? 'wertung') === 'uebung' ? 'uebung' : 'wertung';
  if (!isset($GAMES[$spiel])) raus(['ok' => false, 'grund' => 'unbekannte Prüfung'], 400);

  $verfallen = verfallenLassen(db(), $ich);

  $meine = punkteVon($ich);
  if ($modus === 'wertung') {
    if (isset($meine[$spiel])) raus(['ok' => false, 'grund' => 'Diese Prüfung ist bereits gewertet.', 'state' => zustand(), 'verfallen' => $verfallen], 409);
    $reihe = array_keys($GAMES);
    $pos   = array_search($spiel, $reihe, true);
    if ($pos > 0 && !isset($meine[$reihe[$pos - 1]])) {
      raus(['ok' => false, 'grund' => 'Erst die Prüfung davor.', 'state' => zustand(), 'verfallen' => $verfallen], 409);
    }
  } else {
    if (!isset($meine[$spiel])) raus(['ok' => false, 'grund' => 'Üben geht erst nach dem Wertungslauf.', 'state' => zustand()], 409);
  }

  $lauf = ['nutzer' => $ich, 'spiel' => $spiel, 'modus' => $modus, 'status' => 'offen',
           'zustand' => zustandAnlegen($spiel, $modus), 'punkte' => 0];
  schrittVorbereiten($lauf);
  $st = db()->prepare('INSERT INTO laeufe (nutzer, spiel, modus, status, zustand, punkte, start, aktiv) VALUES (?,?,?,?,?,?,?,?)');
  $st->execute([$ich, $spiel, $modus, 'offen', json_encode($lauf['zustand'], JSON_UNESCAPED_UNICODE), 0, time(), time()]);
  $lauf['id'] = (int)db()->lastInsertId();
  raus(['ok' => true, 'schritt' => schritt($lauf), 'verfallen' => $verfallen, 'state' => zustand()]);
}

/* --- Antwort abgeben ----------------------------------------------------- */
if ($a === 'antwort') {
  $ich = $_SESSION['nutzer'] ?? null;
  if ($ich === null || !isset($CREW[$ich])) raus(['ok' => false, 'grund' => 'nicht angemeldet'], 401);
  $lauf = offenerLauf(db(), $ich);
  if (!$lauf) raus(['ok' => false, 'grund' => 'Kein offener Durchgang.', 'state' => zustand()], 409);

  $ergebnis = antwortVerarbeiten(db(), $lauf, eingabe());
  $antwort  = ['ok' => true, 'feedback' => $ergebnis['feedback'] ?? null, 'weiter' => $ergebnis['weiter'] ?? null];
  if (isset($ergebnis['ergebnis'])) {
    $antwort['ergebnis'] = $ergebnis['ergebnis'];
    $antwort['state']    = zustand();
  }
  raus($antwort);
}

/* --- Durchgang abbrechen: zählt mit dem bisherigen Stand ----------------- */
if ($a === 'abbruch') {
  $ich = $_SESSION['nutzer'] ?? null;
  if ($ich === null || !isset($CREW[$ich])) raus(['ok' => false, 'grund' => 'nicht angemeldet'], 401);
  $verfallen = verfallenLassen(db(), $ich);
  raus(['ok' => true, 'verfallen' => $verfallen, 'state' => zustand()]);
}

if ($a === 'reveal') {
  $ich = $_SESSION['nutzer'] ?? null;
  if ($ich === null || !isset($CREW[$ich])) raus(['ok' => false, 'grund' => 'nicht angemeldet'], 401);
  $meine = punkteVon($ich);
  if (count($meine) < count($GAMES)) {
    raus(['ok' => false, 'grund' => 'Erst alle fünf Prüfungen.'], 403);
  }
  raus(['ok' => true, 'hafen' => $HAFEN]);
}

if ($a === 'reset') {
  $d   = eingabe();
  $key = (string)($d['key'] ?? ($_GET['key'] ?? ''));
  if (!hash_equals($ADMIN_KEY, $key) || $ADMIN_KEY === 'bitte-hier-etwas-eigenes-eintragen') {
    raus(['ok' => false, 'grund' => 'falscher Schlüssel'], 403);
  }
  $wer = strtolower(trim((string)($d['user'] ?? ($_GET['user'] ?? ''))));
  if ($wer !== '') {
    $st = db()->prepare('DELETE FROM ergebnisse WHERE nutzer = ?');
    $st->execute([$wer]);
    $st = db()->prepare('DELETE FROM laeufe WHERE nutzer = ?');
    $st->execute([$wer]);
    raus(['ok' => true, 'geloescht' => $wer]);
  }
  db()->exec('DELETE FROM ergebnisse');
  db()->exec('DELETE FROM laeufe');
  raus(['ok' => true, 'geloescht' => 'alle']);
}

raus(['ok' => false, 'grund' => 'unbekannte Aktion'], 404);
