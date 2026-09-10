<?php
/* Ruf diese Seite einmal im Browser auf, um zu sehen, ob der Webspace passt.
   Danach kannst du die Datei löschen. */
header('Content-Type: text/html; charset=utf-8');
$dbOrdner = __DIR__ . '/daten';
if (!is_dir($dbOrdner)) @mkdir($dbOrdner, 0775, true);

$tests = [
  'PHP-Version 8.0 oder neuer' => [version_compare(PHP_VERSION, '8.0', '>='), PHP_VERSION],
  'SQLite-Datenbank verfügbar' => [in_array('sqlite', PDO::getAvailableDrivers(), true), implode(', ', PDO::getAvailableDrivers())],
  'Ordner "daten" beschreibbar' => [is_dir($dbOrdner) && is_writable($dbOrdner), $dbOrdner],
  'config.php vorhanden' => [is_file(__DIR__ . '/config.php'), ''],
  'index.html vorhanden' => [is_file(__DIR__ . '/index.html'), ''],
  'Verschlüsselte Verbindung (https)' => [!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'sonst beim Hoster einschalten'],
];
$schluesselGeaendert = false;
if (is_file(__DIR__ . '/config.php')) {
  include __DIR__ . '/config.php';
  $schluesselGeaendert = isset($ADMIN_KEY) && $ADMIN_KEY !== 'bitte-hier-etwas-eigenes-eintragen';
}
$tests['Verwaltungsschlüssel geändert'] = [$schluesselGeaendert, 'in config.php, Punkt 4'];

echo '<!doctype html><meta charset="utf-8"><title>Prüfung</title>';
echo '<body style="font-family:system-ui;max-width:640px;margin:40px auto;line-height:1.6">';
echo '<h1>Törn-Briefing: Prüfung des Webspace</h1><ul style="list-style:none;padding:0">';
$allesGut = true;
foreach ($tests as $name => [$ok, $info]) {
  if (!$ok) $allesGut = false;
  printf('<li style="padding:6px 0;border-bottom:1px solid #eee">%s <strong>%s</strong>%s</li>',
    $ok ? '✅' : '❌', htmlspecialchars($name),
    $info ? ' <span style="color:#777">— ' . htmlspecialchars((string)$info) . '</span>' : '');
}
echo '</ul>';
echo $allesGut
  ? '<p style="background:#e6f7ec;padding:14px">Alles bereit. Ruf jetzt <a href="index.html">index.html</a> auf und melde dich an. Diese Datei kannst du löschen.</p>'
  : '<p style="background:#fdecea;padding:14px">Ein Punkt fehlt noch. Rote Zeilen zuerst beheben, dann Seite neu laden.</p>';
