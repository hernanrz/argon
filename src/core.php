<?php 
/**
* Argon - Server side script
*/

include "settings/settings.php";

$pdo_link = new PDO("mysql:dbname=$db_name;host=$db_host", $db_user, $db_password);

function autoloader($class_name) {
	include "classes/". strtolower($class_name) .".class.php";
}

spl_autoload_register('autoloader');


?>