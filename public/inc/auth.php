<?php
/**
 * auth.php
 * 
 * Response Seite für die oAuth Schnittstelle
 */

/**
 * Titel
 */
$title = "Authentifizierung";
$content.= "<h1 class='center'>Authentifizierung</h1>".PHP_EOL;

/**
 * Fehler Abfangen
 */
if(isset($_GET['error'])) {
  $content.= "<div class='warnbox center'>Du hast den Login abgebrochen.</div>".PHP_EOL;
  $content.= "<div class='row center'>".PHP_EOL.
  "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12 hover'><a href='/login'>nochmal versuchen</a></div>".PHP_EOL.
  "</div>".PHP_EOL;
  $content.= "<div class='spacer-l'></div>".PHP_EOL;
} else {
  $error = 0;
  /**
   * authCode auf Richtigkeit überprüfen
   */
  if(preg_match("/^[0-9a-f]{32}$/i", defuse($_GET['authCode']), $match) === 1) {
    $authCode = $match[0];
  } else {
    $error = 1;
  }
  /**
   * userId auf Richtigkeit überprüfen
   */
  if(preg_match("/^[0-9a-f]{32}$/i", defuse($_GET['userId']), $match) === 1) {
    $userId = $match[0];
  } else {
    $error = 1;
  }
  /**
   * state auf Richtigkeit überprüfen
   */
  if(preg_match("/^[0-9a-f]{32}$/i", defuse($_GET['state']), $match) === 1) {
    $state = $match[0];
    /**
     * Prüfung ob der State Parameter vom auth mit dem Cookie übereinstimmt
     */
    if($_COOKIE['state'] != $state) {
      $error = 1;
    }
    /**
     * State Cookie entfernen
     */
    setcookie("state", NULL, 0);
  } else {
    $error = 1;
  }
  
  /**
   * Wenn ein Fehler aufgetreten ist, dann ist der Login fehlgeschlagen und ein Fehler wird ausgegeben.
   */
  if($error == 1) {
    $content.= "<div class='warnbox center'>Ein Fehler ist aufgetreten.</div>".PHP_EOL;
    $content.= "<div class='row center'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12 hover'><a href='/login'>nochmal versuchen</a></div>".PHP_EOL.
    "</div>".PHP_EOL;
    $content.= "<div class='spacer-l'></div>".PHP_EOL;
  } else {
    /**
     * Einbindung des apiCall
     */
    require_once($apiCall);

    /**
     * Wenn kein Fehler aufgetreten ist, wird mit den vorher überprüften Parametern das AuthToken angefragt.
     */
    $response = apiCall("https://pr0gramm.com/api/user/authtoken", array('authCode' => $authCode, 'userId' => $userId, 'clientId' => $clientId, 'clientSecret' => $clientSecret));
    $token = $response['accessToken'];
    
    /**
     * Mit dem AuthToken wird dann der Username angefragt.
     */
    $username = defuse(apiCall("https://pr0gramm.com/api/user/name", NULL, $token)['name']);
    
    /**
     * Prüfung ob der User sich schon einmal angemeldet hat.
     */
    $result = mysqli_query($dbl, "SELECT * FROM `users` WHERE `username`='".$username."' LIMIT 1") OR DIE(MYSQLI_ERROR($dbl));
    if(mysqli_num_rows($result) == 0) {
      /**
       * Neuanlage des Users, sofern nicht vorhanden.
       */
      mysqli_query($dbl, "INSERT INTO `users` (`username`) VALUES ('".$username."')") OR DIE(MYSQLI_ERROR($dbl));
      $userId = mysqli_insert_id($dbl);
    } else {
      /**
       * Abfrage der User-ID, wenn schon vorhanden.
       */
      $row = mysqli_fetch_array($result);
      $userId = $row['id'];
    }
    
    /**
     * Generierung der Sitzung
     */
    $sessionhash = hash("sha256", random_bytes(4096));
    setcookie("bondrucker", $sessionhash, time()+(86400*30));
    mysqli_query($dbl, "INSERT INTO `sessions` (`userId`, `sessionhash`) VALUES ('".$userId."', '".$sessionhash."')") OR DIE(MYSQLI_ERROR($dbl));

    /**
     * Meldung, dass die Sitzung angelegt wurde und weiterleitung auf die Druckseite.
     */
    $content.= "<div class='successbox center'>Login erfolgreich.</div>".PHP_EOL;
    $content.= "<div class='row center'>".PHP_EOL.
    "<div class='col-x-12 col-s-12 col-m-12 col-l-12 col-xl-12 hover'>Hallo ".$username."! <a href='/print'>Weiter zum Druck</a></div>".PHP_EOL.
    "</div>".PHP_EOL;
    $content.= "<div class='spacer-l'></div>".PHP_EOL;
  }
}
?>
