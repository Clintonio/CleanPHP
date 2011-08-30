<?php
/**
* A class representing session data.
*
* @author	Clinton Alexander
*/
class Session {
	/**
	* Current user's IP
	*/
	private static $ip;
	
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
		
		self::$ip = new String(getenv('REMOTE_ADDR'));
		
		self::updateToken();
		
	}
	
	/**
	* Get IP of current session
	*
	* @return	String		IP
	*/
	public static function getIP() {
		return self::$ip;
	}
	
	/**
	* Get the session token
	*
	* @return	String		Session token
	*/
	public static function getToken() {
		return new String($_SESSION[self::TOKEN_INDEX]);
	}
	
	/**
	* Get the session expire time
	*
	* @return	int			Session expire timestamp
	*/
	public static function getTokenExpireTime() {
		return $_SESSION[self::TOKEN_EXPIRE_INDEX];
	}
	
	/**
	* Update the user's session token
	*/
	private static function updateToken() {
		if((!isset($_SESSION[self::TOKEN_EXPIRE_INDEX])) || (!isset($_SESSION[self::TOKEN_INDEX]))
			|| ($_SESSION[self::TOKEN_EXPIRE_INDEX] < time())) {
			$_SESSION[self::TOKEN_INDEX] 			= uniqid();
		}
		
		$_SESSION[self::TOKEN_EXPIRE_INDEX] 	= time() + self::SESSION_TOKEN_LIFESPAN;
	}
	
	/**
	* Check if the given token is valid
	*
	* @param	String		Value to check against
	*/
	public static function isTokenValid(String $token) {
		return ($token->equals(self::getToken()));
	}
}
?>