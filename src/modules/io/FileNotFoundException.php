<?php

CleanPHP::import('io.IOException');

/**
* File not found exception
*
* @author	Clinton Alexander
*/
class FileNotFoundException extends IOException { 
	/**
	* Create a new class not found exception for the given class and message
	*
	* @param	String		Message for developer
	* @param	String		File that was missing
	*/
	public function __construct($message, $file = NULL) {
		if($file != NULL) {
			$message = 'Could not load file: ' . $file . '. ' . $message;
		}
		
		parent::__construct($message, 0);
	}
}

?>