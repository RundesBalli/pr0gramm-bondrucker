# :incoming_envelope: :page_with_curl: pr0gramm-bondrucker
Kleine Spielerei um Usern das Drucken auf meinem Bondrucker zu ermöglichen.

## Abhängigkeiten
[pr0gramm-apiCall](https://github.com/RundesBalli/pr0gramm-apiCall)  
[escpos-php-driver](https://github.com/RundesBalli/escpos-php-driver)

## Einrichtung
Die Datei `cli_scripts/config.template.php` muss in `cli_scripts/config.php` und die Datei `public/inc/config.template.php` muss in `public/inc/config.php` umbenannt werden.  
Die darin enthaltenen Variablen sind dokumentiert und müssen entsprechend der Kommentare ausgefüllt werden.

## Druck
Auf dem lokalen Computer, der im selben Netzwerk wie der Drucker steht, wird die Datei `cli_scripts/print.php` mittels folgendem Befehl ausgeführt und an den Drucker übergeben:  
$ `php print.php | nc ip.of.your.printer 9100 -w 1`

