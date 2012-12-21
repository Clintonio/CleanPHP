<?php
/**
* Thrown upon an IO exception occuring
*
* @author	Clinton Alexander
*/

/**
* IOException that represents an exception caused by IO interruption
*
* @package	io
*/
class IOException extends Exception {
	/**
	* Create an IOException with the given message and optional cause
	*
	* @param	string		message		Message
	* @param	Exception	case		(Optional) Cause
	*/
	public function __construct($message, Exception $cause = NULL) {
		parent::__construct((string) $message, 0, $cause);	
	}
}
?>
