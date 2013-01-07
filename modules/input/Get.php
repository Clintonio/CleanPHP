<?php
/**
* A class to abstract out some pains in using get values
*
* @author	Clinton Alexander
* @version	1
*/

/**
* Get representation and interface
*
* @package	input
*/
class Get {
	/**
	* Get a get value with an optional default
	*
	* @param	String		Name of post field
	* @param	Mixed		Default value
	* @return	Mixed		Default value or get field (if exists). 
	*						A single string will be converted to a string object
	*						arrays will be returned as expected
	*/
	public static function value($index, $default = false) {
		if(isset($_GET[$index])) {
			$value = $_GET[$index];
			if(is_array($value)) {
				return $value;
			} else {
				return new String($value);
			}
		} else {
			// Not in either, return default
			if(is_string($default)) {
				return new String($default);	
			} else {
				return $default;
			}
		}
	}	
	
	/**
	* Check if the get contains the given value
	*
	* @param	string		Name of get field
	* @return	bool		True if value is contained
	*/
	public static function contains($index) {
		return (isset($_GET[$index]));	
	}
}
?>
