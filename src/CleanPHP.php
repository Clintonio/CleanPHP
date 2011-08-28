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

// Import some core modules
CleanPHP::import("core.String");
CleanPHP::import("core.Cookie");

/**
* Contains the most important functions and
* functionality for the framework to load and
* work
*/
class CleanPHP {
	
	/**
	* Location of CleanAPI main modules
	*/
	private static $moduleFolder = "/modules/";
	/**
	* User defined module folders
	*/
	private static $userModules = array();
	
	/**
	* Loaded module list
	*/
	private static $loadedModules = array();
	
	/**
	* Module separator
	*/
	private static $moduleSeparator = ".";
	
	/**
	* The name of the onload method for classes
	*/
	const ONLOAD_METHOD	= "onLoad";
		
	//============================
	// Class loaders
	//============================
	
	/**
	* Load a class from a CleanAPI module. If a static public method named "onLoad" is present
	* it will be automatically executed
	*
	* @throws	ClassNotFoundException	When a class was not found
	* @param	String		The class to load. Java style class name using dots to denote a package
	* @return	void
	*/
	public static function import($class) {
		if(!in_array($class, self::$loadedModules, true)) {
			$fileName  = preg_replace("/\\" . self::$moduleSeparator . "/", "/", $class) . ".php";
			// Get class name so we can check import status after inclusion
			$className = explode(self::$moduleSeparator, $class);
			$className = end($className); 
			// Attempt to include from base include folder
			$location  = __DIR__. self::$moduleFolder . $fileName;
			
			$imported  = false;
			if(file_exists($location)) {
				$imported  = (include_once($location));
			}
			
			// If it has not been found, check user module folders
			if(!$imported) {
				$x 		= 0;
				$size 	= count(self::$userModules);
				while(!$imported && ($x < $size)) {
					$location = self::$userModules[$x] . $fileName;
					
					if(file_exists($location)) {
						$imported = (include_once($location));
					}
					$x++;
				}
			}
			
			if(!$imported) {
				throw new ClassNotFoundException($class, "No class found in any module folder");
			} else if((!class_exists($className)) && (!interface_exists($className))) {
				throw new ClassNotFoundException($class, "No class in file at " . $location);
			} else {
				self::$loadedModules[] = $class;
				
				// Attempt to run the on load functionality
				if(method_exists($className, "onLoad")) {
					$rm = new ReflectionMethod($className, self::ONLOAD_METHOD);
					
					if(($rm->isStatic()) && ($rm->isPublic())) {
						$rm->invoke(NULL);
					}
				}
			}
		}
	}
		
	//============================
	// Adders
	//============================
	
	/**
	* Add a new module folder
	*
	* @throws	FileNotFoundException
	* @param	String		New module folder
	*/
	public static function addModulePath($folder) {
		if(is_dir($folder)) {
			self::$userModules[] = $folder;	
		} else {
			throw new FileNotFoundException("No such module folder");	
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
	* @param	String		Message for developer
	*/
	public function __construct($class, $message) {
		$message = "Could not load class: " . $class . ". " . $message;
		parent::__construct($message, 0);
	}
}

/**
* File not found exception
*
* @author	Clinton Alexander
*/
class FileNotFoundException extends MissingResourceException { 
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