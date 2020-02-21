<?php
/**
 * logout.php
 * 
 * Seite zum Löschen der Sitzung und um den Cookie zu leeren.
 */

/**
 * Sessionüberprüfung
 */
require_once('sessioncheck.php');

/**
 * Titel
 */
$title = "Logout";
$content.= "<h1 class='center'>Logout</h1>".PHP_EOL;

if(!isset($_POST['submit'])) {
  /**
   * Formular wird angezeigt
   */
  $content.= "<form action='/logout' method='post'>".PHP_EOL;
  /**
   * Sitzungstoken
   */
  $content.= "<input type='hidden' name='token' value='".$sessionhash."'>".PHP_EOL;
  /**
   * Auswahl
   */
  $content.= "<div class='row bordered center'>".PHP_EOL.
  "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Möchtest du dich ausloggen?</div>".PHP_EOL.
  "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><input type='submit' name='submit' value='Ja'></div>".PHP_EOL.
  "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><a href='/print'>Nein, zurück.</a></div>".PHP_EOL.
  "<div class='col-x-12 col-s-12 col-m-0 col-l-0 col-xl-0'><div class='spacer-s'></div></div>".PHP_EOL.
  "</div>".PHP_EOL;
  $content.= "</form>".PHP_EOL;
} else {
  /**
   * Formular abgesendet
   */
  /**
   * Sitzungstoken
   */
  if($_POST['token'] != $sessionhash) {
    http_response_code(403);
    $content.= "<div class='warnbox'>Ungültiges Token.</div>".PHP_EOL;
    $content.= "<div class='row'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><a href='/print'>Zurück zum Druck</a></div>".PHP_EOL.
    "</div>".PHP_EOL;
  } else {
    /**
     * Löschen der Sitzung.
     */
    mysqli_query($dbl, "DELETE FROM `sessions` WHERE `sessionhash`='".$match[0]."'") OR DIE(MYSQLI_ERROR($dbl));
    /**
     * Entfernen des Cookies und Umleitung zur Loginseite.
     */
    setcookie('bondrucker', NULL, 0);
    header("Location: /start");
    die();
  }
}
?>
