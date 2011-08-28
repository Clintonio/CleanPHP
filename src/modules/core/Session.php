<?php
/**
* A class representing session data.
*
* @author	Clinton Alexander
*/
class Session {
	/**
	* Session token length in seconds
	*/
	const SESSION_TOKEN_LIFESPAN = 300;
	/**
	* Token field name
	*/
	const TOKEN_INDEX = 'cleanphp_token';
	/**
	* Expire time field name
	*/
	const TOKEN_EXPIRE_INDEX = 'cleanphp_sess_expire';
	
	/**
	* Called when this class is loaded by CleanPHP
	* And begins the session
	*/
	public static function onLoad() {
		if(!isset($_SESSION)) {
			session_start();	
		}
		
		$this->updateToken();
	}
	
	/**
	* Get the session token
	*
	* @return	String		Session token
	*/
	public static function getToken() {
		return new String($_SESSION[TOKEN_INDEX]);
	}
	
	/**
	* Get the session expire time
	*
	* @return	int			Session expire timestamp
	*/
	public static function getTokenExpireTime() {
		return $_SESSION[TOKEN_EXPIRE_INDEX];
	}
	
	/**
	* Update the user's session token
	*/
	private static function updateToken() {
		if((!isset($_SESSION[TOKEN_EXPIRE_INDEX])) || (!isset($_SESSION[TOKEN_INDEX]))
			|| ($_SESSION[TOKEN_EXPIRE_INDEX] < time())) {
			$_SESSION[TOKEN_INDEX] 			= uniqid();
		}
		
		$_SESSION[TOKEN_EXPIRE_INDEX] 	= time() + self::SESSION_TOKEN_LIFESPAN;
	}
	
	/**
	* Check if the given string is a valid session token
	*
	* @param	String		Value to check against
	*/
	public static function isValidToken(String $token) {
		return ($token.equals(self::getToken()));
	}
}
?>