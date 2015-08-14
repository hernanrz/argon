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
			set_status(403, "Missing auth token header");
		}else {
			if(($auth_key_header = explode(":", $_SERVER["HTTP_X_AUTH_KEY"])) && count($auth_key_header) == 2) {
				
				list($username, $auth_key) = $auth_key_header;
				$user = new User($pdo_link, $auth_key);
				$user->username = $username;
				
				if($user->auth()) {
					$session->user = $user;
					$response["session_token"] = $session->grant_token();
				}else {
					$response["Debugging"] = [$user->auth_key, $auth_key, $user->auth()];
					set_status(403, "Could not authenticate user");
				}
				
			}else {
				set_status(403, "Invalid auth key");
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
				set_status(200, "Session token deleted");
			} else {
				set_status(403, "Could not authorize the user");
			}
		}else {
			set_status(403, "Missing parameters");
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
				set_status(403, "Invalid token/username combination");
			}
		}else {
			set_status(403, "Missing parameters");
		}
	break;
	
	default:
		set_status(501, "Method not supported");
	break;
}

?>