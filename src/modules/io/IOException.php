<?php
/**
* Thrown upon an IO exception occuring
*
* @author	Clinton Alexander
*/
class IOException extends Exception {
	/**
	* Create an IOException with the given message and optional cause
	*
	* @param	String		Message
	* @param	Exception	(Optional) Cause
	*/
	public function __construct(String $message, Exception $cause = NULL) {
		parent::__construct((string) $message, 0, $cause);	
	}
}
?>