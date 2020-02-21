<?php
/**
 * start.php
 * 
 * Startseite
 */

/**
 * Titel
 */
$title = "Einloggen";
$content.= "<h1 class='center'>Einloggen</h1>".PHP_EOL;

/**
 * Prüfung ob ein Cookie gesetzt ist und ob es leer ist.
 * Wenn ein Cookie gesetzt ist, dann wird auf die Print-Seite weitergeleitet,
 * falls nicht wird der Login über pr0gramm angeboten.
 */
if(!isset($_COOKIE['bondrucker']) OR empty($_COOKIE['bondrucker'])) {
  $content.= "<div class='infobox center'>Auf dieser Seite werden Cookies verwendet!</div>".PHP_EOL;
  $content.= "<div class='row center'>".PHP_EOL.
  "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12 hover'><a href='/login'>mit pr0gramm anmelden</a></div>".PHP_EOL.
  "</div>".PHP_EOL;
  $content.= "<div class='spacer-l'></div>".PHP_EOL;
} else {
  header("Location: /print");
  die();
}
?>
