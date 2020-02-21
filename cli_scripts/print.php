<?php
/**
 * print.php
 * 
 * Datei zum Herunterladen der Druckaufträge und zur Vorbereitung der Ausgabe ebenjener.
 */

/**
 * Einbinden der Konfigurationsdatei
 */
require_once(__DIR__.DIRECTORY_SEPARATOR."config.php");

/**
 * Laden des Druckertreibers
 */
require_once($driver);

/**
 * cURL initialisieren
*/
$ch = curl_init();

/**
 * Verbindungsoptionen vorbereiten
 * @see https://www.php.net/manual/de/function.curl-setopt.php
 */
$options = array(
  CURLOPT_RETURNTRANSFER => TRUE,
  CURLOPT_URL => $exportURL,
  CURLOPT_USERAGENT => $userAgent,
  CURLOPT_INTERFACE => $bindTo,
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_TIMEOUT => 10
);

/**
 * Das Optionsarray in den cURL-Handle einfügen
 */
curl_setopt_array($ch, $options);

/**
 * Ausführen des cURLs und speichern der Antwort, sowie eventuell
 * anfallender Fehler.
 */
$response = curl_exec($ch);
$errno = curl_errno($ch);
$errstr = curl_error($ch);
if($errno != 0) {
  die("cURL - errno: ".$errno." - errstr: ".$errstr." - url: ".$exportURL."\n");
}

/**
 * Auswerten des HTTP-Codes.
 */
$http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
if($http_code != 200) {
  die("cURL - httpcode: ".$http_code." - url: ".$exportURL."\n");
}

/**
 * Beenden des cURL-Handles
 */
curl_close($ch);

/**
 * Umwandeln des JSON-Strings aus der Antwort in ein assoziatives Array.
 */
$response = json_decode($response, TRUE);

/**
 * Prüfung ob Druckaufträge vorhanden sind.
 * Falls keine Druckaufträge vorliegen wird abgebrochen.
 */
if(empty($response['prints'])) {
  die();
}

foreach($response['prints'] as $key => $val) {
  if($val['publish'] !== TRUE) {
    echo "################################################\n";
    printer_center();
    echo printer_text("- NICHT VERÖFFENTLICHEN -")."\n";
    printer_left();
    echo "################################################\n";
    printer_feed(2);
  }
  echo printer_text("von: ".($val['printName'] === TRUE ? $val['username'] : "<anonym>"))."\n";
  echo "am: ".date("d.m.Y, H:i:s", $val['time'])."\n";
  echo "------------------------------------------------\n";
  printer_feed(1);
  foreach(explode("\n", $val['text']) as $innerkey => $innerval) {
    echo printer_text($innerval)."\n";
  }
  printer_feed(2);
  if($val['publish'] !== TRUE) {
    echo "################################################\n";
    printer_center();
    echo printer_text("- NICHT VERÖFFENTLICHEN -")."\n";
    printer_left();
    echo "################################################\n";
    printer_feed(2);
  }
  if($val['printName'] !== TRUE) {
    printer_cut();
    echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^\n";
    echo "Vorstehender Text von:\n";
    echo printer_text($val['username'])."\n";
    echo "^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^\n";
    printer_feed(2);
  }
  printer_cut();
}
?>
