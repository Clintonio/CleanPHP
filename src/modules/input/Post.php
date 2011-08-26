<?php
/**
* A class to abstract out some pains in using post values
*
* @author	Clinton Alexander
*/
class Post {
	/**
	* Get a post value with an optional default
	*
	* @param	String		Name of post field
	* @param	Mixed		Default value
	* @return	Mixed		Default value or post field (if exists)
	*/
	public static function value($index, $default = false) {
		if(isset($_POST[$index])) {
			return $_POST[$index];
		} else {
			// Not in either, return default
			return $default;
		}
	}
}
?>