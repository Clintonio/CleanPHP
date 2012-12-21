<?php
/**
* Configuration accessing class for JSON based configuration files
*
* @author		Clinton Alexander
* @version		2.0
*/

CleanPHP::import("config.Config");
CleanPHP::import("config.MissingConfigException");
CleanPHP::import('io.IOException');

/**
* A configuration implementation for JSON configuration files
*
* @package	config
*/
class ConfigJSON implements Config {
	/** 
	* All Config values
	*/
	private $configArray = array();
	
	/**
	* Config file location
	*/
	private $location;
	
	//============================
	// Constructor
	//============================
	
	/**
	* Loads a config file into the memory
	*
	* @throws	MissingConfigException	Configuration file is missing
	* @param	String		Location of config file
	*/
	public function __construct($configLocation) {
		// Parse config into memory
		if(false === ($config = file_get_contents($configLocation))) {
			throw new MissingConfigException($configLocation . ' is missing');
		} else {
			$configArray = self::parseConfigFile($config);
			
			if($configArray === NULL) {
				throw new MissingConfigException('Config &quot;' . $configLocation . '&quot; invalid format');
			} else {
				$this->location = $configLocation;
				
				// Loop over and set all values to protected
				foreach($configArray as $configName => $configValue) {
					$this->setTempConfig($configName, $configValue, true);
				}
			}
		}
	}
	
	//============================
	// Creation
	//============================
	
	/**
	* Creates an empty config file at the given location, erasing any existing ones
	* then returns it as a new config object
	*
	* @throws	IOException		When the existing file can't be removed, or the
	*							the new one can't be written
	* @param	file	The location of the new config
	* @return	The new config file
	*/
	public static function createConfigFile($file) {
		$file = (string) $file;
		
		if(file_exists($file) && !@unlink($file)) {
			throw new IOException(new String('Could not remove existing file'));
		} else if(!@file_put_contents($file, '{}')) {
			throw new IOException(new String('Could not create new file'));
		} else {
			return new ConfigJSON($file);
		}
	}
	
	//============================
	// Getters
	//============================
	
	/** 
	* Get a config value
	*
	* @throws	MissingConfigException	When a configuration is missing
	* @param	String		Config name
	* @return	String		Config Value
	*/
	public function getConfig($name) {
		$name = (string) $name;
		if(!isset($this->configArray[$name])) {
			throw new MissingConfigException('Config ' . $name . ' does not exist');
		} else {
			return $this->configArray[$name];
		}
	}
	
	//============================
	// Setters
	//============================
	
	/**
	* Set a config value and write to disk
	*
	* @param	String	Config name
	* @param	String	Config value
	* @return	void
	*/
	public function setConfig($name, $value) {
		$this->setTempConfig($name, $value);
		
		$config = json_encode($this->configArray);
		
		file_put_contents($this->location, $config);
	}
	
	/**
	* Set a config value and do not write to disk
	*
	* @param	String	Config name
	* @param	String	Config value
	* @return	void
	*/
	public function setTempConfig($name, $value) {
		$name = (string) $name;
		if(!isset($this->configArray[$name])) {
			$this->configArray[$name] = $value;
		}
	}
	
	//============================
	// Parsers
	//============================
	
	/**
	* Parses a config file into array
	*
	* @param	String		Config file string
	* @return	array		Config name=>value array
	*/
	private static function parseConfigFile($config) {
		$config = json_decode($config, true);
		unset($config['__comment']);
		// Clean out invalid keys
		foreach($config as $key => $value) {
			if((!is_string($value)) || (!is_string($key))) {
				unset($config[$key]);
			}
		}
		
		return $config;
	}
}

?>
