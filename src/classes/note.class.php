<?php
/**
* Note class to handle all things note 
*/
class Note {
	/*
	* Unique identifier of the note, used in the public URL to access the note.
	*/
	public $UID;
	
	/*
	* Title of the note.
	*/
	public $title;
	
	/*
	* Content of the note, this value should be always in plain text (i.e., not formatted in any way)
	*/
	public $content;
	
	/*
	* This value is required to edit the note, generated automatically when the note is created
	* This only contains a hash of the key. Do not store the key value here
	*/
	public $pkey;
	
	/*
	* (Beta) Determines if the note should be listed publicly
	*/
	public $private;
	
	/*
	*	The user id of the creator of the note
	*	Default is -1 (Anonymous)
	*/
	public $author_id = -1;
	
	/* The pdo link to be used in the queries */
	private $pdo;
	
	/* Error returned when the key passed to save_changes() is invalid */
	const E_INVALID_PKEY = "Error: invalid private key";
	
	/* Contains information about an sql error */
	
	public $sql_error_info;
	
	function __construct($pdo, $UID = NULL) {
		$this->pdo = $pdo;
		
		if($UID) {
			$this->fetch_data($UID);
		}
	}
	
	/*
	* Gets the note data from the database using the UID, returns false if the note is not found, true if it exists
	*/
	public function fetch_data($UID = NULL) {
		$param = [($UID == NULL ? $this->UID : $UID)]; //This is an array containing the UID parameter for the query, if the function argument is null it uses the object's uid
		
		$query = "SELECT * FROM notes WHERE UID = ?";
		
		$handle = new Query($this->pdo, $query);
		$handle->exec($param);
		
		if(!($result = $handle->fetch())) {
			return false;
		}
		$this->_copy($result);
		
		return true;
	}
	
	/*
	* Creates an uid and a key for the note and stores it into the db
	* Returns the note's private key.
	*/
	public function create($author_id = -1) {
		$this->gen_uniq_id();
		$pkey_hash = $this->gen_priv_key();		
		
		$this->author_id = $author_id;
		
		$params = $this->members_to_array("UID", "title", "content", "private", "pkey", "author_id");
		$query = "INSERT INTO notes (UID, title, content, private, pkey, author_id) VALUES (?, ?, ?, ?, ?, ?)";
		
		$handle = new Query($this->pdo, $query);
		if(!($handle->exec($params))) {
			$this->query_error_info = $handle->error_info;
		}
		
		return $pkey_hash;
	}
	
	
	/*
	* Saves the changes made to the note, requires the key to be passed as an argument
	*/
	public function save_changes($key) {	
		
		if(password_verify($key, $this->pkey)) {
			
			$query = "UPDATE notes SET title = ?, content = ?, private = ? WHERE UID = ? AND pkey = ?";
			$handle = new Query($this->pdo, $query);
			$params = $this->members_to_array("title", "content", "private", "UID", "pkey");
			
			$handle->exec($params);
			$this->query_error_info = $handle->error_info;
			
			return $handle->success;
		}//Else:
		
		return self::E_INVALID_PKEY;
		
	}
	
	/**
	* Deletes the note entry from the database
	*/
	public function delete($key) {
		
		if(!isset($this->pkey)) {
			$this->fetch_data();
		}
		
		if(password_verify($key, $this->pkey)) {
			$query = "DELETE FROM notes WHERE UID = ?";
			$handle = new Query($this->pdo, $query);
			$params = $this->members_to_array("UID");//I'm lazy
			
			$handle->exec($params);
			
			return $handle->success;
		}
		
		return self::E_INVALID_PKEY;
	}
	
	/*
	* Generates an unique identifier for the note
	*/
	private function gen_uniq_id() {
		$bytes = openssl_random_pseudo_bytes(16);
		$hash = sha1($bytes);
		
		$this->UID = $hash;

	}
	
	/*
	* Generates a private key for the note. Returns the key value, stores the key hash
	*/
	private function gen_priv_key() {
		$bytes = openssl_random_pseudo_bytes(16);
		$key = bin2hex($bytes);
		
		$hash = password_hash($key, PASSWORD_DEFAULT);
		
		$this->pkey = $hash;
		
		return $key;
	}
	
	/*
	* Copies the values of an associative array into the objects matching members
	*/
	private function _copy($array) {
		foreach($array as $key=>$val) {
			if(property_exists($this, $key)) {
				$this->$key = $val;
			}
		}
	}
	
	/*
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