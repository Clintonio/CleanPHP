<?php
/**
* A class to abstract out some pains in using post values
*
* @author	Clinton Alexander
* @version	1
*/

/**
* Post representation and interface
*
* @package	input
*/
class Post {
	/**
	* Get a post value with an optional default
	*
	* @param	string		Name of post field
	* @param	Mixed		Default value
	* @return	Mixed		Default value or post field (if exists). 
	*						A single string will be converted to a string object
	*						arrays will be returned as expected
	*/
	public static function value($index, $default = false) {
		if(isset($_POST[$index])) {
			$value = $_POST[$index];
			if(is_array($value)) {
				return new String($value);
			} else {
				return $value;
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
	* Check if the post contains the given value
	*
	* @param	string		Name of post field
	* @return	bool		True if value is contained
	*/
	public static function contains($index) {
		return (isset($_POST[$index]));	
	}
}
?>
