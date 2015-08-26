<?php
/*
* Argon REST API v1 Alpha
* This is not the final version of the API and there will be many more changes in the following updates
* Right now im not sure about the whole workflow of the script so it is likely to be updated as soon as i figure a better way
* that allows quick development of new api endpoints
* It determines what method is being requested and parses the parameters needed
*/

header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

require "../core.php";

$method = $_SERVER["REQUEST_METHOD"];
$path = explode("/",$_GET["path"]);

$endpoint = array_shift($path);

$valid_endpoints = [
	'note',
	'session',
	'user'
];

$response = [
	'status' => 'OK',
	'code' => 200
];

/**
* Set the http status code for the response and optionally a message containing more information
*/
function set_status($code, $msg = false) {
	global $response;

	$response["status"] = ($msg ? $msg : $response["status"]);
	$response["code"] = $code;
	http_response_code($code);
}


/**
* Checks if the parameters were given on the POST request
*/

function expect_parameters(...$params) {
	foreach($params as $param){
		if(!isset($_POST[$param])) {
			return false;
		}
	}
	return true;
}

switch($method) {

	case "POST":
		$data = $_POST;
	break;

	case "PUT":
		$data = [];
		parse_str(file_get_contents("php://input"), $data);
	break;

	default:
		$data = NULL;
	break;
}

if(in_array($endpoint, $valid_endpoints)) {
	include "endpoints/" . $endpoint . ".php";
}else {
	set_status(404, $STR["endpoint_404"]);
}

$json = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if(isset($_GET["callback"])){
	$json = $_GET["callback"] . "({$json});";
}

print $json;

exit;
?>