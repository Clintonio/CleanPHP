<?php

CleanPHP::import("database.Database");
CleanPHP::import("database.DatabaseConnectionException");
CleanPHP::import("database.DatabaseQueryException");

/**
* Each database instance holds onto a single connection
* and acts as a wrapper to MySQLi intending to 
* abstract out the PHP mess
*
* @author		Clinton Alexander
* @version		3.0
*/

class MySQLiDatabase implements Database {
	private	static $queries 	= array();
	private	static $queryCount 	= 0;
	
	private $mySQLi 		= array();
	
	/** 
	* Create a new MySQLi database with the given connection details
	* 
	* @param	String		Username for database
	* @param	String		Password for database
	* @param	String		Database name
	* @param	String		Host of database
	*/
	public function __construct($username, $password, $dbname, $host = "localhost") {
		/* Add said DB to the arrray */
		$this->mySQLi = new MySQLi($dbArray["host"], 
									$dbArray["username"], 
									$dbArray["password"], 
									$dbArray["dbname"]);
	
		/* Check to see if the DB connection is working */
		if($this->mySQLi->connect_errno != 0) {
			throw new DatabaseConnectionException("Could not connect to database: " . $this->mySQLi->connect_error);
		}
	}
	
	//============================
	// Getter functions
	//============================
	
	/**
	* Database query
	* Sends a query. Does not return a result. Just true or false
	* Intended for non-select queries.
	* 
	* @throws	DatabaseQueryException	When the query is invalid
	* @param	String		SQL query to execute
	* @return	boolean		Result of query
	*/
	public function sendQuery($query) {
		self::$queries[] = $query;
		self::$queryCount++;
		
		$output = $this->mySQLi->query($query);
		// Checks for validity and returns true if the query was in any way
		// successful. 
		if($output === false) {
			// If 0 rows affected, return false
			if($this->mySQLi->affected_rows == 0) {
				return false;
			// 0 = no error
			} else if($this->mySQLi->errno != 0) {
				throw new DatabaseQueryException("Invalid query. Errorno: " . $this->mySQLi->errno .
												 ". Error text: " . $this->mySQLi->error . " for query . " . $query);
			}
			return false;
		} else if($output instanceof MySQLi_Result) {
			$output->free_result(); //Make no waste!	
			return true;
		} else if($this->mySQLi->affected_rows == 0) {
			return false;
		// 0 = no error
		} else {
			return true;
		}
	}
	
	/** 
	* Database Query
	* Returns all results as an associative array
	* 
	* @throws	DatabaseQueryException	When a query is invalid
	* @param	String		MySQL Query to execute
	* @param	Constant	MySQL Query type
	* @return	Array		Array of results or false
	*/
	public function getQuery($query) {
		return $this->getQueryInternal($query, true);
	}
	
	/**
	* Database query
	* Returns a numeric array
	*
	* @throws	DatabaseQueryException	When a query is invalid
	* @param	String		MySQL query to execute
	* @return	Array		Numeric array of results, or false
	*/
	public function getQueryNumeric($query) {
		return $this->getQueryInternal($query, false);
	}
	
	/** 
	* Database Query
	* Returns all results as an associative array
	* 
	* @throws	DatabaseQueryException	When a query is invalid
	* @param	String		MySQL Query to execute
	* @param	Constant	MySQL Query type
	* @return	Array		Array of results or false
	*/
	private function getQueryInternal($query, $assoc) {
		self::$queries[] = $query;
		self::$queryCount++;
		
		// Var Declarations
		$resultArray = array();
		
		if($assoc) {
			$type = MYSQLI_ASSOC;
		} else {
			$type = MYSQLI_NUM;	
		}
		
		// Checks for validity and assigns $output it's value;
		if(($output = $this->mySQLi->query($query)) === false) {
			throw new DatabaseQueryException("Database error: " . 
								   $this->mySQLi->error . 
								   " for query: " . $query, false);
			return false;
		} elseif ($output === true) {
			return true;
		}
		
		//This section will just strip the mysql object
		// down to an array, associative and numeric.
		$resultArray = $output->fetch_all($type);
		
		$output->free_result(); //Make no waste!
		
		if($resultArray !== NULL) {
			return $resultArray;
		} else {
			return array();
		}
	}
	
	/**
	* Returns the amount of affected rows of the last query
	*
	* @return	int		Count
	*/
	public function getAffectedRows() {
		// -1 indicates error, including "no query executed"
		if($this->mySQLi->affected_rows == -1) {
			return 0;	
		}
		
		return $this->mySQLi->affected_rows;
	}
	
	/**
	* Gets the last inputted ID
	* 
	* @return	int 	Last ID or NULL
	*/
	public function getInsertID() {
		if($this->mySQLi->insert_id == 0) {
			return NULL;
		}
		
		return $this->mySQLi->insert_id;
	}
	
	/**
	* Get the MySQLi object related to this database abstractor
	*
	* @return	Object related to this abstracted database
	*/
	public function getMySQLiObject() {
		return $this->mySQLi;	
	}
	
	//============================
	// Processors
	//============================
	
	/** 
	* Sanitises the given input
	* 
	* @param	String		Data to be sanitised
	* @return	String		Sanitised data
	*/
	public function clean($string) {
		return $this->mySQLi->real_escape_string($string);	
	}
	
	/**
	* Close the connection to the database
	*
	* @return	void
	*/
	public function closeConnection() {
		$this->mySQLi->close();
	}
}

?>