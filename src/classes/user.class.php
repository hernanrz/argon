<?php
/**
* Handles the creation of new users, modifies existing user's info, deletes users, and also authenticates the user
*/
class User {
	
	public $user_id;
	
	public $username;
	/*
	*	Used for authentication, this is not stored in the database
	*/
	public $password;
	/*
	* sha1 hash of username:password
	*/
	public $auth_key;
	
	private $pdo;
	private $query_handle;
	public $authenticated = false;
	
	const E_USERNAME_EXISTS = 0x420;
	
	function __construct($pdo, $auth_key = false) {
		$this->pdo = $pdo;
		$this->query_handle = new Query($this->pdo);
		
		if($auth_key) {
			$this->auth_key = $auth_key;
		}
	}
	
	/**
	*	Generates the auth_key hash based on the current username and password
	*/
	public function gen_auth_key() {
		$hash = sha1($this->username . ":" . $this->password);
		
		return $this->auth_key = $hash;
	}
	
	/**
	*	Creates a new user
	*	(!) Assumes password and username are not empty
	*/
	public function create() {
		$this->gen_auth_key();
		
		if($this->username_exists()) {
			return self::E_USERNAME_EXISTS;
		}
		
		$query = "INSERT INTO users (username, auth_key) VALUES (?, ?)";
		$params = [
			$this->username,
			$this->auth_key	
		];
		
		$this->query_handle->query = $query;
		
		$success = $this->query_handle->exec($params);
		
		return $success;
	}
	
	/**
	*	Generates an auth_key and then checks the database to see if there are any matches
	*	Returns true if it finds a result, false if it does not
	*/
	public function auth() {
		$this->gen_auth_key();
		
		$query = "SELECT * FROM users WHERE username = ? AND auth_key = ?";
		$params = [
			$this->username,
			$this->auth_key
		];
		
		$this->query_handle->query = $query;
		$this->query_handle->exec($params);
		
		$result = $this->query_handle->fetch();
		
		if($result === false){
			return false;
		}
		
		$this->user_id = $result["ID"];
		return $this->authenticated = true;
	}
	
	/**
	*	Modifies the user's data, requires the password and username, regardless of which one is being changed
	*/
	public function modify($current_username, $current_password) {
		
		if(($current_username !== $this->username) && $this->username_exists()){
			return self::E_USERNAME_EXISTS;
		}
		
		$current_auth_key = sha1($current_username . ":" . $current_password);
		$this->gen_auth_key();
		
		$query = "UPDATE users SET username = ?, auth_key = ? WHERE auth_key = ? AND username = ?";
		$this->query_handle->query = $query;
		
		$params = [
			$this->username,
			$this->auth_key,
			$current_auth_key,
			$current_username
		];
		
		return $this->query_handle->exec($params);
	}
	
	/**
	* Deletes current user from the database
	*/
	public function delete() {
		if(!$this->authenticated && !$this->auth()) {
			return false;
		}

		$this->query_handle->query = "DELETE FROM users WHERE ID = ?";
		$param = [$this->user_id];
		
		if($this->query_handle->exec($param)) {
			$this->user_id = null;
			return true;
		}
		return false;		
	}
	
	/**
	* Fetches user's data using the user id
	*/
	public function fetch_with_id($user_id = false) {
		if($user_id === false) {
			$user_id = $this->user_id;
		}
		
		$this->query_handle->query = "SELECT * FROM users WHERE ID = ?";
		$this->query_handle->exec([$user_id]);
	
		
		if ($result = $this->query_handle->fetch()) {
			$this->username = $result["username"];
			$this->auth_key = $result["auth_key"];
			
			return true;
		}
		
		return false;
	}
	
	/**
	*	Checks if the username is already in the database
	*/
	private function username_exists() {
		
		$query = "SELECT * FROM users WHERE username = ?";
		$param = [$this->username];
	
		$this->query_handle->query = $query;
		
		$this->query_handle->exec($param);
		
		if($this->query_handle->fetch() && $this->query_handle->success) {
			
			return true;
			
		}//else {
			
			return false;

		//}
	} 
	
	/**
	* Puts the object's members' value inside an array, useful when building queries
	*/
	private function members_to_array() {
		$args = func_get_args();
		$values = [];
		
		foreach ($args as $member) {
			$values[] = $this->$member;
		}
		
		return $values;
	}
};	
?>