<?php
/**
* File not found exception
*
* @author	Clinton Alexander
* @version	1
*/

CleanPHP::import('io.IOException');

/**
* Exception to throw when a file isn't found
*
* @package	io
*/
class FileNotFoundException extends IOException { 
	/**
	* Create a new class not found exception for the given class and message
	*
	* @param	string	message		Message for developer
	* @param	string	file		File that was missing
	*/
	public function __construct($message, $file = NULL) {
		if($file != NULL) {
			$message = 'Could not load file: ' . $file . '. ' . $message;
		}
		
		parent::__construct($message, 0);
	}
}

?>
