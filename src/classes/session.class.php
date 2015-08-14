<?php
/**
*	Creates and destroys session tokens
*/

class Session {

	public $user;
	public $token;
	
	private $authorized = false;
	private $qhandle;
	private $pdo;

	public function __construct($pdo) {
		$this->pdo = $pdo;
		$this->qhandle = new Query($this->pdo);

	}
	
	/**
	*	Stores a token with the user id in the database
	*/
	public function grant_token() {
		//Make sure that the user exists
		if(!$this->user->authenticated && !$this->user->auth()) {
			return false;
		}
		
		$this->token = $this->gen_token();
		
		$this->qhandle->query = "INSERT INTO access_tokens (token, user_id) VALUES (?, ?)";
		$params = [
			$this->token,
			$this->user->user_id	
		];
		
		if($this->qhandle->exec($params)) {
			$this->authorized = true;
			return $this->token;
		}
		
		return $this->authorized = false;
	}
	
	/**
	*	Removes the current token from the database
	*/
	public function destroy() {
		
		$this->qhandle->query = "DELETE FROM access_tokens WHERE token = ?";
		
		$param = [$this->token];
		
		return $this->qhandle->exec($param);
	}
	
	/**
	*	Gets the user associated with the token	
	*/
	public function authorize($username) {
		
		$this->qhandle->query = "SELECT * FROM access_tokens WHERE token = ?";
		$param = [$this->token];
		
		if($this->qhandle->exec($param) && ($result = $this->qhandle->fetch())) {
			$user = new User($this->pdo);
			
			$user->user_id = $result["user_id"];
			$user->fetch_with_id();
			
			if($user->username === $username) {
				$this->user = $user;
				return $this->authorized = true;
			}
			
		}
		
		
		return $this->authorized = false;
		
	}
	
	/**
	*	Returns true if the user has been authorized with the current token
	*/
	public function is_authorized() {
		return $this->authorized;
	}
	
	
	/**
	*	Generates a pseudo-random token 
	*/
	private function gen_token() {
		
		$chars = array_merge(range("z","a"), range("Z", "A"), range("0", "9"));

		//Define the array length here so we dont have to call count() on each iteration
		$chars_len = count($chars)-1;
		$token_len = 10;
		
		$token = "";
		
		for($i = 0; $i<$token_len; $i++) {
			$token .= $chars[mt_rand(0, $chars_len)];
		}
		
		return $token;
	}
};
?>