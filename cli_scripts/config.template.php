<?php
/**
 * config.php
 * 
 * Konfigurationsdatei
 */

/**
 * Speicherort des Druckertreibers.
 * Download: https://github.com/RundesBalli/escpos-php-driver
 * 
 * Beispiel: /home/user/escpos/driver.php
 * 
 * @var string
 */
$driver = "";

/**
 * URL zum Export der Seite
 * 
 * Beispiel: https://example.com/export.php?authKey=12345
 * 
 * @var string
 */
$exportURL = "";

/**
 * Der Useragent der gesendet wird.
 * 
 * Beispielwerte:
 * - Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0
 * - Heinrichs lustige Datenkrake
 * 
 * @var string
 */
$userAgent = "";

/**
 * Die IPv4-Adresse oder das Interface das für die ausgehende Verbindung genutzt werden soll.
 * Das Interface kann per Shell mit "sudo ifconfig" herausgefunden werden.
 * Wird das Script im Heimnetzwerk ausgeführt, so muss die interne Netzwerkadresse angegeben werden.
 * 
 * Beispielwerte:
 * - 1.2.3.4
 * - eth0
 * - 192.168.178.20 (nur lokaler PC / Heimnetzwerk)
 * 
 * @var string
 */
$bindTo = "";
?>
