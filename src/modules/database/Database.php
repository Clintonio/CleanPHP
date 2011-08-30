<?php
/**
* Abstract database representation
*
* @author	Clinton Alexander
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
	* @param	String		Input data (Not a query statement)
	* @return	String		Clean input data
	*/
	function clean(String $string);
}