<?php
	/*
	* Testing a few simple queries to see if the Query wrapper class works properly
	*/
	include "../src/core.php";
	
	$query = new Query($pdo_link, "SELECT 2+2");
	print_r($query->exec() == true);
	
	$query->query = "CREATE TEMPORARY TABLE potatos (ID int PRIMARY KEY AUTO_INCREMENT, name varchar(20))";
	print_r($query->exec() == true);
	
	$testing = ['Juana', 'Maria', 'Pablo'];
	
	$query->query = "INSERT INTO potatos (name) VALUES (?)";
	
	foreach($testing as $name){
		print_r($query->exec([$name]) == true);
	}
	
	$query->query = "SELECT * FROM potatos";
	print_r($query->exec() == true);
	
	while($row = $query->fetch()){
		print_r(is_array($row) == true);
	}
	/*Output should look like this: 111111111*/
?>