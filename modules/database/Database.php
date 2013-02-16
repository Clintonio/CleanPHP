<?php
/**
* An interface for abstract database representation
*
* @author	Clinton Alexander
* @version	1
*/

/**
* Abstract database representation
*
* @package	database
*/
interface Database {
	
	/**
	* Gets numeric ordered query (no field names)
	*
	* @throws	DatabaseQueryException	When a query fails
	* @param	String		Query to be sent
	* @return	Numeric array of results
	*/
	function getQueryNumeric($query);
	
	/**
	* Gets an associative query
	*
	* @throws	DatabaseQueryException	When a query fails
	* @param	String		Query to be sent
	* @return	Array of results
	*/
	function getQuery($query);
	
	/**
	* Prepares, then executes, a query and returns the results as a numeric array
	* of results which contain associative data fields. Arguments for the prepared statement
	* can either be given as an array in the second parameter or as n parameters
	*
	* @throws	DatabaseQueryException	When a query fails
	* @param	String		Query to be sent
	* @param	param1		array of parameters or N individual parameters
	* @return	Array of results
	*/
	function getPreparedQuery($query, $param1 = array());
	
	/**
	* Prepares, then executes, a query and returns the results as a numeric array
	* of results which contain numeric data fields. Arguments for the prepared statement
	* can either be given as an array in the second parameter or as n parameters.
	* Requires PHP 5.3 or higher and mysqlnd.
	*
	* @throws	DatabaseQueryException	When a query fails
	* @param	string	query		Query to be sent
	* @param	mixed	param1		array of parameters or N individual parameters
	* @return	array	Array of results
	*/
	function getPreparedQueryNumeric($query, $param1 = array());
	
	/**
	* Send a query with a boolean response such as INSERT
	*
	* @throws	DatabaseQueryException	When a query fails
	* @param	String		Query to be sent
	* @return	True or false depending on result of query
	*/
	function sendQuery($query);
	
	/**
	* Get the number of affected rows
	*
	* @return	Number of affected rows
	*/
	function getAffectedRows();
	
	/**
	* Get the ID of the last insert
	*
	* @return	Last insert ID
	*/
	function getInsertID();
	
	/**
	* Closes the connection
	*
	* @return	void
	*/
	function closeConnection();
	
	/**
	* Clean the input data value to prevent it causing
	* injections 
	*
	* @param	string		Input data (Not a query statement)
	* @return	String		Clean input data
	*/
	function clean($string);
}
