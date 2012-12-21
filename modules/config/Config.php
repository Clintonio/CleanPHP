<?php
/**
* Configuration class interface
*
* @author		Clinton Alexander
* @version		3.0
*/

CleanPHP::import("config.MissingConfigException");

interface Config {
	
	//============================
	// Constructor
	//============================
	
	/**
	* Loads a config file into the memory
	*
	* @throws	MissingConfigException	Configuration file is missing
	* @param	String		Location of config file
	*/
	function __construct($configLocation);
	
	//============================
	// Getters
	//============================
	
	/** 
	* Get a config value
	*
	* @throws	MissingConfigException	When a configuration is missing
	* @param	String		Config name
	* @return	String		Config Value
	*/
	function getConfig($name);
	
	//============================
	// Setters
	//============================
	
	/**
	* Set a config value and write to disk
	*
	* @param	String	Config name
	* @param	String	Config value
	* @return	void
	*/
	function setConfig($name, $value);
	
	/**
	* Set a config value and do not write to disk
	*
	* @param	String	Config name
	* @param	String	Config value
	* @return	void
	*/
	function setTempConfig($name, $value);
}

?>
