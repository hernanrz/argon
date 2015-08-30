#!/usr/bin/php
<?php
/**
*	Generates an .sql file containing the database structure
*/
require "../src/settings/database.php";

$output_name = "argon.sql";
$cwd = __DIR__; 

echo "Generating sql dump file...\n";

exec("mysqldump -d -u $db_user -h $db_host --password=$db_password $db_name > $cwd/$output_name");

echo "Dump file generated.\n";
echo $cwd."/".$output_name."\n";

?>