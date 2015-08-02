#!/usr/bin/php
<?php
/**
*	Executes argon.sql, creating the mysql tables for the app
*/

if(!file_exists("../src/settings/database.php")) {
	require "setup_db.php";
}

require "../src/settings/database.php";

exec("mysql -u $db_user --password=$db_password -h $db_host $db_name < argon.sql");

echo "Tables created\n";

?>