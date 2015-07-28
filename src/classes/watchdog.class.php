<?php
/*
*	Watchdog
*	Simple spam protection 
*/

class Watchdog {
	private function __construct() {}
	/* 60 */
	/* Set maximum rates allowed before "blacklisting" */	
	private static $note_edit_rate = 150;
	private static $note_posting_rate = 90;
	
	/** Generate a hash based on the client ip address */
	public static function client_id() {
		$client_ip = strrev($_SERVER["REMOTE_ADDR"]);
		
		return sha1($client_ip);
	}
	
	
};
?>