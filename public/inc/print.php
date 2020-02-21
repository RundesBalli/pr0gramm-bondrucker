<?php
/**
 * print.php
 * 
 * Die eigentliche Hauptseite zum Eintragen von Druckaufträgen.
 */

/**
 * Titel
 */
$title = "Drucken";
$content.= "<h1 class='center'>Drucken</h1>".PHP_EOL;

/**
 * Sessionüberprüfung
 */
require_once('sessioncheck.php');

$content.= "<div class='row center'>".PHP_EOL.
"<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Hallo ".$username."! <span class='smaller'>(<a href='/logout'>Ausloggen</a>)</span></div>".PHP_EOL.
"</div>".PHP_EOL;
$content.= "<div class='spacer-m'></div>".PHP_EOL;

if($lastPrinted > (time()-3600)) {
  $content.= "<div class='infobox center'>Du kannst erst um ".date("H:i:s", $lastPrinted+3600)." wieder drucken.</div>";
  $content.= "<div class='spacer-m'></div>".PHP_EOL;
} else {
  if(!isset($_POST['submit'])) {
    /**
     * Formular wird angezeigt
     */
    $content.= "<form action='/print' method='post'>".PHP_EOL;
    /**
     * Sitzungstoken
     */
    $content.= "<input type='hidden' name='token' value='".$sessionhash."'>".PHP_EOL;
    /**
     * Formular
     */
    $content.= "<div class='row bordered center'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Text</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><textarea name='text' rows='20' cols='48' placeholder='max. 20 Zeilen, je 48 Zeichen breit!\nAlles darüber hinaus wird abgeschnitten bzw umgebrochen.\nLeerzeichen am Anfang und Ende jeder Zeile werden entfernt.'></textarea></div>".PHP_EOL.
    "</div>".PHP_EOL;
    $content.= "<div class='spacer-m'></div>";
    $content.= "<div class='row bordered center'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Mit Namen drucken?</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><input type='radio' name='printName' value='1' checked id='printName-yes'> <label for='printName-yes'>Ja</label><br><input type='radio' name='printName' value='0'id='printName-no'> <label for='printName-no'>Nein</label></div>".PHP_EOL.
    "</div>".PHP_EOL;
    $content.= "<div class='spacer-m'></div>";
    $content.= "<div class='row bordered center'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'>Darf der Ausdruck fotografiert und hochgeladen werden?</div>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><input type='radio' name='publish' value='1' checked id='publish-yes'> <label for='publish-yes'>Ja</label><br><input type='radio' name='publish' value='0'id='publish-no'> <label for='publish-no'>Nein</label></div>".PHP_EOL.
    "</div>".PHP_EOL;
    $content.= "<div class='spacer-m'></div>";
    $content.= "<div class='row bordered center'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><input type='submit' name='submit' value='drucken!'></div>".PHP_EOL.
    "</div>".PHP_EOL;
    $content.= "<div class='spacer-m'></div>";
    $content.= "</form>".PHP_EOL;
  } else {
    $text = preg_split('/[\r\n]+/', trim($_POST['text']));
    $textDb = array();
    foreach($text as $key => $val) {
      $textDb[] = wordwrap(trim($val), 48, "\n");
    }
    $text = defuse(implode("\n", array_slice($textDb, 0, 20)));
    if(empty($text)) {
      $content.= "<div class='warnbox center'>Du hast keinen Text eingetragen.</div>";
      $content.= "<div class='row center'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><a href='/print'>Neuer Versuch</a></div>".PHP_EOL.
      "</div>".PHP_EOL;
      $content.= "<div class='spacer-m'></div>".PHP_EOL;
    } else {
      require_once($apiCall);
      $banned = apiCall("https://pr0gramm.com/api/profile/info/?name=".$username)['user']['banned'];
      if($banned == 0) {
        mysqli_query($dbl, "INSERT INTO `prints` (`userId`, `text`, `printName`, `publish`) VALUES ('".$userId."', '".$text."', '".($_POST['printName'] == 1 ? 1 : 0)."', '".($_POST['publish'] == 1 ? 1 : 0)."')") OR DIE(MYSQLI_ERROR($dbl));
        mysqli_query($dbl, "UPDATE `users` SET `lastPrinted`=NOW() WHERE `id`='".$userId."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
        $content.= "<div class='successbox center'>Erfolgreich eingetragen.</div>";
      } else {
        $content.= "<div class='warnbox center'>Du kannst nichts eintragen, solange du auf pr0gramm gebannt bist.</div>";
        mysqli_query($dbl, "DELETE FROM `sessions` WHERE `sessionhash`='".$sessionhash."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
      }
      $content.= "<div class='row center'>".PHP_EOL.
      "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12'><a href='/print'>Zurück</a></div>".PHP_EOL.
      "</div>".PHP_EOL;
      $content.= "<div class='spacer-m'></div>".PHP_EOL;
    }
  }
}
?>
