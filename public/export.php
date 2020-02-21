<?php
/**
 * export.php
 * 
 * Datei zum Abrufen der noch nicht gedruckten Druckaufträge.
 */

/**
 * Einbinden der Konfigurationsdatei sowie der Funktionsdatei
 */
require_once(__DIR__.DIRECTORY_SEPARATOR."inc".DIRECTORY_SEPARATOR."config.php");

/**
 * Initialisierung der Ausgabe
 */
header('Content-Type: application/json');
$output = array();
$output['error'] = NULL;
$output['errorMsg'] = NULL;

/**
 * Prüfung ob ein authKey übergeben wurde und ob dieser mit dem aus der config übereinstimmt.
 */
if((!isset($_GET['authKey']) OR empty($_GET['authKey'])) OR $_GET['authKey'] != $authKey) {
  http_response_code(403);
  $output['error'] = 403;
  $output['errorMsg'] = "Forbidden";
  die(json_encode($output));
}

/**
 * Vorbereiten der Ausgabe
 */
$result = mysqli_query($dbl, "SELECT `prints`.`id`, `users`.`username`, `prints`.`time`, `prints`.`text`, `prints`.`printName`, `prints`.`publish` FROM `prints` JOIN `users` ON `prints`.`userId` = `users`.`id` WHERE `prints`.`printed`='0' ORDER BY `prints`.`id` ASC") OR DIE(MYSQLI_ERROR($dbl));
$output['prints'] = array();
while($row = mysqli_fetch_array($result)) {
  $output['prints'][] = array('username' => $row['username'], 'time' => strtotime($row['time']), 'text' => $row['text'], 'printName' => ($row['printName'] == 1 ? TRUE : FALSE), 'publish' => ($row['publish'] == 1 ? TRUE : FALSE));
  mysqli_query($dbl, "UPDATE `prints` SET `printed`='1' WHERE `id` = '".$row['id']."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
}

/**
 * Ausgabe
 */
die(json_encode($output));
?>
