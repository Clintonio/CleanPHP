<?php
/**
* Class designated for handling PHP errors. Either a custom error handler
* can be given to an instance of this class, or the class can statically handle 
* errors and throw ErrorExceptions upon each PHP error.
* 
* @author	Clinton Alexander
*/

/**
* An error handler class for handling both PHP exceptions and custom ones
*
* @package	error
*/
class ErrorHandler {
	/**
	* The callback that is called upon errors 
	*/
	private $callback;
	/**
	* The level of errors that this handler handles
	*/
	private $level		= NULL;
	
	/**
	* Create this error handler with a custom callback to call when errors
	* are generated at the given level
	*
	* @param	callback	callback	Error handler callback
	* @param	int			level		Level to handle for (uses E_ALL | E_STRICT by default)
	*/
	public function __construct($callback, $level = NULL) {
		$this->callback = $callback;
	}
	
	/**
	* Set level to use
	*
	* @param	int		level	Level to handle for
	*/
	public function setLevel($level) {
		$this->level = $level;	
	}
	
	/**
	* Start using the callback given at construction to handle errors at the
	* level given at construction
	*/
	public function handleErrors() {
		if($level === NULL) {
			$level = E_ALL | E_STRICT;	
		}
		set_error_handler($this->callback, $level);
	}
	
	//=============
	// Static methods
	//=============
	
	/**
	* Default error exception handler
	*
	* @param	int		errno	The serverity of the error
	* @param	string	errstr	The error string to log or display
	* @param	string	errfile	The file in which the error occurred
	* @param	int		errline	The line on which the error occurred
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
