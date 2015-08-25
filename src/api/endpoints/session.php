<?php
/**
*	Stateless session handler endpoint
*/

$session = new Session($pdo_link);

switch($method) {
	//Generate a new token for a user
	//Requires the auth key and username to be passed through the X-Auth-Key http header
	//X-Auth-Key is a string of the form username:auth_key
	case "POST":
		if(!isset($_SERVER["HTTP_X_AUTH_KEY"])) {
			set_status(403, $STR["missing_atoken_header"]);
		}else {
			if(($auth_key_header = explode(":", $_SERVER["HTTP_X_AUTH_KEY"])) && count($auth_key_header) == 2) {

				list($username, $password) = $auth_key_header;
				$user = new User($pdo_link);
				$user->username = $username;
				$user->password = $password;

				if($user->auth()) {
					$session->user = $user;
					$response["session_token"] = $session->grant_token();
				}else {
					set_status(403, $STR["couldnt_auth_user"]);
				}

			}else {
				set_status(403, $STR["invalid_auth_key"]);
			}

		}
	break;

	//Delete a given token
	//Requires the username to be present in the path
	//Path must be in this way session/username/session_token
	case "DELETE":
		if((count($path) > 2) && list($username, $token) = array_slice($path, 0, 2)) {
			$session->token = $token;
			if($session->authorize($username)) {
				$session->destroy();
				set_status(200, $STR["sess_token_deleted"]);
			} else {
				set_status(403, $STR["couldnt_authorize"]);
			}
		}else {
			set_status(403, $STR["missing_parameters"]);
		}
	break;

	//
	//
	case "GET":
		if((count($path) > 2) && list($username, $token) = array_slice($path, 0, 2)) {
			$session->token = $token;
			if($session->authorize($username)) {
				$user_data = [
					'user_id' => $session->user->user_id,
					'username' => $session->user->username
				];
				$response["user_data"] = $user_data;
			}else {
				set_status(403, $STR["invalid_userkey_combo"]);
			}
		}else {
			set_status(403, $STR["missing_parameters"]);
		}
	break;

	default:
		set_status(501, $STR["unsupported_method"]);
	break;
}

?>