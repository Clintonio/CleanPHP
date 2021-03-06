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

// We need to have a recent version
if(version_compare(PHP_VERSION, '5.3.0', '<')) {
	die('<h1>Critical Error - Site cannot function</h1><br />PHP Version too low, current installed: ' . PHP_VERSION . '. 
		Please install PHP Version 5.3 or contact your system administrator');
}

// Import some core modules
CleanPHP::import("core.CoreString");
CleanPHP::import('core.Session');
CleanPHP::import("core.Cookie");

/**
* Contains the most important functions and
* functionality for the framework to load and
* work
*
* @package		CleanPHP
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
	* Modules that are currently loading
	*/
	private static $loadingModules = array();
	
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
		if(!in_array($class, self::$loadedModules, true) 
			&& !in_array($class, self::$loadingModules, true)) {
			self::$loadingModules[] = $class;
			
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
			
			// Remove the module from the currently loading modules
			if(($key = array_search($class, self::$loadingModules)) !== false) {
				unset(self::$loadingModules[$key]);
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
	// Verifiers
	//============================
		
	/**
	* Check if a given class exists on the module path with the given module
	* name, following the standard module name rules
	*
	* @param	class	The class module being loaded
	* @return	bool	True if the module is found on the module path
	*/
	public static function moduleExists($class) {
		if(!in_array($class, self::$loadedModules, true)) {
			$fileName  = preg_replace("/\\" . self::$moduleSeparator . "/", "/", $class) . ".php";
			// Get class name so we can check import status after inclusion
			$className = explode(self::$moduleSeparator, $class);
			$className = end($className); 
			// Attempt to include from base include folder
			$location  = __DIR__. self::$moduleFolder . $fileName;
			
			$found  = file_exists($location);
			
			// If it has not been found, check user module folders
			if(!$found) {
				$x 		= 0;
				$size 	= count(self::$userModules);
				while(!$found && ($x < $size)) {
					$location = self::$userModules[$x] . $fileName;
					
					$found = file_exists($location);
					$x++;
				}
			}
			
			return $found;
		} else {
			return true;
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
* @package	CleanPHP
* @since 	2
* @version	1
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
* Exception to be thrown when an something that
* should exist does not.Primarily items in the DB
* Or objects
*
* @author	Clinton Alexander
* @package	CleanPHP
* @since 	2
* @version	1
*/
class MissingResourceException extends RuntimeException { }

/**
* Exception to be thrown when an something that
* should exist does not. Primarily array indexes 
* in associative arrays
*
* @author	Clinton Alexander
* @package	CleanPHP
* @since 	2
* @version	1
*/
class MissingIndexException extends MissingResourceException { }

/**
* Resource update failure
* 
* @author	Clinton Alexander
* @package	CleanPHP
* @since	2
* @version	1
*/
class ResourceUpdateException extends RuntimeException { }

/**
* Permissions failure
* 
* @author	Clinton Alexander
* @package	CleanPHP
* @since	2
* @version	1
*/
class PermissionsException extends RuntimeException { }

/**
* Form validation exception
*
* @author	Clinton Alexander
* @package	CleanPHP
* @since	2
* @version	1
*/
class FormValidatorException extends InvalidArgumentException { }

/**
* Unexpected field exception
*
* @author	Clinton Alexander
* @package	CleanPHP
* @since	2
* @version	1
*/
class UnexpectedFieldException extends RuntimeException { }
?>
