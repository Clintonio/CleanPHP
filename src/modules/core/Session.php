<?php
/**
* A class representing session data.
*
* @author	Clinton Alexander
*/
class Session {
	
	/**
	* Called when this class is loaded by CleanPHP
	* And begins the session
	*/
	public static function onLoad() {
		if(!isset($_SESSION)) {
			session_start();	
		}
	}
}
?>