<?php
/**
* Class designated for handling PHP errors
* 
* @author	Clinton Alexander
*/
class ErrorHandler {
	private $callback;
	private $level		= NULL;
	
	/**
	* Create this error handler
	*
	* @param	Callback	Error handler callback
	* @param	int			Level to handle for (uses E_ALL | E_STRICT by default)
	*/
	public function __construct($callback, $level = NULL) {
		$this->callback = $callback;
	}
	
	/**
	* Set level to use
	*
	* @param	int			Level to handle for
	*/
	public function setLevel($level) {
		$this->level = $level;	
	}
	
	/**
	* Set the error handler to this current one
	*
	* @param	Callback	Error handler callback
	* @param	int			Level of error to handle
	*/
	public function handleErrors() {
		if($level === NULL) {
			$level = E_ALL | E_STRICT;	
		}
		set_error_handler($handler, $level);
	}
	
	//=============
	// Static methods
	//=============
	
	/**
	* Default error exception handler
	*
	* @throws	ErrorException	Always throws error exception
	*/
	public static function handleException($errno, $errstr, $errfile, $errline ) {
    	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}
	
	/**
	* Emit inbuild ErrorExceptions upon all exceptions
	* This overwrites any current exception handler
	*/
	public static function throwExceptions() {
		set_error_handler('ErrorHandler::handleException');	
	}
	
	/**
	* Restore default PHP behaviour
	*/
	public static function reset() {
		restore_error_handler();
	}
}
?>