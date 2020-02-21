<?php
/**
 * config.php
 * 
 * Konfigurationsdatei
 */

/**
 * Speicherort des apiCalls.
 * Download: https://github.com/RundesBalli/pr0gramm-apiCall
 * Wird - sofern erforderlich - eingebunden.
 * 
 * Beispiel: /home/user/apiCall/apiCall.php
 * 
 * @var string
 */
$apiCall = "";

/**
 * authKey für export.php
 * 
 * Beispiel: 1234567890asdfghjkl
 * 
 * @var string
 */
$authKey = "";

/**
 * pr0-Auth
 * 
 * @var string $clientSecret
 * @var int    $clientId
 * @var string $authURL      Beispiel: https://pr0gramm.com/auth/test123
 */
$clientSecret = "";
$clientId = 0;
$authURL = "https://pr0gramm.com/auth/";

/**
 * MySQL-Zugangsdaten
 * 
 * @var string $mysql_host
 * @var string $mysql_user
 * @var string $mysql_pass
 * @var string $mysql_db
 */
$mysql_host = "localhost";
$mysql_user = "";
$mysql_pass = "";
$mysql_db = "";

/**
 * Datenbankverbindung
*/
$dbl = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db) OR DIE(MYSQLI_ERROR($dbl));
mysqli_set_charset($dbl, "utf8") OR DIE(MYSQLI_ERROR($dbl));

/**
 * Zeitzoneneinstellung
 */
date_default_timezone_set("Europe/Berlin");
?>
