<?php
	include "../src/core.php";
	
	$username = "RottenLife";
	$token = "EIgzUkY0PJ";
	
	$sess = new Session($pdo_link);
	
	$sess->token =  $token;
	
	$sess->authorize($username);
	
	var_dump($sess);
?>