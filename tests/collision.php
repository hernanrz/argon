<?php
	/**
	* session token collision test script
	*/
	function gen_token() {
		
		$chars = array_merge(range("z","a"), range("Z", "A"), range("0", "9"));

		//Define the array length here so we dont have to call count() on each iteration
		$chars_len = count($chars)-1;
		$token_len = 5;
		
		$token = "";
		
		for($i = 0; $i<$token_len; $i++) {
			$token .= $chars[mt_rand(0, $chars_len)];
		}
		
		return $token;
	}
	
	$origin = gen_token();
	$i = 0;
	while(($current = gen_token()) !== $origin && $i<1000000){
		echo "Failed! at attempt #" . $i++ . "\n";
	}
	if($origin == $current) {
		print "Script terminated, success with $i attempts\n";
	}else {
		print "Script terminated, failed with $i attempts\n Origin: $origin \n Current: $current";
	}
?>