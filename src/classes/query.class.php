<?php 
class Query {
	
	/* The query string to be executed, note this class uses prepared queries*/
	public $query;
	
	/* Set to true or false depending on whether the query was executed successfully or not. */
	public $success;
	
	/* Contains a string with details of the error in case the query wasn't successful */
	public $error_info;
	
	/* PDO link passed to the constructor */
	private $pdo;
	
	/* PDOStatement object used withing the class */
	private $pdo_statement;
	
	function __construct($pdo_link, $query = "") {
		$this->query = $query;
		$this->pdo = $pdo_link;
	}
	
	/*
	* Prepares the query then executes it.
	*/
	public function exec($input = NULL) {
		$this->prepare();
		if($this->success = $this->pdo_statement->execute($input)){
			return $this->success;
		}else {
			$this->errorInfo = $this->pdo_statement->errorInfo();
		}
	}
	
	/*
	* Calls prepare from pdo and stores the PDOStatement object
	*/
	private function prepare($query = NULL) {
		$query = ($query == NULL ? $this->query : $query);
		
		$this->pdo_statement = $this->pdo->prepare($query);
		
		return $this;
	}
	
	/*
	* Returns an associative array with the query results, or an error if it fails to do the former
	*/
	public function fetch($style = PDO::FETCH_ASSOC){
			//check for empty resultset
		if($this->pdo_statement->rowCount() < 1) {
			return false;
		}
		
		if ($result = $this->pdo_statement->fetch($style)) {
			return $result;
		}else {
			$this->errorInfo = $this->pdo_statement->errorInfo();
			return $this->success = false;
		}
	}
};
?>