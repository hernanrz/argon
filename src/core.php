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

/**
*	Attempt to load a user
*/
$SESSION_STARTED = false;

if(isset($_SERVER["HTTP_X_AUTH_KEY"]) && ($auth_key_header = explode(":", $_SERVER["HTTP_X_AUTH_KEY"])) && count($auth_key_header) == 2) {
	list($sess_username, $sess_password) = $auth_key_header;
	$CURRENT_USER = new User($pdo_link);
	$CURRENT_USER->username = $sess_username;
	$CURRENT_USER->password = $sess_password;
	
	if($CURRENT_USER->auth()) {
		$SESSION_STARTED = true;
	}
}

if(isset($_SERVER["HTTP_X_SESSION_TOKEN"])) {
	if(count($AR_SESSION_PAIR = explode(":", $_SERVER["HTTP_X_SESSION_TOKEN"])) == 2) {
	$AR_SESSION = new Session($pdo_link);
			list($sess_username, $token) = $AR_SESSION_PAIR;
			$AR_SESSION->token = $token;
			if($AR_SESSION->authorize($sess_username)) {
				$CURRENT_USER = $AR_SESSION->user;
				$SESSION_STARTED = true;
			}
	}
}
?>