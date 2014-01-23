#!/bin/sh
cd /Users/wonliao/Sites/wordpress/import/
/Applications/MAMP/bin/php/php5.3.6/bin/php /Users/wonliao/Sites/wordpress/import/reimport_7k7k.php > log.txt
cat /dev/null > /Applications/MAMP/logs/php_error.log

