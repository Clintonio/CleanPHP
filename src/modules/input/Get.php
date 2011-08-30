<?php
/**
* A class to abstract out some pains in using get values
*
* @author	Clinton Alexander
*/
class Get {
	/**
	* Get a get value with an optional default
	*
	* @param	String		Name of post field
	* @param	Mixed		Default value
	* @return	Mixed		Default value or get field (if exists). 
	*						String types are converted to String objects
	*/
	public static function value($index, $default = false) {
		if(isset($_GET[$index])) {
			return new String($_GET[$index]);
		} else {
			// Not in either, return default
			if(is_string($default)) {
				return new String($default);	
			} else {
				return $default;
			}
		}
	}	
}
?>