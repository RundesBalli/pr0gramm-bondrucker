<?php
/**
 * sessioncheck.php
 * 
 * Prüft ob ein gültiger Cookie gesetzt ist.
 */

if(isset($_COOKIE['bondrucker']) AND !empty($_COOKIE['bondrucker'])) {
  /**
   * Cookieinhalt entschärfen und prüfen ob Inhalt ein sha256-Hash ist.
   */
  $sessionhash = defuse($_COOKIE['bondrucker']);
  if(preg_match('/[a-f0-9]{64}/i', $sessionhash, $match) === 1) {
    /**
     * Abfrage in der Datenbank, ob eine Sitzung mit diesem Hash existiert.
     */
    $result = mysqli_query($dbl, "SELECT `users`.* FROM `sessions` JOIN `users` ON `users`.`id`=`sessions`.`userId` WHERE `sessionhash`='".$match[0]."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
    if(mysqli_num_rows($result) == 1) {
      /**
       * Wenn eine Sitzung existiert wird der letzte Nutzungszeitpunkt aktualisiert und der Username in die Variable $username geladen.
       */
      mysqli_query($dbl, "UPDATE `sessions` SET `lastActivity`=CURRENT_TIMESTAMP WHERE `sessionhash`='".$match[0]."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
      setcookie('bondrucker', $match[0], time()+(86400*30));
      $userrow = mysqli_fetch_array($result);
      $username = $userrow['username'];
      $userId = $userrow['id'];
      $lastPrinted = strtotime($userrow['lastPrinted']);
      $sessionhash = $match[0];
    } else {
      /**
       * Wenn keine Sitzung mit dem übergebenen Hash existiert wird der User durch Entfernen des Cookies und Umleitung zur Loginseite ausgeloggt.
       */
      setcookie('bondrucker', NULL, 0);
      header("Location: /start");
      die();
    }
  } else {
    /**
     * Wenn kein gültiger sha256 Hash übergeben wurde wird der User durch Entfernen des Cookies und Umleitung zur Loginseite ausgeloggt.
     */
    setcookie('bondrucker', NULL, 0);
    header("Location: /start");
    die();
  }
} else {
  /**
   * Wenn kein oder ein leerer Cookie übergeben wurde wird auf die Loginseite weitergeleitet.
   */
  header("Location: /start");
  die();
}
?>
