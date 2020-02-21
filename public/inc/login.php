<?php
/**
 * login.php
 * 
 * Setzt den Session Cookie und leitet auf pr0gramm weiter
 */

/**
 * Es wird der State Parameter für die oAuth Anfrage erzeugt
 * und in einem Cookie gespeichert. Danach erfolgt die Umleitung.
 */
$state = md5(random_bytes(4096));
setcookie("state", $state, time()+3600);
header("Location: ".$authURL."/".$state);
die();
?>
