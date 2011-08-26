<?php
/**
* CleanPHP Core API
*
* @author		Clinton Alexander
* @version		2.0
*/

//============================
// SYSTEM REQUIREMENTS
//============================

// Since System is Rotatr's lifeblood and all scripts
// use it, requirements go in system

// We need to have a recent version
if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	die('<h1>Critical Error - Site cannot function</h1><br />PHP Version too low, current installed: ' . PHP_VERSION . '. 
		Please install PHP Version 5.3 or contact your system administrator');
}

/**
* Contains the most important functions and
* functionality for the framework to load and
* work
*/
class CleanPHP {
	
	/**
	* Location of CleanAPI modules
	*/
	private static $moduleFolder = "/modules/";
	
	/**
	* Loaded module list
	*/
	private static $loadedModules = array();
	
	/**
	* Module separator
	*/
	private static $moduleSeparator = ".";
		
	//============================
	// Class loaders
	//============================
	
	/**
	* Load a class from a CleanAPI module
	*
	* @throws	ClassNotFoundException	When a class was not found
	* @param	String		The class to load. Java style class name using dots to denote a package
	* @return	void
	*/
	public static function import($class) {
		if(!in_array($class, self::$loadedModules, true)) {
			$location = __DIR__. self::$moduleFolder . preg_replace("/\\" . self::$moduleSeparator . "/", "/", $class) . ".php";
			
			$className = explode(self::$moduleSeparator, $class);
			$className = end($className); 
			
			if(!include_once($location)) {
				throw new ClassNotFoundException($class, "No file found at " . $location);
			} else if((!class_exists($className)) && (!interface_exists($className))) {
				throw new ClassNotFoundException($class, "No class in file at " . $location);
			} else {
				self::$loadedModules[] = $class;	
			}
		}
	}
}

//=====
// Standard exceptions
//=====

/**
* Exception to be thrown when a class could not be found
*
* @author	Clinton Alexander
*/
class ClassNotFoundException extends RuntimeException {
	/**
	* Create a new class not found exception for the given class and message
	*
	* @param	String		Name of class not found
	* @param	String		Message for user
	*/
	public function __construct($class, $message) {
		$message = "Could not load class: " . $class . ". " . $message;
		parent::__construct($message, 0);
	}
}

/**
* Exception to be thrown when an something that
* should exist does not.Primarily items in the DB
* Or objects
*
* @author	Clinton Alexander
* @since 	2.0.0.0
* @version	1.0.0.0
*/
class MissingResourceException extends RuntimeException { }

/**
* Exception to be thrown when an something that
* should exist does not. Primarily array indexes 
* in associative arrays
*
* @author	Clinton Alexander
* @since 	2.0.0.0
* @version	1.0.0.0
*/
class MissingIndexException extends MissingResourceException { }

/**
* Resource update failure
* 
* @author	Clinton Alexandr
* @since	2.0.0.0
* @version	1.0.0.0
*/
class ResourceUpdateException extends RuntimeException { }

/**
* Permissions failure
* 
* @author	Clinton Alexandr
* @since	2.0.0.0
* @version	1.0.0.0
*/
class PermissionsException extends RuntimeException { }

/**
* Form validation exception
*
* @author	Clinton Alexandr
* @since	2.0.0.0
* @version	1.0.0.0
*/
class FormValidatorException extends InvalidArgumentException { }

/**
* Unexpected field exception
*
* @author	Clinton Alexandr
* @since	2.0.0.0
* @version	1.0.0.0
*/
class UnexpectedFieldException extends RuntimeException { }
?>