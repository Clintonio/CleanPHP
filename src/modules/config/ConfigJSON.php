<?php

CleanPHP::import("config.Config");
CleanPHP::import("config.MissingConfigException");

/**
* Configuration accessing class for JSON based configuration files
*
* @author		Clinton Alexander
* @version		2.0
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
			
			if($configArray == NULL) {
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